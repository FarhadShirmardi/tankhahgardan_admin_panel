<?php

namespace App\Filament\Resources;

use App\Enums\ActivityTypeEnum;
use App\Enums\UserPremiumStateEnum;
use App\Filament\Components\JalaliDatePicker;
use App\Filament\Resources\UserResource\Pages;
use App\Models\UserReport;
use Ariaieboy\FilamentJalaliDatetime\JalaliDateTimeColumn;
use Exception;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Webbingbrasil\FilamentAdvancedFilter\Filters\TextFilter;

class UserResource extends Resource
{
    protected static ?string $model = UserReport::class;

    public static function getModelLabel(): string
    {
        return __('filament::pages/user.title');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament::pages/user.title');
    }

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make(__('names.table.row index'))
                    ->rowIndex(),
                ColorColumn::make('user_type')
                    ->label('')
                    ->tooltip(fn (UserReport $record) => ActivityTypeEnum::from($record->user_type)->description())
                    ->getStateUsing(fn (UserReport $record) => ActivityTypeEnum::from($record->user_type)->color()),
                TextColumn::make('name')
                    ->label(__('names.full name'))
                    ->copyable(),
                TextColumn::make('phone_number')
                    ->label(__('names.phone number'))
                    ->getStateUsing(fn (UserReport $record) => reformatPhoneNumber($record->phone_number))
                    ->copyable(),
                TextColumn::make('user_state')
                    ->label(__('names.user state'))
                    ->enum(UserPremiumStateEnum::columnValues()),
                JalaliDateTimeColumn::make('registered_at')
                    ->label(__('names.registered at'))
                    ->extraAttributes([
                        'class' => 'ltr-col',
                    ])
                    ->dateTime()
                    ->sortable(),
                JalaliDateTimeColumn::make('max_time')
                    ->label(__('names.last record time'))
                    ->extraAttributes([
                        'class' => 'ltr-col',
                    ])
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('project_count')
                    ->label(__('names.total project count'))
                    ->sortable(),
                TextColumn::make('own_project_count')
                    ->label(__('names.owned project count'))
                    ->sortable(),
                TextColumn::make('not_own_project_count')
                    ->label(__('names.not owned project count'))
                    ->getStateUsing(fn (UserReport $record) => $record->project_count - $record->own_project_count)
                    ->sortable(),
                TextColumn::make('payment_count')
                    ->label(__('names.payment count'))
                    ->sortable(),
                TextColumn::make('receive_count')
                    ->label(__('names.receive count'))
                    ->sortable(),
                TextColumn::make('imprest_count')
                    ->label(__('names.imprest count'))
                    ->sortable(),
                TextColumn::make('image_count')
                    ->label(__('names.image count'))
                    ->sortable(),
                TextColumn::make('image_size')
                    ->label(__('names.image size'))
                    ->sortable(),
            ])
            ->filters(
                [
                    Tables\Filters\SelectFilter::make('user_type')
                        ->label(__('names.last record time state'))
                        ->multiple()
                        ->options(ActivityTypeEnum::columnValues()),
                    Tables\Filters\SelectFilter::make('user_state')
                        ->label(__('names.user state'))
                        ->multiple()
                        ->options(UserPremiumStateEnum::columnValues()),
                    Tables\Filters\Filter::make('phone_number')
                        ->form([
                            TextInput::make('phone_number')->label(__('names.phone number')),
                        ])
                        ->indicateUsing(fn (array $data) => ! $data['phone_number'] ? null : __('names.phone number').': '.$data['phone_number'])
                        ->query(function (Builder $query, array $data): Builder {
                            return $query->when(
                                $data['phone_number'],
                                function (Builder $query, $phoneNumber) {
                                    $phoneNumber = englishString($phoneNumber);
                                    return $query
                                        ->where('phone_number', 'like', "%$phoneNumber%");
                                }
                            );
                        }),
                    TextFilter::make('name')
                        ->label(__('names.full name')),
                    Tables\Filters\Filter::make('registered_at')
                        ->form([
                            JalaliDatePicker::make('registered_from')
                                ->label(__('names.registered from')),
                            JalaliDatePicker::make('registered_until')
                                ->displayFormat('d M Y')
                                ->label(__('names.registered until')),
                        ])
                        ->query(function (Builder $query, array $data): Builder {
                            return $query
                                ->when(
                                    $data['registered_from'],
                                    fn (Builder $query, $date): Builder => $query->whereDate('registered_at', '>=', $date),
                                )
                                ->when(
                                    $data['registered_until'],
                                    fn (Builder $query, $date): Builder => $query->whereDate('registered_at', '<=', $date),
                                );
                        }),
                ],
                layout: Tables\Filters\Layout::AboveContent
            )
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'view' => Pages\ViewUser::route('/{record}'),
        ];
    }
}
