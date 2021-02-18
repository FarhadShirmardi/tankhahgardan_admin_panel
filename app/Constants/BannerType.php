<?php

namespace App\Constants;

use ReflectionClass;
use ReflectionException;

class BannerType
{
    const PUBLIC = 1;
    const PRIVATE = 2;

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
            case self::PUBLIC:
                return 'عمومی';
            case self::PRIVATE:
                return 'خصوصی';
            default:
                return '';
        }
    }
}
