<?php

namespace App\Filament\Resources;

use App\Enums\PremiumDurationEnum;
use App\Enums\UserStatusTypeEnum;
use App\Filament\Components\JalaliDateTimeColumn;
use App\Filament\Components\RowIndexColumn;
use App\Filament\Resources\TransactionResource\Pages;
use App\Helpers\UtilHelpers;
use App\Models\User;
use App\Models\UserStatus;
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

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationIcon = 'heroicon-o-calculator';

    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::getColumns())
            ->actions(self::getActions())
            ->defaultSort('created_at', 'desc')
            ->bulkActions([
            ])
            ->filters([
                Filter::make('phone_number')
                    ->form([
                        TextInput::make('phone_number')->label(__('names.phone number')),
                    ])
                    ->indicateUsing(fn (array $data) => !$data['phone_number'] ? null : __('names.phone number').': '.$data['phone_number'])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['phone_number'],
                            function (Builder $query, $phoneNumber) {
                                $phoneNumber = formatPhoneNumber(englishString($phoneNumber));
                                return $query->whereIn('user_id', User::query()->where('phone_number', 'like', "%$phoneNumber%")->select('id'));
                            }
                        );
                    })
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

    public static function getColumns(bool $showUsername = true): array
    {
        return [
            RowIndexColumn::make(),
            TextColumn::make('username')
                ->formatStateUsing(fn ($record) => $record->user->username)
                ->hidden(!$showUsername)
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
            TextColumn::make('transaction.trace_no')
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
        ];
    }

    public static function getActions(): array
    {
        return [
            ViewAction::make('view_details')
                ->modalHeading(__('filament::resources/pages/view-record.title', ['label' => __('filament::pages/transaction.single title')]))
                ->modalContent(fn ($record) => \view('filament.resources.transaction-resource.modals.view-transaction', ['record' => $record]))
        ];
    }
}
