<?php

namespace App\Constants;

use ReflectionClass;
use ReflectionException;

class Platform
{
    const ANDROID = 1;
    const WEB = 3;

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
            case self::ANDROID:
                return 'اندروید';
            case self::WEB:
                return 'وب';
            default:
                return ' - ';
        }
    }
}
