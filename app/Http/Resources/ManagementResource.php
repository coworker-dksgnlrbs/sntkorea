<?php

namespace App\Http\Resources;

use App\Models\Management;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ManagementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $next = Management::find(Management::where('id', '<', $this->id)->max('id'));

        $prev = Management::find(Management::where('id', '>', $this->id)->min('id'));

        $createdDate = Carbon::parse($this->created_at);

        $currentDate = Carbon::now();

        $new = $createdDate->diffInDays($currentDate) <= 1 ? 1 : 0;

        return [
            "id" => $this->id,
            "title" => $this->title,
            "description" => $this->description,
            "files" => $this->files,
            "year" => Carbon::make($this->created_at)->format("Y"),
            "month" => Carbon::make($this->created_at)->format("m"),
            "day" => Carbon::make($this->created_at)->format("d"),
            "prev" => $prev ?? "",
            "next" => $next ?? "",
            "new" => $new ?? "",
            "created_at" => Carbon::make($this->created_at)->format("Y.m.d")
        ];
    }
}
