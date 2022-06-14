<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['title','parent_id','type'];
    public $timestamps = false;
    protected $with = ['child'];
    public function product(){
        return $this->hasMany(Product::class);
    }

    public function posts(){
        return $this->hasMany(Post::class);
    }

    public function parent(){
        return $this->belongsTo(Category::class)->where('parent_id','!=',null);
    }

    public function child(){
        return $this->hasMany(Category::class,'parent_id')->where('parent_id','!=',null);
    }
}
