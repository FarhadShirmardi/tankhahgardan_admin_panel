<?php

namespace App\Constants;

use App\PanelUser;
use ReflectionClass;
use ReflectionException;

class LogType
{
    const BURN_USER = 1;

    const RESPONSE_FEEDBACK = 2;
    const NEW_COMMENT = 3;
    const EDIT_COMMENT = 4;

    /**
     * @return array
     * @throws ReflectionException
     */
    public static function toArray()
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }

    public static function getDescription($enum, PanelUser $user)
    {
        switch ($enum) {
            case self::BURN_USER:
                return $user->name . ' یک کاربر را به لیست سوخته اضافه کرد.';
            case self::RESPONSE_FEEDBACK:
                return $user->name . ' بازخوردی را پاسخ داد.';
            case self::NEW_COMMENT:
                return $user->name . ' بازخورد جدیدی را ثبت کرد.';
            case self::EDIT_COMMENT:
                return $user->name . ' بازخوردی را ویرایش کرد.';
        }
    }

    public static function getTitle($enum)
    {
        switch ($enum) {
            case self::BURN_USER:
                return 'سوزاندن کاربر';
            case self::RESPONSE_FEEDBACK:
                return 'پاسخ بازخورد';
            case self::NEW_COMMENT:
                return 'ثبت بازخورد';
            case self::EDIT_COMMENT:
                return 'ویرایش بازخورد';
            default:
                return '';
        }
    }
}
