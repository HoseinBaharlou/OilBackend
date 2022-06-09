<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function index(){
        return Category::with(['products','posts'])->get();
    }
}
