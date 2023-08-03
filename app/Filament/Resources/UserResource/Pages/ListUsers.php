<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected int $defaultTableRecordsPerPageSelectOption = 50;

    protected function getActions(): array
    {
        return [
        ];
    }
}
