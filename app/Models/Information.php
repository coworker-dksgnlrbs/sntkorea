<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    use HasFactory;

    protected $fillable = [
        "name_company",
        "name_president",
        "number_company",
        "number_shop",
        "contact",
        "address",
        "charger_privacy",
        "facebook",
        "instagram",
        "kakao",
        "youtube",

        "info_eat",
        "info_delivery",
        "info_refund",
    ];
}
