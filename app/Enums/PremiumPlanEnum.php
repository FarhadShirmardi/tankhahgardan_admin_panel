<?php

namespace App\Enums;

use App\Traits\HasColumnValues;

enum PremiumPlanEnum: int
{
    use HasColumnValues;

    case FREE = 1;
    case BRONZE = 2;
    case SILVER = 3;
    case GOLD = 4;
    case SPECIAL = 5;

    private const UNLIMITED = 1000000;

    public function color(): string
    {
        return match ($this) {
            self::FREE => '#1B73E4',
            self::BRONZE => '#CD7F31',
            self::SILVER => '#BBBDBD',
            self::GOLD => '#FFB300',
            self::SPECIAL => '#007153',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::FREE => __('names.title_plan.free'),
            self::BRONZE => __('names.title_plan.bronze'),
            self::SILVER => __('names.title_plan.silver'),
            self::GOLD => __('names.title_plan.gold'),
            self::SPECIAL => __('names.title_plan.special'),
        };
    }

    public function title(): string
    {
        return 'طرح ' . $this->description();
    }

    public function subTitle(): string
    {
        return match ($this) {
            self::FREE => '',
            self::BRONZE => __('names.sub_title_plan.bronze'),
            self::SILVER => __('names.sub_title_plan.silver'),
            self::GOLD => __('names.sub_title_plan.gold'),
            self::SPECIAL => __('names.sub_title_plan.special'),
        };
    }

    public function features(): array
    {
        return match ($this) {
            self::FREE => [
                '',
                'ثبت حداکثر ۱۰۰ تراکنش',
                'ایجاد حداکثر ۳ گزارش تنخواه',
                'تعریف حداکثر ۲ شرکت',
                'ثبت حداکثر ۱۰۰ پیوست در تراکنش‌ها',
                'ثبت حداکثر ۲ پیوست در هر تراکنش',
                'دریافت ۱ عدد فایل PDF در روز',
            ],
            self::BRONZE => [
                '',
                'ثبت تعداد نامحدود تراکنش',
                'اشتراک‌گذاری با حداکثر ۱ کاربر مهمان',
                'ثبت تعداد نامحدود گزارش تنخواه',
                'امکان ثبت خودکار پیامک‌های بانکی',
                'تعریف حداکثر ۴ شرکت',
                'ثبت حداکثر ۱۰۰۰ پیوست در تراکنش‌ها',
                'ثبت حداکثر ۴ پیوست در هر تراکنش',
                'دریافت تعداد نامحدود فایل PDF در روز',
                'دریافت رسید دریافت و پرداخت',
                'امکان مشاهده گزارش ماهانه',
                'امکان مشاهده گزارش هشتگ‌ها',
                'بارگزاری سرفصل‌های حساب از اکسل',
                'امکان ثبت یادداشت در تقویم',
            ],
            self::SILVER => [
                'تمام ویژگی‌های طرح برنزی به اضافه:',
                'اشتراک‌گذاری با حداکثر ۷ کاربر مهمان',
                'امکان ساخت و مدیریت تیم ‌تنخواه‌داری',
                'امکان اشتراک‌گذاری پنل مدیریت با مدیران',
                'تعریف حداکثر ۱۰ شرکت',
                'ثبت حداکثر ۵۰۰۰ پیوست در تراکنش‌ها',
                'ثبت حداکثر ۸ پیوست در هر تراکنش',
                'امکان مشاهده گزارش طرف حساب‌ها',
                'ثبت بودجه‌بندی ماهانه',
                'دریافت فایل سند حسابداری',
                'دریافت فایل خروجی به صورت اکسل',
                'بارگزاری تراکنش از اکسل',
                'کپی کردن تراکنش‌ها',
                'کپی کردن تراکنش از یک شرکت به شرکت دیگر',
                'امکان ثبت فعالیت و اشتراک‌گذاری آن',
                'امکان ثبت یادآور',
                'ثبت تراکنش به صورت آفلاین',
            ],
            self::GOLD => [
                'تمام ویژگی‌های طرح نقره‌ای به اضافه:',
                'اشتراک‌گذاری با حداکثر ۱۵ کاربر مهمان',
                'امکان تعریف فرآیند تایید گزارش‌های تنخواه',
                'تعریف تعداد نامحدود شرکت',
                'ثبت حداکثر ۲۰۰۰۰ پیوست در تراکنش‌ها',
                'ثبت حداکثر ۱۲ پیوست در هر تراکنش',
                'ثبت بودجه‌بندی برای گزارش تنخواه',
            ],
        };
    }

    public function limits(): array
    {
        return match ($this) {
            self::FREE => self::getFreePlanLimits(),
            self::BRONZE => self::getBronzePlanLimits(),
            self::SILVER => self::getSilverPlanLimits(),
            self::GOLD => self::getGoldPlanLimits(),
            self::SPECIAL => [],
        };
    }

    private static function getFreePlanLimits(): array
    {
        return [
            'transaction_count_limit' => 100,
            'image_count_limit' => 100,
            'project_count_limit' => 2,
            'imprest_count_limit' => 3,
            'transaction_image_count_limit' => 2,
            'user_count_limit' => 0,
            'pdf_count_limit' => 1,
            'monthly_report_limit' => 0,
            'hashtag_report_limit' => 0,
            'read_sms_limit' => 0,
            'account_title_import_limit' => 0,
            'transaction_print_limit' => 0,
            'excel_count_limit' => 0,
            'memo_count_limit' => 0,
            'reminder_count_limit' => 0,
            'task_count_limit' => 0,
            'transaction_duplicate_limit' => 0,
            'contact_report_limit' => 0,
            'offline_transaction_limit' => 0,
            'transaction_import_limit' => 0,
            'accountant_report_limit' => 0,
            'monthly_budget_limit' => 0,
            'transaction_copy_limit' => 0,
            'admin_transaction_limit' => 0,
            'team_limit' => 0,
            'admin_panel_limit' => 0,
            'imprest_budget_limit' => 0,
            'team_level_limit' => 0,
        ];
    }

    private static function getBronzePlanLimits(): array
    {
        return array_merge(
            self::getFreePlanLimits(),
            [
                'transaction_count_limit' => self::UNLIMITED,
                'image_count_limit' => 1000,
                'project_count_limit' => 4,
                'imprest_count_limit' => self::UNLIMITED,
                'transaction_image_count_limit' => 4,
                'user_count_limit' => 1,
                'pdf_count_limit' => self::UNLIMITED,
                'monthly_report_limit' => 1,
                'hashtag_report_limit' => 1,
                'read_sms_limit' => 1,
                'account_title_import_limit' => 1,
                'transaction_print_limit' => 1,
                'excel_count_limit' => 1,
                'memo_count_limit' => 1,
            ]
        );
    }

    private static function getSilverPlanLimits(): array
    {
        return array_merge(
            self::getBronzePlanLimits(),
            [
                'image_count_limit' => 5000,
                'project_count_limit' => 10,
                'transaction_image_count_limit' => 8,
                'user_count_limit' => 7,
                'reminder_count_limit' => 1,
                'task_count_limit' => 1,
                'transaction_duplicate_limit' => 1,
                'contact_report_limit' => 1,
                'offline_transaction_limit' => 1,
                'transaction_import_limit' => 1,
                'accountant_report_limit' => 1,
                'monthly_budget_limit' => 1,
                'transaction_copy_limit' => 1,
                'admin_transaction_limit' => 1,
                'team_limit' => 1,
                'admin_panel_limit' => 1,
            ]
        );
    }

    private static function getGoldPlanLimits(): array
    {
        return array_merge(
            self::getSilverPlanLimits(),
            [
                'image_count_limit' => 20000,
                'project_count_limit' => self::UNLIMITED,
                'transaction_image_count_limit' => 12,
                'user_count_limit' => 15,
                'imprest_budget_limit' => 1,
                'team_level_limit' => 1,
            ]
        );
    }
}
