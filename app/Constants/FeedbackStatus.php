<?php

namespace App\Constants;

use ReflectionClass;
use ReflectionException;

class FeedbackStatus
{
    const NOT_ANSWERED = 1;
    const NEED_RE_EXAMINED = 2;
    const DOING = 3;
    const DONE = 4;
    const CLOSED = 5;
    const SPAM = 6;

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
            case self::NOT_ANSWERED:
                return 'بدون پاسخ';
            case self::SPAM:
                return 'SPAM';
            case self::NEED_RE_EXAMINED:
                return 'نیاز به بررسی مجدد';
            case self::DOING:
                return 'در حال انجام';
            case self::DONE:
                return 'انجام شده';
            case self::CLOSED:
                return 'اتمام شده';
            default:
                return '';
        }
    }
}
