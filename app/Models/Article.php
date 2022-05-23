<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = ['id','title','text','writer','keyword','created_at','updated_at','category_id'];

    public $timestamps = false;
}
