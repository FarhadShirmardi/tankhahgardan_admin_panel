<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Enums\PremiumDurationEnum;
use App\Enums\SaleReportTypeEnum;
use App\Filament\Resources\SaleResource;
use App\Models\UserStatusLog;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Filters\SelectFilter;
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

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->with('user')
            ->where(fn (Builder $query) => $query->where('duration_id', '!=', PremiumDurationEnum::HALF_MONTH->value)->orWhere('price_id', '!=', PremiumDurationEnum::HALF_MONTH->value))
            ->select([
                'id',
                DB::raw('date(created_at) as date'),
                DB::raw("SUM(total_amount + added_value_amount - wallet_amount - credit_amount - discount_amount) as total_sum")
            ]);
    }

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('type')
                ->options(SaleReportTypeEnum::columnValues())
                ->default(SaleReportTypeEnum::BY_DAY->value)
                ->query(function (Builder $query, array $data) {
                    if (!empty($data['value'])) {
                        return match ((int) $data['value']) {
                            SaleReportTypeEnum::BY_DAY->value => $query->groupBy('date')
                        };
                    }

                    return $query;
                })
        ];
    }

    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }
}
