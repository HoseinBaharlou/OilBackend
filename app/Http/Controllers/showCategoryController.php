<?php

namespace App\Http\Controllers;

use App\Http\Resources\categoryShowPostResource;
use App\Http\Resources\categoryShowResource;
use App\Http\Resources\Post\postResource;
use App\Http\Resources\Post\showPostResource;
use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Http\Request;

class showCategoryController extends Controller
{
    public function shop($slug){
        $category = Category::all()->where('slug','=',$slug);

        if ($category){
            return response()->json([
                'product'=>categoryShowResource::collection($category)
            ],200);
        }else{
            return abort(404);
        }
    }

    public function post($slug){
        $category = Category::with('posts')->where('slug','=',$slug)->get();

        if ($category){
            return response()->json([
                'posts'=>categoryShowPostResource::collection($category)
            ],200);
        }else{
            return abort(404);
        }
    }
}
