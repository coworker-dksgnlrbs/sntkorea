<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;

class Portfolio extends Model implements HasMedia
{
    use HasFactory, \Spatie\MediaLibrary\InteractsWithMedia;

    protected $guarded = ["id"];

    protected $casts = [
        "started_at" => "date",
        "finished_at" => "date",
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('top')->singleFile();
        $this->addMediaCollection('pc')->singleFile();
        $this->addMediaCollection('mobile')->singleFile();
        $this->addMediaCollection('imgs');
    }

    public function getTopAttribute()
    {
        if($this->hasMedia('top')) {
            $media = $this->getMedia('top')[0];

            return [
                "name" => $media->file_name,
                "url" => $media->getFullUrl()
            ];
        }

        return null;
    }

    public function getPcAttribute()
    {
        if($this->hasMedia('pc')) {
            $media = $this->getMedia('pc')[0];

            return [
                "name" => $media->file_name,
                "url" => $media->getFullUrl()
            ];
        }

        return null;
    }

    public function getMobileAttribute()
    {
        if($this->hasMedia('mobile')) {
            $media = $this->getMedia('mobile')[0];

            return [
                "name" => $media->file_name,
                "url" => $media->getFullUrl()
            ];
        }

        return null;
    }

    public function getImgsAttribute()
    {
        $items = [];

        if ($this->hasMedia('imgs')) {
            $medias = $this->getMedia('imgs');

            foreach ($medias as $media) {
                $items[] = [
                    "name" => $media->file_name,
                    "url" => $media->getFullUrl()
                ];
            }
        }

        return $items;
    }
}
