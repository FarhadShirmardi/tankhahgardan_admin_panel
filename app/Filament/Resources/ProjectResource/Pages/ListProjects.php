<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Resources\Pages\ListRecords;

class ListProjects extends ListRecords
{
    protected static string $resource = ProjectResource::class;

    protected int $defaultTableRecordsPerPageSelectOption = 50;

    protected function getActions(): array
    {
        return [
        ];
    }
}
