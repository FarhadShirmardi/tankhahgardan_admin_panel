<?php

namespace App\Traits;

use Derakht\Jalali\Jalali;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait StartEndDateCaster
{
    public function startDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Jalali::parse($value)->toJalaliDateString() : null,
            set: fn ($value) => $value ? Jalali::parseJalali($value)->toDateString() : null
        );
    }

    public function endDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Jalali::parse($value)->toJalaliDateString() : null,
            set: fn ($value) => $value ? Jalali::parseJalali($value)->toDateString() : null
        );
    }
}
