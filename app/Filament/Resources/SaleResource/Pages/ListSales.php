<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Enums\PremiumDurationEnum;
use App\Filament\Resources\SaleResource;
use App\Models\UserStatusLog;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ListSales extends ListRecords
{
    protected static string $resource = SaleResource::class;

    protected function getActions(): array
    {
        return [
        ];
    }

    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }
}
