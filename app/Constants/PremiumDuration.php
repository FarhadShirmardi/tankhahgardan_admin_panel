<?php
/**
 * Created by PhpStorm.
 * User: farhad
 * Date: 10/6/18
 * Time: 5:25 PM
 */

namespace App\Constants;

use ReflectionClass;

class PremiumDuration
{
    const MONTH = 1;
    const HALF_MONTH = 2;
    const ONE_WEEK = 3;
    const YEAR = 4;
    const SPECIAL = 5;


    public static function toArray(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }


    public static function getTitle($enum)
    {
        switch ($enum) {
            case self::ONE_WEEK:
                return trans('names.one_week');
            case self::MONTH:
                return trans('names.one_month');
            case self::YEAR:
                return trans('names.one_year');
            case self::HALF_MONTH:
                return trans('names.half_month');
            case self::SPECIAL:
                return 'ویژه';
            default:
                return ' - ';
        }
    }

    public static function getSecondTitle($enum)
    {
        switch ($enum) {
            case self::ONE_WEEK:
                return trans('names.one_week_2');
            case self::MONTH:
                return trans('names.one_month_2');
            case self::YEAR:
                return trans('names.one_year_2');
            case self::HALF_MONTH:
                return trans('names.half_month_2');
            default:
                return ' - ';
        }
    }
}
