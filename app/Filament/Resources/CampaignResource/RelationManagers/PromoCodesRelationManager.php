<?php

namespace App\Filament\Resources\CampaignResource\RelationManagers;

use App\Enums\PremiumDurationEnum;
use App\Filament\Components\JalaliDatePicker;
use App\Filament\Components\JalaliDateTimeColumn;
use App\Filament\Resources\UserResource;
use App\Models\PromoCode;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;

class PromoCodesRelationManager extends RelationManager
{
    protected static string $relationship = 'promoCodes';

    public static function getModelLabel(): string
    {
        return __('filament::pages/promoCode.title');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament::pages/promoCode.title_plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('code')
                    ->required(),

                TextInput::make('user_id')
                    ->integer(),

                TextInput::make('discount_percent')
                    ->integer(),

                TextInput::make('max_discount')
                    ->integer(),

                TextInput::make('max_count')
                    ->required()
                    ->integer(),

                JalaliDatePicker::make('start_at')
                    ->label(__('names.start date')),

                JalaliDatePicker::make('expire_at')
                    ->label(__('names.expire date')),

                TextInput::make('text'),

                TextInput::make('reserve_count')
                    ->required()
                    ->integer(),

                TextInput::make('panel_user_id')
                    ->integer(),



                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn (?PromoCode $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn (?PromoCode $record): string => $record?->updated_at?->diffForHumans() ?? '-'),

                Checkbox::make('is_hidden'),

                Checkbox::make('is_unlimited'),

                TextInput::make('duration_id'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make(__('names.table.row index'))
                    ->rowIndex(),

                TextColumn::make('code')
                    ->copyable(),

                TextColumn::make('user.username')
                    ->label(__('names.username'))
                    ->url(fn(PromoCode $record) => UserResource::getUrl('view', ['record' => $record->user_id]), shouldOpenInNewTab: true),

                TextColumn::make('discount_percent')
                    ->label(__('names.promo_code.discount percent')),

                TextColumn::make('max_discount')
                    ->getStateUsing(fn (PromoCode $record) => is_null($record->max_discount) ? '-' : $record->max_discount)
                    ->label(__('names.promo_code.max discount')),

                TextColumn::make('max_count')
                    ->label(__('names.promo_code.max count')),

                JalaliDateTimeColumn::make('start_at')
                    ->label(__('names.start date'))
                    ->dateTime(),

                JalaliDateTimeColumn::make('expire_at')
                    ->label(__('names.expire date'))
                    ->dateTime(),

                TextColumn::make('reserve_count')
                    ->label(__('names.promo_code.reserved count')),

                IconColumn::make('is_hidden')->boolean(),

                IconColumn::make('is_unlimited')->boolean(),

                BadgeColumn::make('duration_id')
                    ->label(__('names.plan type'))
                    ->enum(PremiumDurationEnum::columnValues())
                    ->color(static fn ($state) => PremiumDurationEnum::tryFrom($state)?->color()),
            ])
            ->headerActions([
                CreateAction::make()
            ]);
    }
}
