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

class PurchaseType
{
    const NEW = 1;
    const UPGRADE = 2;
    const EXTEND = 3;

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
            case self::NEW:
                return 'خرید';
            case self::UPGRADE:
                return 'ارتقا';
            case self::EXTEND:
                return 'تمدید';
            default:
                return ' - ';
        }
    }
}
