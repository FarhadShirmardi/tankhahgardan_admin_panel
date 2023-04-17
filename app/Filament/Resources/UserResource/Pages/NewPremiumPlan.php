<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Enums\PremiumDurationEnum;
use App\Enums\PremiumPlanEnum;
use App\Filament\Components\JalaliDateTimePicker;
use App\Filament\Resources\UserResource;
use App\Models\PremiumPlan;
use App\Models\User;
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
    public int $userCount;

    protected static string $resource = UserResource::class;

    protected static string $view = 'filament.resources.user-resource.pages.new-premium-plan';

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
                        ->afterStateUpdated(function (Closure $set, $state) {
                            if ($state != PremiumPlanEnum::SPECIAL->value) {
                                $this->form->fill([...$this->getPlanLimits($this->getConsumptionKeys(), PremiumPlanEnum::from($state))]);
                            }
                        }),
                ]),
            Forms\Components\Grid::make(3)
                ->schema([
                    ...collect($this->getConsumptionKeys())
                        ->map(fn ($key) => $this->getConsumptionLimitInputText(str_replace('_count', '', $key)))
                        ->toArray(),
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
        $duration = PremiumDurationEnum::MONTH;
        $startDate = $this->getMinDate();
        $endDate = $this->getEndDate($startDate, $duration);

        $this->form->fill([
            'start_date' => $startDate,
            'end_date' => $endDate,
            'duration_id' => $duration->value,
            ...$this->getPlanLimits($this->getConsumptionKeys(), PremiumPlanEnum::FREE)

        ]);
    }

    private function getPlansSelectOptions(): Collection
    {
        return $this->getPlans()->mapWithKeys(fn (PremiumPlan $plan) => [(string) $plan->id => $plan->type->title()]);
    }

    private function getPlans(): Collection
    {
        return $this->plans ?? PremiumPlan::query()
            ->active()
            ->buyable()
            ->get();
    }

    public function save(): void
    {
        dd($this->form->getState());
    }

    private function getPlanLimits(array $keys, PremiumPlanEnum $planEnum): array
    {
        $limits = $planEnum->limits();

        return collect($keys)->mapWithKeys(fn ($key) => [$key => data_get($limits, $key.'_limit', 1)])->toArray();
    }
}
