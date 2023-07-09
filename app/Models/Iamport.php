<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Iamport extends Model
{
    use HasFactory;

    // 결제취소
    public static function cancel($accessToken, $imp_uid, $message = "결제실패")
    {
        return Http::withHeaders([
            "Content-Type" => "application/json",
            "Authorization" => $accessToken
        ])->post("https://api.iamport.kr/payments/cancel", [
            "reason" => $message,
            "imp_uid" => $imp_uid,
        ])->json();
    }

    // 아임포트 결제요청 권한 얻기
    public static function getAccessToken()
    {
        $response = Http::withHeaders([
            "Content-Type" => "application/json"
        ])->post("https://api.iamport.kr/users/getToken", [
            "imp_key" => config("iamport.key"),
            "imp_secret" => config("iamport.secret"),
        ])->json();

        return $response["response"]["access_token"];
    }

    // 아임포트 주문조회
    public static function getOrder($accessToken, $imp_uid)
    {
        return Http::withHeaders([
            "Content-Type" => "application/json",
            "Authorization" => $accessToken
        ])->get("https://api.iamport.kr/payments/{$imp_uid}", [
            "imp_key" => config("iamport.key"),
            "imp_secret" => config("iamport.secret"),
        ])->json()["response"];
    }
}
