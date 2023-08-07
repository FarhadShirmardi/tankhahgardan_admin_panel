<?php

namespace App\Filament\Resources;

use App\Enums\PremiumDurationEnum;
use App\Enums\UserStatusTypeEnum;
use App\Filament\Components\JalaliDateTimeColumn;
use App\Filament\Components\RowIndexColumn;
use App\Filament\Resources\TransactionResource\Pages;
use App\Helpers\UtilHelpers;
use App\Models\User;
use App\Models\UserStatusLog;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class UserStatusLogResource extends Resource
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
                TextColumn::make('username')
                    ->formatStateUsing(fn ($record) => $record->user->username)
                    ->tooltip(fn ($record) => reformatPhoneNumber($record->user->phone_number))
                    ->url(fn ($record) => $record->user_id ? UserResource::getUrl('view', ['record' => $record->user_id]) : null, shouldOpenInNewTab: true)
                    ->label(__('names.full name')),
                JalaliDateTimeColumn::make('created_at')
                    ->label(__('names.payed date'))
                    ->dateTime(),
                ColorColumn::make('premiumPlan.type')
                    ->label(__('names.plan'))
                    ->tooltip(fn (UserStatusLog $record) => $record->premiumPlan?->type->title())
                    ->alignCenter()
                    ->getStateUsing(fn (UserStatusLog $record) => $record->premiumPlan?->type->color()),
                BadgeColumn::make('duration_id')
                    ->label(__('names.plan type'))
                    ->enum(PremiumDurationEnum::columnValues())
                    ->color(static fn ($state) => PremiumDurationEnum::tryFrom($state)?->color()),
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
            ])
            ->actions([])
            ->defaultSort('created_at', 'desc')
            ->bulkActions([
            ])
            ->filters([

            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('user');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
        ];
    }
}
