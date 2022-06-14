<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class LikesController extends Controller
{
    public function store(Request $request,Post $post){
        if (auth()->check()){
            $post->likes()->sync(
                $request->user()->id,
                false
            );
        }
    }

    public function destroy(Request $request,Post $post){
        if (auth()->check()){
            $post->likes()->detach(
                $request->user()->id
            );
        }
    }
}
