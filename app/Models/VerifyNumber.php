<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifyNumber extends Model
{
    use HasFactory;

    protected $fillable = ["contact", "number", "verified"];
}
