<?php

namespace App\Traits;

trait HasColumnValues
{
    public static function columnValues(): array
    {
        return collect(self::cases())
            ->mapWithKeys(function (self $enum) {
                return [$enum->value => $enum->description()];
            })->toArray();
    }
}
