<?php

namespace App\Enums;

enum PermissionEnum: string
{
    case VIEW_TICKET = 'view_ticket';
    case RESPONSE_TICKET = 'response_ticket';

    case EDIT_USER_PANELS = 'edit_user_panels';

    case USER_REPORT = 'user_reports';
    case PROJECT_REPORT = 'project_reports';

    case PREMIUM_PLAN = 'premium_plan';

    public function getTitle(): string
    {
        return match ($this) {
            self::VIEW_TICKET => 'نمایش تیکت',
            self::RESPONSE_TICKET => 'پاسخ دادن به تیکت',
            self::EDIT_USER_PANELS => 'ویرایش کاربران پنل',
            self::USER_REPORT => 'گزارش کاربران',
            self::PROJECT_REPORT => 'گزارش پروژه',
            self::PREMIUM_PLAN => 'ایجاد طرح برای کاربران',
        };
    }
}
