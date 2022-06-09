<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $with = ['replies','user'];
    public static function booted()
    {
        parent::saving(function($comment){
            $comment->user_id = request()->user()->id;
        });
    }

    //relashonship
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function post(){
        return $this->belongsTo(Post::class);
    }

    public function parent(){
        return $this->belongsTo(Comment::class);
    }

    public function replies(){
        return $this->hasMany(Comment::class);
    }
}
