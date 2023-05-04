<?php

namespace App\Filament\Components;

use Ariaieboy\FilamentJalaliDatetime\JalaliDateTimeColumn as BaseJalaliDateTimeColumnAlias;

class JalaliDateTimeColumn extends BaseJalaliDateTimeColumnAlias
{
    protected array $extraAttributes =
        [
            [
                'class' => 'ltr-col',
            ]
        ];
}