<?php
/**
 * Created by PhpStorm.
 * User: master
 * Date: 2021-01-31
 * Time: 오후 4:04
 */

namespace App\Enums;


final class KakaoTemplate
{
    const VERIFY_NUMBER = "VERIFY_NUMBER"; // 결제실패
    const START_DELIVERY = "START_DELIVERY"; // 배송시작
    const SUCCESS_ORDER = "SUCCESS_ORDER"; // 주문성공

    public static function getTemplateId($template)
    {
        $templateIds = [
            self::VERIFY_NUMBER =>  "KA01TP220124174858208VphRybsidLo",
            self::START_DELIVERY =>  "KA01TP2201240857533423q0iOCGHTV5",
            self::SUCCESS_ORDER =>  "KA01TP220124084923934EaxIXI8MeUz",
        ];

        return $templateIds[$template];
    }

    public static function getValues()
    {
        return [self::VERIFY_NUMBER, self::START_DELIVERY, self::SUCCESS_ORDER];
    }
}
