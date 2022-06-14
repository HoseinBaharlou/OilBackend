<?php

namespace App\Http\Resources;

use App\Http\Resources\Post\postResource;
use Illuminate\Http\Resources\Json\JsonResource;

class categoryShowPostResource extends JsonResource
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
          postResource::collection($this->posts)
        ];
    }
}
