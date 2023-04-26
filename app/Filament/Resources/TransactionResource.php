<?php

namespace App\Filament\Resources;

use App\Enums\UserStatusTypeEnum;
use App\Filament\Resources\TransactionResource\Pages;
use App\Helpers\UtilHelpers;
use App\Models\UserStatusLog;
use Ariaieboy\FilamentJalaliDatetime\JalaliDateTimeColumn;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;

class TransactionResource extends Resource
{
    protected static ?string $model = UserStatusLog::class;

    public static function getModelLabel(): string
    {
        return __('filament::pages/transaction.title');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament::pages/transaction.title');
    }

    protected static ?string $navigationIcon = 'heroicon-o-calculator';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make(__('names.table.row index'))
                    ->rowIndex(),
                TextColumn::make('username')
                    ->formatStateUsing(fn ($record) => $record->user->username)
                    ->tooltip(fn ($record) => reformatPhoneNumber($record->user->phone_number))
                    ->label(__('names.full name')),
                TextColumn::make('trace_no')
                    ->label(__('names.bank transaction number')),
                TextColumn::make('trace_number')
                    ->label(__('names.tankhah transaction number')),
                TextColumn::make('payable_amount')
                    ->formatStateUsing(fn ($record) => formatPrice(UtilHelpers::getPayableAmount($record->total_amount, $record->added_value_amount, $record->discount_amount, $record->wallet_amount, $record->credit_amount)))
                    ->label(__('names.payable amount')),
                IconColumn::make('status')
                    ->options([
                        'heroicon-o-x-circle',
                        'heroicon-o-clock' => UserStatusTypeEnum::PENDING->value,
                        'heroicon-o-check-circle' => UserStatusTypeEnum::SUCCEED->value
                    ])
                    ->colors([
                        'danger' => UserStatusTypeEnum::FAILED->value,
                        'warning' => UserStatusTypeEnum::PENDING->value,
                        'success' => UserStatusTypeEnum::SUCCEED->value,
                    ])
                    ->label(__('names.state')),
                JalaliDateTimeColumn::make('start_date')
                    ->label(__('names.start date').' '.__('names.plan'))
                    ->extraAttributes([
                        'class' => 'ltr-col',
                    ])
                    ->dateTime()
                    ->sortable(),
                JalaliDateTimeColumn::make('end_date')
                    ->label(__('names.end date').' '.__('names.plan'))
                    ->extraAttributes([
                        'class' => 'ltr-col',
                    ])
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
            ])
            ->bulkActions([
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
        ];
    }
}
