<?php

namespace App\Enums;

use App\Traits\HasColumnValues;

enum UserPremiumStateEnum: int
{
    use HasColumnValues;

    case FREE = 1;
    case PREMIUM = 2;
    case EXPIRED_PREMIUM = 3;
    case NEAR_ENDING_PREMIUM = 4;

    public function description(): string
    {
        return match ($this) {
            self::FREE => __('names.premium_state.free'),
            self::PREMIUM => __('names.premium_state.premium'),
            self::EXPIRED_PREMIUM => __('names.premium_state.expired_premium'),
            self::NEAR_ENDING_PREMIUM => __('names.premium_state.near_ending_premium'),
        };
    }
}
