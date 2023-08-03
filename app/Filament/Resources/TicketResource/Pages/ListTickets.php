<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use Filament\Resources\Pages\ListRecords;

class ListTickets extends ListRecords
{
    protected static string $resource = TicketResource::class;

    protected function getTableFiltersFormColumns(): int
    {
        return 3;
    }

    protected function getTableBulkActions(): array
    {
        return [];
    }

    protected function getActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }
}
