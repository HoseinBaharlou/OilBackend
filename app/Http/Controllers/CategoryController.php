<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    public function index(){
        $category = Category::all();
        return response()->json([
            'category'=>$category[0]
        ],200);
    }
    //check category
    public function CheckCategory($title,$parent_id){
        $check = Category::where($title,'=','title')->where('parent_id','=', $parent_id);
        if ($check){
            return false;
        }else{
            return true;
        }
    }
    // store category
    public function store(Request $request){
        //check table row
        //validate
        $validator = Validator::make( $request->only(['title','parent_id','type']),[
            'title'=>'required|string|min:1',
            'parent_id'=>'required|integer',
            'type'=>'required|string|max:10|min:4'
        ]);

        if ($validator->fails()){
            return response()->json([
                'errors'=>$validator->errors()->first()
            ],401);
        }
        if (ctype_digit($request->title)){
            return response()->json([
                'errors'=>'.عنوان نمیتواند عدد باشد'
            ],401);
        }
        // check category in table
        $check = $this->CheckCategory($request->title,$request->parent_id);
        if ($check){
            return response()->json([
                'errors'=>'دسته بندی مورد نظر قبلا ایجاد شده است.'
            ],404);
        }
        $slug = implode('_',explode(' ',$request->title));
        // add to category
        Category::insert([
            'title'=>$request->title,
            'slug'=>$slug,
            'parent_id'=>$request->parent_id,
            'type'=>$request->type
        ]);

        return response()->json([
            'success'=>'دسته بندی با موفقیت ایجاد شد.'
        ],201);
    }

    // show category
    public function show(){
        $category = Category::all();
        return response()->json([
            'category'=>CategoryResource::collection($category)
        ],200);
    }


}
