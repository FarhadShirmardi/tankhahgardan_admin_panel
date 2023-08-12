<?php

namespace App\Enums;

use App\Traits\HasColumnValues;

enum SaleReportTypeEnum: int
{
    use HasColumnValues;

    case BY_DAY = 1;
    case BY_MONTH = 2;
    case BY_YEAR = 3;

    public function description(): string
    {
        return match ($this) {
            self::BY_DAY => __('names.sale_report_type.by_day'),
            self::BY_MONTH => __('names.sale_report_type.by_month'),
            self::BY_YEAR => __('names.sale_report_type.by_year'),
        };
    }
}
