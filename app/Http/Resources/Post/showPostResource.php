<?php

namespace App\Http\Resources\Post;

use App\Services\convert_date\convert_date;
use Illuminate\Http\Resources\Json\JsonResource;

class showPostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'title'=>$this->title,
            'writer'=>$this->writer,
            'text'=>$this->text,
            'file'=>asset('storage/image/'.$this->file),
            'keyword'=>$this->keyword,
            'likes_count'=>$this->likes_count,
            'is_liked'=>$this->is_liked,
            'comments'=>$this->comments,
            'category_id'=>$this->category_id,
            'updated_at'=>convert_date::jalali($this->updated_at),
        ];
    }
}
