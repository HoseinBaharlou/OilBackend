<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function store(Request $request){
        //validate
        $validator = Validator::make($request->only('title','text','writer','date','keyword','category'),[
            'title'=>'required|string',
            'text'=>'required|string',
            'writer'=>'required|string',
            'date'=>'required|date_format:Y-m-d',
            'category'=>'required|numeric',
            'keyword'=>'required|array|max:20',
        ]);

        //check validate
        if ($validator->fails()){
            return response()->json([
                'errors'=>$validator->errors()->first()
            ],401);
        }
        //get keyword
        $keyword = implode(',',$request->keyword);
        // insert value
        Article::create([
            'title'=>$request->title,
            'text'=>$request->text,
            'writer'=>$request->writer,
            'category_id'=>$request->category,
            'keyword'=>$keyword,
            'created_at'=>$request->date,
            'updated_at'=>$request->date
        ]);
        //response
        return response()->json([
            'success'=>'مقاله با موفقیت ایجاد شد.'
        ],201);
    }
}
