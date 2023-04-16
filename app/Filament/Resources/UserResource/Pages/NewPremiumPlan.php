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
use Filament\Tables\Columns\TextColumn;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Yepsua\Filament\Forms\Components\RangeSlider;

/**
 * @property Forms\ComponentContainer|View|mixed|null $form
 */
class NewPremiumPlan extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    public User $user;
    public Collection $plans;

    protected static string $resource = UserResource::class;

    protected static string $view = 'filament.resources.user-resource.pages.new-premium-plan';

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
                        ->options([
                            ...$this->getPlansSelectOptions(),
                            null => __('names.title_plan.special')
                        ]),
                ]),
            Forms\Components\Grid::make(3)
                ->schema([
                    Forms\Components\TextInput::make('user_count')
                        ->prefixIcon('lucide-infinity')
                        ->label('تعداد کاربر')
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
            'duration_id' => $duration->value
        ]);
    }

    private function getPlansSelectOptions(): Collection
    {
        return $this->getPlans()->mapWithKeys(fn (PremiumPlan $plan) => [$plan->id => $plan->type->title()]);
    }

    private function getPlans(): Collection
    {
        return $this->plans ?? PremiumPlan::query()
            ->active()
            ->buyable()
            ->get();
    }
}
