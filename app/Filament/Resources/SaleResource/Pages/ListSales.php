<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\SaleResource;
use Filament\Resources\Pages\ListRecords;

class ListSales extends ListRecords
{
    protected static string $resource = SaleResource::class;

    protected function getActions(): array
    {
        return [
        ];
    }
}
