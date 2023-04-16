<?php

namespace App\Enums;

use App\Traits\HasColumnValues;

enum EndPremiumPlanReturnTypeEnum: int
{
    use HasColumnValues;
    case WALLET = 1;
    case CARD = 2;

    public function description(): string
    {
        return match ($this) {
            self::WALLET => __('names.return money type.wallet'),
            self::CARD => __('names.return money type.card'),
        };
    }
}
