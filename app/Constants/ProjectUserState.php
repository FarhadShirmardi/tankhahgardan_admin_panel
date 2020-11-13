<?php

namespace App\Constants;

use ReflectionClass;
use ReflectionException;

class ProjectUserState
{
    const PENDING = 2;
    const ACTIVE = 1;
    const FORBIDDEN = 30;
    const INACTIVE = 40;
    const FORBIDDEN_PENDING = 3;
    const INACTIVE_PENDING = 4;

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
