<?php
/**
 * Created by PhpStorm.
 * User: farhad
 * Date: 10/6/18
 * Time: 5:25 PM
 */

namespace App\Constants;

use ReflectionClass;
use ReflectionException;

class UserPremiumState
{
    const FREE = 1;
    const PREMIUM = 2;
    const EXPIRED_PREMIUM = 3;
    const NEAR_ENDING_PREMIUM = 4;

    /**
     * @return array
     */
    public static function toArray()
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }

    public static function getEnum($enum)
    {
        switch ($enum) {
            case self::FREE:
                return 'رایگان';
            case self::PREMIUM:
                return 'پولی';
            case self::EXPIRED_PREMIUM:
                return 'اتمام اعتبار';
            case self::NEAR_ENDING_PREMIUM:
                return 'نزدیک به پایان';
            default:
                return '';
        }
    }
}
