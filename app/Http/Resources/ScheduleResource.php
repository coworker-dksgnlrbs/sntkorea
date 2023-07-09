<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
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
            "title" => $this->title,
            "url" => $this->url,
            "place" => $this->place,
            "started_at" => Carbon::make($this->started_at)->format("Y.m.d"),
            "finished_at" => Carbon::make($this->finished_at)->format("m.d"),
            "count_participant" => $this->count_participant,
            "host" => $this->host,
            "management" => $this->management,
            "event" => $this->event,
            "state" => $this->getState(),
        ];
    }
}
