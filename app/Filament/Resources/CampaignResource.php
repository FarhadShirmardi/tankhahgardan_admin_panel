<?php

namespace App\Filament\Resources;

use App\Filament\Components\JalaliDatePicker;
use App\Filament\Components\JalaliDateTimeColumn;
use App\Filament\Components\RowIndexColumn;
use App\Filament\Resources\CampaignResource\Pages;
use App\Filament\Resources\CampaignResource\RelationManagers\PromoCodesRelationManager;
use App\Models\Campaign;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;

class CampaignResource extends Resource
{
    protected static ?string $model = Campaign::class;

    public static function getModelLabel(): string
    {
        return __('filament::pages/campaign.title');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament::pages/campaign.title_plural');
    }

    protected static ?string $navigationIcon = 'carbon-promote';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(2)
            ->schema([
                TextInput::make('name')
                    ->label(__('names.campaign.name'))
                    ->required(),

                TextInput::make('count')
                    ->label(__('names.campaign.count'))
                    ->required()
                    ->integer(),

                JalaliDatePicker::make('start_date')
                    ->label(__('names.start date')),

                JalaliDatePicker::make('end_date')
                    ->label(__('names.end date')),

                TextInput::make('symbol')
                    ->label(__('names.campaign.symbol'))
                    ->disabledOn('edit')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                RowIndexColumn::make(),

                TextColumn::make('name')
                    ->label(__('names.campaign.name'))
                    ->searchable()
                    ->sortable(),

                JalaliDateTimeColumn::make('start_date')
                    ->label(__('names.start date'))
                    ->date(),

                JalaliDateTimeColumn::make('end_date')
                    ->label(__('names.end date'))
                    ->date(),

                TextColumn::make('symbol')
                    ->label(__('names.campaign.symbol')),

                TextColumn::make('count')
                    ->label(__('names.campaign.count')),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PromoCodesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCampaigns::route('/'),
            'create' => Pages\CreateCampaign::route('/create'),
            'edit' => Pages\EditCampaign::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }
}
