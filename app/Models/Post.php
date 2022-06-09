<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['id','title','text','writer','keyword','created_at','updated_at','category_id','file'];

    public $timestamps = false;
    //relashonShip likes table
    public function likes(){
        return $this->belongsToMany(User::class,'likes');
    }

    public function getIsLikedAttribute(){
        return $this->likes()->where('user_id',optional(auth()->user())->id)->exists();
    }

//    public function getCountWriter(){
//        Post::all()->where()
//    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function parentComments(){
        return $this->comments()->whereNull('comment_id');
    }
}
