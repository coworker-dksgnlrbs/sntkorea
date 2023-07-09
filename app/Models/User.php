<?php

namespace App\Models;

use App\Enums\CouponTypeDiscount;
use App\Enums\CouponTypeExpired;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, InteractsWithMedia;

    use SoftDeletes;

    protected $fillable = [
        'contact',
        "point",
        'name',
        'password',
        "verified_at",
        "social_id",
        "social_platform",
        "accepted",
        "agree_ad",
        "order_name",
        "order_contact",
        "reason_leave_out",

        "account",
        "bank",
        "owner"
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ["img"];

    public function registerMediaCollections():void
    {
        $this->addMediaCollection('img')->singleFile();
    }

    public function getImgAttribute()
    {
        if($this->hasMedia('img')) {
            $media = $this->getMedia('img')[0];

            return [
                "name" => $media->file_name,
                "url" => $media->getFullUrl()
            ];
        }

        return null;
    }

    public function diets()
    {
        return $this->hasMany(Diet::class);
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }

    public function coupons()
    {
        return $this->belongsToMany(Coupon::class)->withPivot(["used", "expired_at"]);
    }

    public function expiredCoupons()
    {
        return $this->coupons()
            ->wherePivot("used", false)
            ->wherePivot("expired_at", "<", Carbon::now()->startOfDay()->format("Y-m-d"));
    }

    public function validCoupons()
    {
        return $this->coupons()
            ->wherePivot("used", false)
            ->wherePivot("expired_at", ">=", Carbon::now()->startOfDay()->format("Y-m-d"));
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function verifyOrderNumbers()
    {
        return $this->hasMany(VerifyOrderNumber::class);
    }

    public function qnas()
    {
        return $this->hasMany(Qna::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

}
