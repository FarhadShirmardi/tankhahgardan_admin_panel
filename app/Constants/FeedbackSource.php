<?php

namespace App\Constants;

use ReflectionClass;
use ReflectionException;

class FeedbackSource
{
    const APPLICATION = 1;
    const PHONE = 2;
    const LANDING_CONTACT = 3;
    const TELEGRAM = 4;
    const STORES = 5;

    /**
     * @return array
     * @throws ReflectionException
     */
    public static function toArray()
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }

    public static function getEnum($enum)
    {
        switch ($enum) {
            case self::APPLICATION:
                return 'درون برنامه‌ای';
            case self::PHONE:
                return 'تلفنی';
            case self::TELEGRAM:
                return 'تلگرام';
            case self::STORES:
                return 'استورها';
            case self::LANDING_CONTACT:
                return 'نظرات لندینگ';
            default:
                return '';
        }
    }
}
