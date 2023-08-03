<?php

namespace App\Filament\Components;

use Ariaieboy\FilamentJalaliDatetimepicker\Forms\Components\JalaliDatePicker as BaseJalaliDatePicker;

class JalaliDateTimePicker extends BaseJalaliDatePicker
{
    public static function make(string $name): static
    {
        return parent::make($name)
            ->displayFormat('j F Y H:i:s')
            ->firstDayOfWeek(6)
            ->closeOnDateSelection();
    }
}
