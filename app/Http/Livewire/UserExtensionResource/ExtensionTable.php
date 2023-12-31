<?php

namespace App\Http\Livewire\UserExtensionResource;

use App\Enums\PremiumDurationEnum;
use App\Enums\PremiumPlanEnum;
use App\Enums\ProjectUserTypeEnum;
use App\Filament\Components\JalaliDatePicker;
use App\Filament\Components\JalaliDateTimeColumn;
use App\Filament\Components\RowIndexColumn;
use App\Filament\Resources\UserResource;
use App\Models\UserStatus;
use App\Models\UserStatusLog;
use Closure;
use DB;
use Derakht\Jalali\Jalali;
use Exception;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class ExtensionTable extends Component implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected $queryString = [
        'tableFilters',
        'tableSortColumn' => ['except' => ''],
        'tableSortDirection' => ['except' => ''],
    ];

    public bool $isLoaded = false;

    public function loadData(): void
    {
        $this->isLoaded = true;
    }

    protected function getTableEmptyStateIcon(): ?string
    {
        return $this->isLoaded ? null : 'heroicon-o-download';
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return $this->isLoaded ? null : __('message.loading_data');
    }

    protected function getTableQuery()
    {
        if (!$this->isLoaded) {
            return UserStatus::query()->whereRaw('false');
        }

        $maxTimeQuery = DB::connection('mysql')->table('panel_project_reports')
            ->join('project_user', 'project_user.project_id', 'panel_project_reports.id')
            ->where('project_user.user_type', ProjectUserTypeEnum::OWNER->value)
            ->whereColumn('project_user.user_id', 'user_statuses.user_id')
            ->selectRaw('max(max_time)');

        return UserStatus::query()
            ->join('panel_user_reports', 'panel_user_reports.id', 'user_statuses.user_id')
            ->orderBy('date_diff')
            ->selectSub($maxTimeQuery, 'max_time')
            ->addSelect([
                DB::raw("exists(select * from user_statuses ou where ou.user_id = user_statuses.user_id and ou.end_date > user_statuses.end_date) as has_extended"),
                DB::raw("datediff(end_date, now()) as date_diff"),
                'panel_user_reports.id',
                'panel_user_reports.name',
                'panel_user_reports.phone_number',
                'end_date',
                'premium_plan_id',
                'duration_id'
            ]);
    }

    protected function getTableColumns(): array
    {
        return [
            RowIndexColumn::make(),
            Tables\Columns\ColorColumn::make('color')
                ->label('')
                ->tooltip(fn ($record) => $record->has_extended? 'تمدید کرده است' : ($record->date_diff < 0 ? 'تمدید نکرده است' : ''))
                ->getStateUsing(fn ($record) => $record->has_extended? '#33d48f' : ($record->date_diff < 0 ? '#F1F3F4' : '')),
            TextColumn::make('name')
                ->label(__('names.full name'))
                ->getStateUsing(fn ($record) => filled($record->name) ? $record->name : '-')
                ->words(4),
            TextColumn::make('phone_number')
                ->label(__('names.phone number'))
                ->getStateUsing(fn ($record) => reformatPhoneNumber($record->phone_number)),
            JalaliDateTimeColumn::make('max_time')
                ->label(__('names.last record time'))
                ->sortable()
                ->dateTime(),
            ColorColumn::make('premiumPlan.type')
                ->label(__('names.plan'))
                ->tooltip(fn (UserStatus $record) => $record->premiumPlan?->type->title())
                ->alignCenter()
                ->getStateUsing(fn (UserStatus $record) => $record->premiumPlan?->type->color()),
            BadgeColumn::make('duration_id')
                ->label(__('names.plan type'))
                ->enum(PremiumDurationEnum::columnValues())
                ->color(static fn ($state) => PremiumDurationEnum::tryFrom($state)?->color()),
            TextColumn::make('date_diff')
                ->label(__('names.days remain'))
                ->sortable()
                ->tooltip(fn ($record) => Jalali::parse($record->end_date)->toJalaliDateTimeString()),
        ];
    }

    /**
     * @throws Exception
     */
    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('premium_plan')
                ->attribute('premium_plan_id')
                ->label(__('names.sale report plan'))
                ->multiple()
                ->options(PremiumPlanEnum::columnValues())
                ->default([PremiumPlanEnum::SILVER->value, PremiumPlanEnum::GOLD->value, PremiumPlanEnum::SPECIAL->value]),
            SelectFilter::make('premium_duration')
                ->attribute('duration_id')
                ->label(__('names.premium_duration.title'))
                ->multiple()
                ->options(PremiumDurationEnum::columnValues()),
            Tables\Filters\Filter::make('date_diff')
                ->form([
                    JalaliDatePicker::make('date_from')
                        ->default(now()->subDays(10)->startOfDay())
                        ->label(__('names.date from')),
                    JalaliDatePicker::make('date_until')
                        ->default(now()->addDays(6)->endOfDay())
                        ->label(__('names.date until')),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['date_from'],
                            fn (Builder $query, $date): Builder => $query->where('end_date', '>', $date),
                        )
                        ->when(
                            $data['date_until'],
                            fn (Builder $query, $date): Builder => $query->where('end_date', '<', $date),
                        );
                }),
        ];
    }

    protected function getTableFiltersLayout(): ?string
    {
        return Tables\Filters\Layout::AboveContent;
    }

    protected function getTableRecordUrlUsing(): ?Closure
    {
        return fn ($record) => UserResource::getUrl('view', ['record' => $record->id]);
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [50, 100, -1];
    }

    public function render(): View
    {
        return view('livewire.user-extension-resource.extension-table');
    }
}
