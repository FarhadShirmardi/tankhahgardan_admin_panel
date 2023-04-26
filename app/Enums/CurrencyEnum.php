<?php

namespace App\Enums;

enum CurrencyEnum: int
{
    case RIAL = 1;
    case TOMAN = 2;

    public function symbolFa(): string
    {
        return match ($this) {
            self::RIAL => __('names.rial'),
            self::TOMAN => __('names.toman'),
        };
    }

    public function item(): array
    {
        return [
            'id' => $this->value,
            'symbol_fa' => $this->symbolFa(),
        ];
    }

    public static function list(): array
    {
        return array_map(fn (self $enum) => $enum->item(), self::cases());
    }
}
