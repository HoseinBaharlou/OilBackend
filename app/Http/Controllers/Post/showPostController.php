<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Resources\Post\showPostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class showPostController extends Controller
{
    public function index(){
        $post = Post::all()->loadCount('likes')->append('is_liked');
        return response()->json([
            'posts'=>showPostResource::collection($post)
        ],200);
    }

//    show post
    public function show($id){
        $post = Post::findOrFail($id)->load(['parentComments'])->loadCount('likes')->append('is_liked');

        return response()->json([
            'post'=>new showPostResource($post)
        ],200);
    }
}
