<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request){
        return response()->json([
            'okk'
        ],201);
    }
}
