<?php

namespace App\Constants;

use ReflectionClass;
use ReflectionException;

class PremiumReportType
{
    const FULL = 1;
    const DAILY = 2;
    const MONTHLY = 3;

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
            case self::FULL:
                return 'کل';
            case self::DAILY:
                return 'به تفکیک روز';
            case self::MONTHLY:
                return 'به تفکیک ماه';
            default:
                return '';
        }
    }
}
