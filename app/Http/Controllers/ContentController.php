<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Content;
use App\Services\Uploader\Uploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Types\Self_;

class ContentController extends Controller
{
    private static $uploader;
    public function __construct(Uploader $uploader)
    {
        self::$uploader = $uploader;
    }
    public function index(){
        $contents = Content::all();

        return response()->json([
            'contents'=>$contents
        ],200);
    }
    //upload image
    public function store(Request $request){
        //validate
        $validator = Validator::make($request->all(),[
           'name'=>'required|string',
           'type'=>'required|string',
           'body'=>'required'
        ]);

        if ($validator->fails()){
            return response()->json([
                'errors'=>$validator->errors()->first()
            ],401);
        }
        //save
        $id = $this->update($request->name,$request->type);
        if ($id){
            DB::table('contents')->where('id','=',$id)->update([
                'body'=>$request->body
            ]);
        }else{
            Content::insert([
                'name'=>$request->name,
                'type'=>$request->type,
                'body'=>$request->body
            ]);
        }
        //response
        return response()->json([
            'success'=>'عملیات با موفقیت انجام شد.'
        ],201);
    }

    //update
    public function update($name,$type){
        $checkContent = Content::all()->where('name','=',$name)->where('type','=',$type)->first();

        if (!$checkContent){
            return false;
        }

        return $checkContent->id;
    }


    public function uploadImage(Request $request){
        //validate
        $validator = Validator::make($request->all(),[
            'file'=>'required|file|mimes:jpg,jpeg,png|max:3000',
        ]);

        if ($validator->fails()){
            return response()->json([
                'errors'=>$validator->errors()->first()
            ],401);
        }

        //upload
        $FileName = self::$uploader->upload(false,false);

        return response()->json([
            'fileName'=>asset('storage/image/'.$FileName)
        ],200);
    }
}
