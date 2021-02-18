<?php

namespace App\Constants;

use ReflectionClass;
use ReflectionException;

class BannerStatus
{
    const ACTIVE = 1;
    const EXPIRED = 2;
    const NOT_STARTED = 3;

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
            case self::ACTIVE:
                return 'فعال';
            case self::EXPIRED:
                return 'منقضی';
            case self::NOT_STARTED:
                return 'شروع نشده';
            default:
                return '';
        }
    }
}
