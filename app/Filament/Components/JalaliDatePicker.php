<?php

namespace App\Filament\Components;

use Ariaieboy\FilamentJalaliDatetimepicker\Forms\Components\JalaliDatePicker as BaseJalaliDatePicker;

class JalaliDatePicker extends BaseJalaliDatePicker
{
    public static function make(string $name): static
    {
        return parent::make($name)
            ->displayFormat('d M Y')
            ->firstDayOfWeek(6)
            ->closeOnDateSelection();
    }
}
