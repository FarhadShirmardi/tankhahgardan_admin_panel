<?php

namespace App\Filament\Resources\CampaignResource\RelationManagers;

use App\Enums\PremiumDurationEnum;
use App\Filament\Components\JalaliDatePicker;
use App\Filament\Components\JalaliDateTimeColumn;
use App\Filament\Resources\UserResource;
use App\Models\PromoCode;
use App\Models\User;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
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
                    ->label(__('names.promo_code.code'))
                    ->required(),

                Select::make('user_id')
                    ->label(__('names.username'))
                    ->searchable()
                    ->getSearchResultsUsing(function (string $search) {
                        $search = formatPhoneNumber($search);
                        $englishSearch = englishString($search);
                        $persianSearch = persianString($search);

                        return User::query()
                            ->where(fn ($query) => $query
                                ->where('phone_number', 'like', "%$englishSearch%")
                                ->orWhere('phone_number', 'like', "%$persianSearch%")
                                ->orWhere('name', 'like', "%$englishSearch%")
                                ->orWhere('name', 'like', "%$persianSearch%")
                                ->orWhere('family', 'like', "%$englishSearch%")
                                ->orWhere('family', 'like', "%$persianSearch%")
                            )
                            ->limit(50)
                            ->get()
                            ->pluck('username', 'id');
                    })
                    ->getOptionLabelUsing(fn ($value): ?string => User::find($value)->username),

                TextInput::make('discount_percent')
                    ->label(__('names.promo_code.discount percent'))
                    ->hint(__('names.percent'))
                    ->minValue(1)
                    ->integer(),

                TextInput::make('max_discount')
                    ->label(__('names.promo_code.max discount'))
                    ->integer(),

                TextInput::make('max_count')
                    ->default(1)
                    ->required()
                    ->integer(),

                TextInput::make('text')
                    ->label(__('names.promo_code.text'))
                    ->required(),

                JalaliDatePicker::make('start_at')
                    ->label(__('names.start date')),

                JalaliDatePicker::make('expire_at')
                    ->label(__('names.expire date')),

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
                    ->label(__('names.promo_code.code'))
                    ->copyable(),

                TextColumn::make('user.username')
                    ->label(__('names.username'))
                    ->url(fn (PromoCode $record) => UserResource::getUrl('view', ['record' => $record->user_id]), shouldOpenInNewTab: true),

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

                IconColumn::make('is_hidden')
                    ->label(__('names.promo_code.is hidden'))
                    ->boolean(),

                IconColumn::make('is_unlimited')
                    ->label(__('names.promo_code.is unlimited'))
                    ->boolean(),

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
