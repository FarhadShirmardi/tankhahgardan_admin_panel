<?php

namespace App\Filament\Resources\PanelUserResource\Pages;

use App\Filament\Resources\PanelUserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPanelUsers extends ListRecords
{
    protected static string $resource = PanelUserResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
