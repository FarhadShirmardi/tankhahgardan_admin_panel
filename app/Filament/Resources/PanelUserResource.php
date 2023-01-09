<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PanelUserResource\Pages;
use App\Filament\Resources\PanelUserResource\RelationManagers;
use App\Models\PanelUser;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Resources\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Hash;
use Illuminate\Database\Eloquent\Builder;

class PanelUserResource extends Resource
{
    protected static ?string $model = PanelUser::class;

    public static function getModelLabel(): string
    {
        return __('filament::pages/panel-user.title');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament::pages/panel-user.title_plural');
    }

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    TextInput::make('name')
                        ->label(__('names.full name'))
                        ->string()
                        ->required(),
                    TextInput::make('phone_number')
                        ->label(__('filament::login.fields.phone_number.label'))
                        ->required(),
                    TextInput::make('password')
                        ->label(__('filament::login.fields.password.label'))
                        ->password()
                        ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (Page $livewire) => ($livewire instanceof CreateRecord)),
                ])->columns(),
                Card::make([
                    CheckboxList::make('permissions.name')
                        ->label(__('names.permissions'))
                        ->columns(3)
                        ->relationship('permissions', 'title'),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make(__('names.table.row index'))
                    ->rowIndex(),
                TextColumn::make('name')
                    ->sortable()
                    ->label(__('names.full name')),
                TextColumn::make('phone_number')
                    ->sortable()
                    ->label(__('names.phone number')),
                TextColumn::make('permissions.title')
                    ->label(__('names.permissions'))
                    ->words(10),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('id', '<>', auth()->id());
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
            'index' => Pages\ListPanelUsers::route('/'),
            'create' => Pages\CreatePanelUser::route('/create'),
            'edit' => Pages\EditPanelUser::route('/{record}/edit'),
        ];
    }
}
