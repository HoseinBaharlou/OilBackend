<?php

namespace App\Http\Resources\Post;

use Illuminate\Http\Resources\Json\JsonResource;

class EditPostResource extends JsonResource
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
            'category_id'=>$this->category_id,
            'updated_at'=>explode(' ',$this->updated_at)[0],
        ];
    }
}
