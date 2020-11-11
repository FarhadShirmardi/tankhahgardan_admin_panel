<?php
/**
 * Created by PhpStorm.
 * User: farhad
 * Date: 10/6/18
 * Time: 5:25 PM
 */

namespace App\Constants;

class PremiumDuration
{
    const MONTH = 1;
    const HALF_MONTH = 2;
    const YEAR = 4;

    public static function getTitle($enum)
    {
        switch ($enum) {
            case self::MONTH:
                return trans('names.one_month');
            case self::YEAR:
                return trans('names.one_year');
            case self::HALF_MONTH:
                return trans('names.half_month');
            default:
                return ' - ';
        }
    }

    public static function getSecondTitle($enum)
    {
        switch ($enum) {
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
