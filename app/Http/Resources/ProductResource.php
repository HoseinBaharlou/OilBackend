<?php

namespace App\Http\Resources;

use App\Services\convert_date\convert_date;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'volume'=>$this->volume,
            'unit'=>$this->unit,
            'body'=>$this->body,
            'file'=>asset('storage/image/'.$this->file),
            'category_id'=>$this->category_id,
            'created_at'=>convert_date::jalali($this->created_at),
            'updated_at'=>convert_date::jalali($this->updated_at)
        ];
    }
}
