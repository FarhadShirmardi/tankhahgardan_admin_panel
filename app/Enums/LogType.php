<?php

namespace App\Enums;

use App\Models\PanelUser;

enum LogType: int
{
    case BURN_USER = 1;

    case RESPONSE_FEEDBACK = 2;
    case NEW_COMMENT = 3;
    case EDIT_FEEDBACK = 4;

    case NEW_ANNOUNCEMENT = 5;
    case EDIT_ANNOUNCEMENT = 6;
    case DELETE_ANNOUNCEMENT = 7;

    case NEW_BANNER = 8;
    case EDIT_BANNER = 9;
    case DELETE_BANNER = 10;

    case NEW_PROMO_CODE = 11;
    case EDIT_PROMO_CODE = 12;

    case NEW_AUTOMATION_CALL = 13;
    case EDIT_AUTOMATION_CALL = 14;
    case NEW_AUTOMATION_MISS_CALL = 20;

    case EDIT_WALLET = 15;
    case NEW_INVOICE = 16;
    case PAY_INVOICE = 17;
    case CLOSE_PLAN = 18;
    case DELETE_INVOICE = 19;

    public function description(PanelUser $user): string
    {
        return $user->name . match ($this) {
            self::BURN_USER => ' یک کاربر را به لیست سوخته اضافه کرد.',
            self::RESPONSE_FEEDBACK => ' بازخوردی را پاسخ داد.',
            self::NEW_COMMENT => ' بازخورد جدیدی را ثبت کرد.',
            self::EDIT_FEEDBACK => ' بازخوردی را ویرایش کرد.',
            self::NEW_ANNOUNCEMENT => ' اعلان جدیدی را ثبت کرد.',
            self::EDIT_ANNOUNCEMENT => ' اعلانی را ویرایش کرد.',
            self::DELETE_ANNOUNCEMENT => ' اعلانی را حذف کرد.',
            self::NEW_BANNER => ' بنر جدیدی را ثبت کرد.',
            self::EDIT_BANNER => ' بنری را ویرایش کرد.',
            self::DELETE_BANNER => ' بنری را حذف کرد.',
            self::NEW_PROMO_CODE => ' کد تخفیف جدیدی را ایجاد کرد.',
            self::EDIT_PROMO_CODE => ' کد تخفیفی را ویرایش کرد.',
            self::EDIT_WALLET => ' کیف پولی را تغییر داد.',
            self::NEW_INVOICE => ' یک پیش‌فاکتور جدید ساخته است.',
            self::PAY_INVOICE => ' یک پیش‌فاکتور را پرداخت کرد.',
            self::CLOSE_PLAN => ' یک طرح را اتمام کرد.',
            self::NEW_AUTOMATION_CALL => ' یک تماس جدید ثبت کرده است.',
            self::EDIT_AUTOMATION_CALL => ' یک تماس را ویرایش کرد.',
            self::DELETE_INVOICE => ' یک پیش‌فاکتور را حذف کرد.',
            self::NEW_AUTOMATION_MISS_CALL => ' یک تماس بی پاسخ ثبت کرد.',
        };
    }
}
