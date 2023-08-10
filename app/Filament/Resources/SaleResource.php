<?php

namespace App\Filament\Resources;

use App\Enums\PremiumDurationEnum;
use App\Enums\SaleReportTypeEnum;
use App\Enums\UserStatusTypeEnum;
use App\Filament\Components\JalaliDateTimeColumn;
use App\Filament\Components\RowIndexColumn;
use App\Filament\Resources\TransactionResource\Pages;
use App\Helpers\UtilHelpers;
use App\Models\UserStatusLog;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class SaleResource extends Resource
{
    public static function getModelLabel(): string
    {
        return __('filament::pages/sale.single title');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament::pages/sale.title');
    }

    protected static ?int $navigationSort = 6;

    protected static ?string $navigationIcon = 'healthicons-o-money-bag';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                RowIndexColumn::make(),
                JalaliDateTimeColumn::make('created_at')
                    ->label(__('names.date_time'))
                    ->dateTime(),
                TextColumn::make('payable_amount')
                    ->formatStateUsing(fn ($record) => formatPrice(UtilHelpers::getPayableAmount($record->total_amount, $record->added_value_amount, $record->discount_amount, $record->wallet_amount, $record->credit_amount)))
                    ->label(__('names.payable amount')),
            ])
            ->actions([])
            ->defaultSort('created_at', 'desc')
            ->bulkActions([
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options(SaleReportTypeEnum::columnValues())
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['value'])) {
                            return match($data['value']) {
                                SaleReportTypeEnum::BY_DAY->value => $query->groupBy('created_at')
                            };
                        }

                        return $query;
                    })
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return UserStatusLog::query()
            ->with('user')
            ->where(fn (Builder $query) => $query->where('duration_id', '!=', PremiumDurationEnum::HALF_MONTH->value)->orWhere('price_id', '!=', PremiumDurationEnum::HALF_MONTH->value))
            ->select([
                'id',
                'created_at',
                DB::raw("SUM(total_amount + added_value_amount - wallet_amount - credit_amount - discount_amount) as total_sum")
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSales::route('/'),
        ];
    }
}
