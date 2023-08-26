<?php

namespace App\Enums;

use App\Traits\HasColumnValues;

enum BankEnum: int
{
    use HasColumnValues;

    case ZARIN_PAL = 1;
    case SADAD = 2;
    case BAZAAR = 3;
    case SEPAH = 4;

    public function name(): string
    {
        return match ($this) {
            self::ZARIN_PAL => 'زرین‌پال',
            self::SADAD => 'بانک ملی',
            self::BAZAAR => 'کافه بازار',
            self::SEPAH => 'سپه',
        };
    }

    public function description(): string
    {
        return $this->name();
    }
}
