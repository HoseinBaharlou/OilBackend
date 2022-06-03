<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\Uploader\Uploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    private static $uploader;
    public function __construct(Uploader $uploader)
    {
        self::$uploader = $uploader;
    }
    public function index(){
        $product = Product::all();

        return response()->json([
            'product'=>ProductResource::collection($product)
        ],200);
    }
    //create product
    public function store(Request $request)
    {
        //validate
        $validator = Validator::make($request->all(),[
           'title'=>'required|string|max:100',
           'volume'=>'required|string|max:100',
            'unit'=>'required|string|min:2|max:10',
            'category_id'=>'required|numeric',
            'file'=>'required|file|mimes:png,jpeg,jpg',
            'body'=>'required|string|min:10'
        ]);
        //check validate
        if ($validator->fails()){
            return response()->json([
                'errors'=>$validator->errors()->first()
            ],401);
        }

        //upload file
        $FilesName = self::$uploader->upload(false,true);
        //create product
        Product::create([
           'title'=>$request->title,
           'volume' =>$request->volume,
            'unit'=>$request->unit,
            'category_id'=>$request->category_id,
            'file'=>$FilesName,
            'body'=>$request->body
        ]);
        //res
        return response()->json([
            'success'=>'محصول با موفقیت ایجاد شد.'
        ],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return response()->json([
            'product'=>new ProductResource($product)
        ],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //validate
        $validator = Validator::make($request->all(),[
            'title'=>'required|string|max:100',
            'volume'=>'required|string|max:100',
            'unit'=>'required|string|min:2|max:10',
            'category_id'=>'required|numeric',
            'file'=>'nullable|file|mimes:png,jpeg,jpg',
            'body'=>'required|string|min:10'
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
            $path = storage_path('app/public/image/'.$product->file);
            File::delete($path);
            $product->file = $FilesName;
            $product->save();
        }
        //update data
        $data = $request->only(['title','volume','unit','category_id','body']);

        $product->update($data);

        $product->save();

        return response()->json([
            'success'=>'محصول با موفقیت ویرایش شد.'
        ],201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
