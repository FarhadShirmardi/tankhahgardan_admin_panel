<?php

namespace App\Enums;

use App\Traits\HasColumnValues;

enum ActivityTypeEnum: int
{
    use HasColumnValues;

    case ONE_WEEK = 1;
    case TWO_WEEK = 2;
    case ONE_MONTH = 3;
    case DISABLE = 4;

    public function description(): string
    {
        return match ($this) {
            self::ONE_WEEK => __('names.user_activity_type.one_week'),
            self::TWO_WEEK => __('names.user_activity_type.two_week'),
            self::ONE_MONTH => __('names.user_activity_type.one_month'),
            self::DISABLE => __('names.user_activity_type.disabled'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::ONE_WEEK => '#33d48f',
            self::TWO_WEEK => '#4f62d1',
            self::ONE_MONTH => '#f05b4a',
            self::DISABLE => '#F1F3F4',
        };
    }
}
