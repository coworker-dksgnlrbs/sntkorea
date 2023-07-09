<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Notice extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $guarded = [
        "id",
    ];

    public function registerMediaCollections():void
    {
        $this->addMediaCollection('files');
    }

    public function getFilesAttribute()
    {
        $items = [];

        $medias = $this->getMedia('files');

        foreach($medias as $media){
            $items[] = [
                "id" => $media->id,
                "name" => $media->file_name,
                "url" => $media->getFullUrl()
            ];
        }

        return $items;
    }

}
