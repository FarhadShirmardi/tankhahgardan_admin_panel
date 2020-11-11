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

class ProjectStatusType
{
    const FAILED = 0;
    const SUCCEED = 1;
    const PENDING = 2;

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
            case self::FAILED:
                return 'ناموفق';
            case self::SUCCEED:
                return 'موفق';
            case self::PENDING:
                return 'در انتظار';
            default:
                return ' - ';
        }
    }
}
