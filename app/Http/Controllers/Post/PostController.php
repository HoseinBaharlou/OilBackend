<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Resources\Post\EditPostResource;
use App\Http\Resources\Post\postResource;
use App\Http\Resources\Post\showPostResource;
use App\Models\Post;
use App\Services\Uploader\StorageManager;
use App\Services\Uploader\Uploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    private static $uploader;
    public function __construct(Uploader $uploader)
    {
        self::$uploader = $uploader;
    }
    //list post
    public function index(){
        $post = Post::withoutTrashed()->get();
        return response()->json([
            'post'=>postResource::collection($post)
        ],200);
    }
    //create post
    public function store(Request $request){
        //validate
        $validator = Validator::make($request->only(['title','text','writer','date','keyword','category','file']),[
            'title'=>'required|string',
            'text'=>'required|string',
            'writer'=>'required|string',
            'date'=>'required|date_format:Y-m-d',
            'category'=>'required|numeric',
            'keyword'=>'required|array|max:20',
            'file'=>'required|file|mimes:png,jpeg,jpg',
        ]);

        //check validate
        if ($validator->fails()){
            return response()->json([
                'errors'=>$validator->errors()->first()
            ],401);
        }
        //upload file
        $FilesName = self::$uploader->upload(false,true);
//        return response()->json(['success'=>$FilesName]);
        //get keyword
        $keyword = implode(',',$request->keyword);
        // insert value
        Post::create([
            'title'=>$request->title,
            'text'=>$request->text,
            'writer'=>$request->writer,
            'category_id'=>$request->category,
            'keyword'=>$keyword,
            'file'=>$FilesName,
            'created_at'=>$request->date,
            'updated_at'=>$request->date
        ]);
        //response
        return response()->json([
            'success'=>'مقاله با موفقیت ایجاد شد.'
        ],201);
    }
    //show post
    public function show($id){

    }
    //show post for edit
    public function edit($id){
        $post = Post::findOrFail($id);
        return response()->json([
            'post'=>new EditPostResource($post)
        ],200);
    }

    //update post
    public function update(Request $request,Post $post){
        //validate
        $validator = Validator::make($request->all(),[
            'title'=>'required|string',
            'text'=>'required|string',
            'writer'=>'required|string',
            'date'=>'required|date_format:Y-m-d',
            'category'=>'required|numeric',
            'keyword'=>'required|array|max:20',
            'file'=>'nullable|file|mimes:png,jpeg,jpg',
        ]);
        //check validate
        if ($validator->fails()){
            return response()->json([
                'errors'=>$validator->errors()->first()
            ],401);
        }
        //upload file
        if ($request->file){
            $FilesName = self::$uploader->upload(false,true);
            //remove file
            $path = storage_path('app/public/image/'.$post->file);
            File::delete($path);
            $post->file = $FilesName;
            $post->save();
        }
        //update data
        $data = $request->only(['title','text','writer','date','category']);
        $data['keyword'] = implode(',',$request->keyword);
        $post->update($data);

        $post->save();

        return response()->json([
            'success'=>'پست با موفقیت ویرایش شد.'
        ],201);
    }

    public function destroy($id)
    {
        $product = Post::withTrashed()->findOrFail($id);
        StorageManager::deleteFile($product->file,'image',false);
        Post::withTrashed()->findOrFail($id)->forceDelete();
        return response()->json([
            'success'=>'پست با موفقیت حذف شد.'
        ],200);
    }
//    soft delete
    public function softDelete($id){
        Post::destroy($id);
        return response()->json([
            'success'=>'پست با موفقیت به سطل زباله منتقل شد.'
        ],200);
    }
    //trash
    public function trash(){
        $product = Post::onlyTrashed()->get();

        return response()->json([
            'trash'=>postResource::collection($product)
        ],200);
    }

    //restore
    public function restore($id){
        Post::onlyTrashed()->findOrFail($id)->restore();
        return response()->json([
            'success'=>'پست با موفقیت به لیست پست ها بازگشت.'
        ],200);
    }
}
