<?php

namespace App\Enums;

use App\Traits\HasColumnValues;

enum PremiumDurationEnum: int
{
    use HasColumnValues;

    case MONTH = 1;
    case HALF_MONTH = 2;
    case ONE_WEEK = 3;

    case YEAR = 4;

    case SPECIAL = 5;

    public function description(): string
    {
        return match ($this) {
            self::MONTH => __('names.premium_duration.one_month'),
            self::HALF_MONTH => __('names.premium_duration.half_month'),
            self::ONE_WEEK => __('names.premium_duration.one_week'),
            self::YEAR => __('names.premium_duration.one_year'),
            self::SPECIAL => __('names.premium_duration.special'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::MONTH => 'primary',
            self::YEAR => 'success',
            self::HALF_MONTH, self::ONE_WEEK => 'secondary',
            self::SPECIAL => 'warning',
        };
    }
}
