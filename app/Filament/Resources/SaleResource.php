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
    protected static ?string $model = UserStatusLog::class;

    protected static ?string $slug = 'sales-report';

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
                JalaliDateTimeColumn::make('date')
                    ->label(__('names.date_time'))
                    ->dateTime(),
                TextColumn::make('total_sum')
                    ->formatStateUsing(fn ($record) => formatPrice($record->total_sum))
                    ->label(__('names.total amount')),
            ])
            ->actions([])
            ->defaultSort('created_at', 'desc')
            ->bulkActions([
            ])
            ->filters([

            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSales::route('/'),
        ];
    }
}
