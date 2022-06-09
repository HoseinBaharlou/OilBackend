<?php

namespace App\Http\Controllers\Comment;

use App\Events\commentCreatedEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\commentResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function store(Request $request,Post $post){
        //check validate
        $validator = Validator::make($request->only('content'),[
           'content'=>'required|string'
        ]);

        if ($validator->fails()){
            return response()->json([
                'errors'=>$validator->errors()->first()
            ],401);
        }

        $post->comments()->save(
            $comment = new Comment($request->only('content'))
        );

        event(new commentCreatedEvent(
            $comment->load(['user','post','replies','parent'])
        ));
        //response
        return response()->json([
            'success'=>'دیدگاه شما با موفقیت ارسال شد.'
        ],201);
    }

    //destroy
    public function destroy(Comment $comment){
        $comment->delete();

        //response
        return response()->json([
            'success'=>'کامنت مورد نظر با موفقیت حذف شد.'
        ],200);
    }
}
