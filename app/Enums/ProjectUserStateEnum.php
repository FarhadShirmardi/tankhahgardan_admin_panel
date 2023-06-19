<?php

namespace App\Enums;

use App\Traits\HasColumnValues;

enum ProjectUserStateEnum: int
{
    use HasColumnValues;
    case ACTIVE = 1;
    case PENDING = 2;
    case INACTIVE = 40;

    public function description(): string
    {
        return match ($this) {
            self::ACTIVE => __('names.project_user_state.active'),
            self::PENDING => __('names.project_user_state.pending'),
            self::INACTIVE => __('names.project_user_state.inactive')
        };
    }
}
