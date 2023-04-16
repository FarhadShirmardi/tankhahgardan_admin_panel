<?php

namespace App\Data;

use App\Enums\EndPremiumPlanReturnTypeEnum;
use Spatie\LaravelData\Data;

class EndPremiumPlanData extends Data
{
    public function __construct(
        public EndPremiumPlanReturnTypeEnum $type,
        public string $text
    )
    {
    }
}
