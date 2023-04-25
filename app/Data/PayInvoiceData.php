<?php

namespace App\Data;

use App\Enums\EndPremiumPlanReturnTypeEnum;
use Spatie\LaravelData\Data;

class PayInvoiceData extends Data
{
    public function __construct(
        public string $text
    )
    {
    }
}
