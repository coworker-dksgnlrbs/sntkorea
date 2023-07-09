<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;

    protected $fillable = ["contact", "token"];

    protected $primaryKey = "contact";

    public $timestamps = false;

    public function resetUrl()
    {
        return config("app.url")."/passwordResets/$this->token/edit";
    }
}
