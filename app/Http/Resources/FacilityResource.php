<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FacilityResource extends JsonResource
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
            "id" => $this->id,
            "img" => $this->img,
            "category" => $this->category,
            "title" => $this->title,
            "contact" => $this->contact,
            "address" => $this->address,
            "description" => $this->description,
        ];
    }
}
