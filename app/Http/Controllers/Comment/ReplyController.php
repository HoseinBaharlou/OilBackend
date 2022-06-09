<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReplyController extends Controller
{
    public function store(Request $request,Post $post){
        //validate
        $validator = Validator::make($request->all(),[
           'content'=>'required|string|min:10',
            'comment_id'=>'required'
        ]);

        if ($validator->fails()){
            return response()->json([
                'errors'=>$validator->errors()->first()
            ],401);
        }

        //save
        $post->comments()->save(new Comment($request->only(['content','comment_id'])));

        //response
        return response()->json([
            'success'=>'پاسخ با موفقیت ارسال شد.'
        ],201);
    }
}
