<?php

namespace App\Constants;

use App\Models\PanelUser;
use ReflectionClass;
use ReflectionException;

class LogType
{
    const BURN_USER = 1;

    const RESPONSE_FEEDBACK = 2;
    const NEW_COMMENT = 3;
    const EDIT_FEEDBACK = 4;

    const NEW_ANNOUNCEMENT = 5;
    const EDIT_ANNOUNCEMENT = 6;
    const DELETE_ANNOUNCEMENT = 7;

    const NEW_BANNER = 8;
    const EDIT_BANNER = 9;
    const DELETE_BANNER = 10;

    const NEW_PROMO_CODE = 11;
    const EDIT_PROMO_CODE = 12;

    const NEW_AUTOMATION_CALL = 13;
    const EDIT_AUTOMATION_CALL = 14;
    const NEW_AUTOMATION_MISS_CALL = 20;

    const EDIT_WALLET = 15;
    const NEW_INVOICE = 16;
    const PAY_INVOICE = 17;
    const CLOSE_PLAN = 18;
    const DELETE_INVOICE = 19;

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
            case self::EDIT_FEEDBACK:
                return $user->name . ' بازخوردی را ویرایش کرد.';
            case self::NEW_ANNOUNCEMENT:
                return $user->name . ' اعلان جدیدی را ثبت کرد.';
            case self::EDIT_ANNOUNCEMENT:
                return $user->name . ' اعلانی را ویرایش کرد.';
            case self::DELETE_ANNOUNCEMENT:
                return $user->name . ' اعلانی را حذف کرد.';
            case self::NEW_BANNER:
                return $user->name . ' بنر جدیدی را ثبت کرد.';
            case self::EDIT_BANNER:
                return $user->name . ' بنری را ویرایش کرد.';
            case self::DELETE_BANNER:
                return $user->name . ' بنری را حذف کرد.';
            case self::NEW_PROMO_CODE:
                return $user->name . ' کد تخفیف جدیدی را ایجاد کرد.';
            case self::EDIT_PROMO_CODE:
                return $user->name . ' کد تخفیفی را ویرایش کرد.';
            case self::EDIT_WALLET:
                return $user->name . ' کیف پولی را تغییر داد.';
            case self::NEW_INVOICE:
                return $user->name . ' یک پیش‌فاکتور جدید ساخته است.';
            case self::PAY_INVOICE:
                return $user->name . ' یک پیش‌فاکتور را پرداخت کرد.';
            case self::CLOSE_PLAN:
                return $user->name . ' یک طرح را اتمام کرد.';
            case self::NEW_AUTOMATION_CALL:
                return $user->name . ' یک تماس جدید ثبت کرده است.';
            case self::EDIT_AUTOMATION_CALL:
                return $user->name . ' یک تماس را ویرایش کرد.';
            case self::DELETE_INVOICE:
                return $user->name . ' یک پیش‌فاکتور را حذف کرد.';
            case self::NEW_AUTOMATION_MISS_CALL:
                return $user->name . ' یک تماس بی پاسخ ثبت کرد.';
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
            case self::EDIT_FEEDBACK:
                return 'ویرایش بازخورد';
            case self::NEW_ANNOUNCEMENT:
                return 'ثبت اعلان';
            case self::EDIT_ANNOUNCEMENT:
                return 'ویرایش اعلان';
            case self::DELETE_ANNOUNCEMENT:
                return 'حذف اعلان';
            case self::NEW_BANNER:
                return 'ثبت بنر';
            case self::EDIT_BANNER:
                return 'ویرایش بنر';
            case self::DELETE_BANNER:
                return 'حذف بنر';
            case self::NEW_PROMO_CODE:
                return 'ایجاد کد تخفیف';
            case self::EDIT_PROMO_CODE:
                return 'ویرایش کد تخفیف';
            case self::EDIT_WALLET:
                return 'ویرایش کیف پول';
            case self::NEW_INVOICE:
                return 'ساخت پیش‌فاکتور';
            case self::PAY_INVOICE:
                return 'پرداخت پیش‌فاکتور';
            case self::CLOSE_PLAN:
                return 'اتمام طرح';
            case self::NEW_AUTOMATION_CALL:
                return 'تماس اتوماسیون';
            case self::EDIT_AUTOMATION_CALL:
                return 'ویرایش تماس اتوماسیون';
            case self::DELETE_INVOICE:
                return 'اتمام پیش‌فاکتور';
            case self::NEW_AUTOMATION_MISS_CALL:
                return 'ثبت تماس بی پاسخ';
            default:
                return '';
        }
    }
}
