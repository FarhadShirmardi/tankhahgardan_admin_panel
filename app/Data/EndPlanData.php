<?php

namespace App\Data;

use App\Enums\EndPlanReturnTypeEnum;
use Spatie\LaravelData\Data;

class EndPlanData extends Data
{
    public function __construct(
        public EndPlanReturnTypeEnum $type,
        public string $text
    )
    {
    }
}
