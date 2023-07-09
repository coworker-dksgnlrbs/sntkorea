<?php

use Illuminate\Support\Str;

return [
    'delivery_price' => env('DELIVERY_PRICE', '3000'),
    'delivery_min_discount_price' => env('DELIVERY_MIN_DISCOUNT_PRICE', '30000'), // n만원 이상일 시 배송비 무료
    'point_ratio' => env("POINT_RATIO", '3') // 결제 시 적립금 %
];
