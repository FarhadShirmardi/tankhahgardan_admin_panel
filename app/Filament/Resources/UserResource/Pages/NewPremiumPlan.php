<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Constants\PremiumConstants;
use App\Enums\PremiumDurationEnum;
use App\Enums\PremiumPlanEnum;
use App\Filament\Components\JalaliDateTimePicker;
use App\Filament\Resources\UserResource;
use App\Helpers\UtilHelpers;
use App\Models\PremiumPlan;
use App\Models\PromoCode;
use App\Models\User;
use App\Services\PromoCodeService;
use Carbon\Carbon;
use Closure;
use Filament\Forms;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

/**
 * @property Forms\ComponentContainer|View|mixed|null $form
 */
class NewPremiumPlan extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    public User $user;
    public Collection $plans;
    public Collection $promoCodes;
    public int $walletAmount = 0;
    public int $userCount;

    protected static string $resource = UserResource::class;

    protected static string $view = 'filament.resources.user-resource.pages.new-premium-plan';

    public function getAddedValueAmount(): int
    {
        $totalAmount = $this->form->getComponent('total_amount')->getState();
        if (!$totalAmount) {
            return 0;
        }
        $discountAmount = $this->getDiscountAmount($totalAmount);
        $payableAmount = UtilHelpers::getPayableAmount(totalAmount: $totalAmount, discountAmount: $discountAmount);
        $walletAmount = $this->form->getComponent('use_wallet')->getState() ? min($this->walletAmount, $payableAmount) : 0;
        return (int) round(($payableAmount - $walletAmount) * PremiumConstants::ADDED_VALUE_PERCENT);
    }

    public function getUseWalletAmount(): int
    {
        $useWallet = $this->form->getComponent('use_wallet')->getState();
        if (!$useWallet) {
            return 0;
        }
        $totalAmount = $this->form->getComponent('total_amount')->getState();
        if (!$totalAmount) {
            return 0;
        }
        $discountAmount = $this->getDiscountAmount($totalAmount);
        $payableAmount = UtilHelpers::getPayableAmount(totalAmount: $totalAmount, discountAmount: $discountAmount);
        return min($this->walletAmount, $payableAmount);
    }

    public function getDiscountAmount($totalAmount): int|float
    {
        $promoCodeId = $this->form->getComponent('promo_code_id')->getState();
        return $promoCodeId ?
            PromoCodeService::getDiscountAmount($totalAmount, $this->getPromoCodes()->firstWhere('id', $promoCodeId)) :
            0;
    }

    /**
     * @return string[]
     */
    public function getConsumptionKeys(): array
    {
        return [
            'transaction_count',
            'image_count',
            'project_count',
            'imprest_count',
            'user_count',
            'transaction_image_count'
        ];
    }

    public function getConsumptionLimitInputText(string $field): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make("{$field}_count")
            ->numeric()
            ->reactive()
            ->suffixAction(
                fn (Closure $get, string $state) => ($get('premium_plan_id') != PremiumPlanEnum::SPECIAL->value or $state == PremiumPlanEnum::UNLIMITED or $field == 'transaction_image') ?
                    null :
                    Forms\Components\Actions\Action::make("{$field}_count_infinity")
                        ->action(fn () => $this->form->fill([
                            "{$field}_count" => PremiumPlanEnum::UNLIMITED
                        ]))
                        ->icon('lucide-infinity')
                        ->label(__('names.infinity'))
            )
            ->required()
            ->disabled(fn (Closure $get) => $get('premium_plan_id') != PremiumPlanEnum::SPECIAL->value)
            ->minValue(1)
            ->maxValue(fn () => $field == 'transaction_image' ? 50 : PremiumPlanEnum::UNLIMITED)
            ->hint(fn (string $state): ?string => $state == PremiumPlanEnum::UNLIMITED ? __('names.unlimited') : null)
            ->label(__("names.consumption.".str_replace('_', ' ', $field)." count"));
    }

    protected function getTitle(): string
    {
        return __('names.create new plan').' '.$this->user->formatted_username;
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Grid::make(3)
                ->schema([
                    Forms\Components\Select::make('duration_id')
                        ->label(__('names.premium_duration.title'))
                        ->options([
                            PremiumDurationEnum::MONTH->value => PremiumDurationEnum::MONTH->description(),
                            PremiumDurationEnum::YEAR->value => PremiumDurationEnum::YEAR->description(),
                            PremiumDurationEnum::SPECIAL->value => PremiumDurationEnum::SPECIAL->description(),
                        ])
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function (Closure $set, Closure $get, $state) {
                            $set('end_date', $this->getEndDate($get('start_date'), PremiumDurationEnum::from($state)));
                        }),
                    JalaliDateTimePicker::make('start_date')
                        ->label(__('names.start date'))
                        ->maxDate(fn (Closure $get) => $get('end_date'))
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function (Closure $set, Closure $get, $state) {
                            $set('end_date', $this->getEndDate($state, PremiumDurationEnum::from($get('duration_id'))));
                        }),
                    JalaliDateTimePicker::make('end_date')
                        ->label(__('names.end date'))
                        ->minDate(fn (Closure $get) => $get('start_date'))
                        ->required()
                        ->reactive()
                        ->disabled(fn (Closure $get) => $get('duration_id') != PremiumDurationEnum::SPECIAL->value),
                ]),
            Forms\Components\Grid::make(3)
                ->schema([
                    Forms\Components\Select::make('premium_plan_id')
                        ->label(__('names.plan'))
                        ->reactive()
                        ->required()
                        ->options($this->getPlansSelectOptions()->put(PremiumPlanEnum::SPECIAL->value, __('names.title_plan.special')))
                        ->afterStateUpdated(function (Closure $set, $state, Closure $get) {
                            if ($state != PremiumPlanEnum::SPECIAL->value) {
                                /** @var PremiumPlan $plan */
                                $plan = $this->getPlans()->firstWhere('id', $state);
                                $durationPrice = PremiumDurationEnum::getItem($get('duration_id'), $plan->price, $plan->yearly_discount);
                                $this->form->fill([...$this->getPlanLimits($this->getConsumptionKeys(), $plan->limits), 'total_amount' => $durationPrice->price]);
                            }
                        }),
                ]),
            Forms\Components\Grid::make(3)
                ->schema([
                    ...collect($this->getConsumptionKeys())
                        ->map(fn ($key) => $this->getConsumptionLimitInputText(str_replace('_count', '', $key)))
                        ->toArray(),
                ]),
            Forms\Components\Grid::make(3)
                ->schema([
                    Forms\Components\TextInput::make('total_amount')
                        ->label(__('names.total amount'))
                        ->numeric()
                        ->hint(__('names.rial'))
                        ->required()
                        ->reactive(),
                    Forms\Components\Select::make('promo_code_id')
                        ->label(__('names.promo code'))
                        ->reactive()
                        ->options($this->getPromoCodeOptions()),
                    Forms\Components\Toggle::make('use_wallet')
                        ->inline(false)
                        ->reactive()
                        ->label(fn () => "استفاده از کیف پول (".formatPrice($this->walletAmount)." ریال)"),
                ]),
        ];
    }

    private function getMinDate(): ?string
    {
        return max(now()->toDateTimeString(), $this->user->userStatuses()->max('end_date'));
    }

    private function getEndDate(string $startDate, PremiumDurationEnum $duration): string
    {
        return Carbon::parse($startDate)
            ->addDays($duration->getDayCount())
            ->endOfDay()
            ->toDateTimeString();
    }

    public function mount(int $record): void
    {
        $this->user = User::findOrFail($record);
        $this->walletAmount = $this->user->wallet_amount;
        $duration = PremiumDurationEnum::MONTH;
        $startDate = $this->getMinDate();
        $endDate = $this->getEndDate($startDate, $duration);

        $this->form->fill([
            'start_date' => $startDate,
            'end_date' => $endDate,
            'duration_id' => $duration->value,
            ...$this->getPlanLimits($this->getConsumptionKeys(), PremiumPlanEnum::FREE->limits())

        ]);
    }

    private function getPlansSelectOptions(): Collection
    {
        return $this->getPlans()->mapWithKeys(fn (PremiumPlan $plan) => [(string) $plan->id => $plan->type->title()]);
    }

    private function getPlans(): Collection
    {
        $this->plans = $this->plans ?? PremiumPlan::query()
            ->active()
            ->buyable()
            ->get();
        return $this->plans;
    }

    public function save(): void
    {
        dd($this->form->getState());
    }

    private function getPlanLimits(array $keys, array $limits): array
    {
        return collect($keys)->mapWithKeys(fn ($key) => [$key => data_get($limits, $key.'_limit', 1)])->toArray();
    }

    private function getPromoCodes(): Collection
    {
        $this->promoCodes = $this->promoCodes ?? PromoCodeService::promoCodesQuery($this->user)->get();
        return $this->promoCodes;
    }

    private function getPromoCodeOptions(): Collection
    {
        return $this->getPromoCodes()->mapWithKeys(fn (PromoCode $code) => [$code->id => $this->getPromoCodeText($code)]);
    }

    private function getPromoCodeText(PromoCode $promoCode): string
    {
        $text = $promoCode->text;
        if ($promoCode->discount_percent > 0) {
            $text .= " - ".$promoCode->discount_percent." ".__('names.percent')." ".__('names.discount');
        }
        if ($promoCode->max_discount > 0) {
            $text .= " ".__('names.up_to')." ".formatPrice($promoCode->max_discount / 10)." ".__('names.toman');
        }
        $text .= " ($promoCode->code)";
        return $text;
    }

    public function getPlanText(): string
    {
        /** @var PremiumPlan $plan */
        $planId = $this->form->getComponent('premium_plan_id')->getState();
        $durationId = $this->form->getComponent('duration_id')->getState();
        $duration = PremiumDurationEnum::from($durationId);
        if (!$planId or !$durationId) {
            return __('names.undefined plan');
        }

        return PremiumPlanEnum::from($planId)->title() . ' - ' . $duration->getTitle();
    }
}
