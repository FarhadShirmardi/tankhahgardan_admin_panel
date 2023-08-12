<?php

namespace App\Enums;

use App\Traits\HasColumnValues;

enum UserStatusTypeEnum: int
{
    use HasColumnValues;

    case FAILED = 0;
    case SUCCEED = 1;
    case PENDING = 2;

    public function description(): string
    {
        return match ($this) {
            self::FAILED => __('names.user status state.failed'),
            self::SUCCEED => __('names.user status state.success'),
            self::PENDING => __('names.user status state.pending'),
        };
    }
}
