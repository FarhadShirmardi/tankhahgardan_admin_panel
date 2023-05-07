<?php

namespace App\Filament\Resources\CampaignResource\RelationManagers;

use App\Enums\PremiumDurationEnum;
use App\Filament\Components\JalaliDateTimeColumn;
use App\Filament\Components\JalaliDateTimePicker;
use App\Filament\Components\RowIndexColumn;
use App\Filament\Resources\UserResource;
use App\Helpers\UtilHelpers;
use App\Models\PromoCode;
use App\Models\User;
use Closure;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * @property ComponentContainer|View|mixed|null $form
 */
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
            ->schema(self::getFormArray());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::getTableColumnsArray())
            ->headerActions([
                CreateAction::make()
            ]);
    }

    /**
     * @return array
     */
    public static function getFormArray(bool $hasUser = false): array
    {
        return [
            TextInput::make('code')
                ->required()
                ->disabled()
                ->label(__('names.promo_code.code'))
                ->default(fn () => UtilHelpers::generatePromoCode()),

            Select::make('user_id')
                ->label(__('names.username'))
                ->hint(__('message.promo code visible for all users'))
                ->searchable()
                ->reactive()
                ->hidden($hasUser)
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
                ->getOptionLabelUsing(fn ($value): ?string => User::find($value)->username)
                ->afterStateUpdated(function (Closure $set, $state) {
                    if ($state != null) {
                        $set('max_count', 1);
                    }
                }),

            TextInput::make('discount_percent')
                ->label(__('names.promo_code.discount percent'))
                ->hint(__('names.percent'))
                ->minValue(1)
                ->required()
                ->integer(),

            TextInput::make('max_discount')
                ->label(__('names.promo_code.max discount'))
                ->hint(__('names.rial'))
                ->integer(),

            TextInput::make('max_count')
                ->label(__('names.promo_code.max count'))
                ->default(1)
                ->disabled(fn (Closure $get) => !is_null($get('user_id')))
                ->required()
                ->reactive()
                ->hidden($hasUser)
                ->integer(),

            TextInput::make('text')
                ->label(__('names.promo_code.text'))
                ->hint(__('names.promo_code.visible to user'))
                ->required(),

            JalaliDateTimePicker::make('start_at')
                ->label(__('names.start date'))
                ->maxDate(fn (Closure $get) => $get('expire_at'))
                ->required()
                ->default(now()->toDateTimeString()),

            JalaliDateTimePicker::make('expire_at')
                ->label(__('names.expire date')),

            Checkbox::make('is_hidden')
                ->label(__('names.promo_code.is hidden')),

            Checkbox::make('is_unlimited')
                ->label(__('names.promo_code.is unlimited')),

            Select::make('duration_id')
                ->label(__('names.plan type'))
                ->options([
                    null => __('names.promo_code.without plan'),
                    PremiumDurationEnum::MONTH->value => PremiumDurationEnum::MONTH->description(),
                    PremiumDurationEnum::YEAR->value => PremiumDurationEnum::YEAR->description(),
                ]),
        ];
    }

    /**
     * @param bool $hasUser
     * @return array
     */
    public static function getTableColumnsArray(bool $hasUser = false): array
    {
        return [
            RowIndexColumn::make(),

            TextColumn::make('code')
                ->label(__('names.promo_code.code'))
                ->copyable(),

            TextColumn::make('user.username')
                ->label(__('names.username'))
                ->hidden($hasUser)
                ->url(fn (PromoCode $record) => $record->user_id ? UserResource::getUrl('view', ['record' => $record->user_id]) : null, shouldOpenInNewTab: true),

            TextColumn::make('discount_percent')
                ->label(__('names.promo_code.discount percent')),

            TextColumn::make('max_discount')
                ->getStateUsing(fn (PromoCode $record) => is_null($record->max_discount) ? '-' : $record->max_discount)
                ->label(__('names.promo_code.max discount')),

            TextColumn::make('max_count')
                ->hidden($hasUser)
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
        ];
    }
}
