<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class QnaResource extends JsonResource
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
            "company" => $this->company,
            "name" => $this->name,
            "contact" => $this->contact,
            "email" => $this->email,
            "type" => $this->type,
            "service" => $this->service,
            "budget" => $this->budget,
            "url" => $this->url,
            "files" => $this->files,
            "opened_at" => $this->opened_at,
            "memo" => $this->memo,
            "created_at" => Carbon::make($this->created_at)->format("m.d"),
        ];
    }
}
