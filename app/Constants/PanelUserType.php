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

class PanelUserType
{
    const ADMIN = 1;
    const MARKETING = 2;
    const SECRETARY = 3;

    /**
     * @return array
     * @throws ReflectionException
     */
    public static function toArray()
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
