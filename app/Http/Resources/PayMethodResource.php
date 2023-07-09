<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PayMethodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "pg" => $this->pg,
            "method" => $this->method,
            "name" => $this->name,
            "commission" => $this->commission,
            "img" => $this->img
        ];
    }
}
