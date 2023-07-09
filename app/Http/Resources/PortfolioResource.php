<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PortfolioResource extends JsonResource
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
            "imgs" => $this->imgs,
            "top" => $this->top ?? "",
            "mobile" => $this->mobile ?? "",
            "pc" => $this->pc ?? "",
            "category" => $this->category,
            "company" => $this->company,
            "subtitle" => $this->subtitle,
            "title" => $this->title,
            "description" => $this->description,
            "url" => $this->url,
            "started_at" => $this->started_at ? Carbon::make($this->started_at)->format("Y.m") : "",
            "finished_at" => $this->started_at ? Carbon::make($this->finished_at)->format("Y.m") : "",
        ];
    }
}
