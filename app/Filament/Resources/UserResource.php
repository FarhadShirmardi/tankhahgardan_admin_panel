<?php

namespace App\Filament\Resources;

use App\Enums\UserPremiumStateEnum;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\UserReport;
use Ariaieboy\FilamentJalaliDatetime\JalaliDateTimeColumn;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class UserResource extends Resource
{
    protected static ?string $model = UserReport::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make(__('names.table.row index'))
                    ->rowIndex(),
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
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'view' => Pages\ViewUser::route('/{record}/view'),
        ];
    }
}
