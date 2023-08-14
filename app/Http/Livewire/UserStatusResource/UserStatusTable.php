<?php

namespace App\Http\Livewire\UserStatusResource;

use App\Enums\PremiumDurationEnum;
use App\Enums\PremiumPlanEnum;
use App\Enums\ProjectUserTypeEnum;
use App\Enums\SaleReportTypeEnum;
use App\Filament\Components\JalaliDatePicker;
use App\Filament\Components\JalaliDateTimeColumn;
use App\Filament\Components\RowIndexColumn;
use App\Filament\Resources\UserResource;
use App\Models\ProjectReport;
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

class UserStatusTable extends Component implements Tables\Contracts\HasTable
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

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'max_time';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'asc';
    }

    function getMaxTimeFilter(): array
    {
        return $this->getCachedTableFilters()['max_time']->getState();
    }

    protected function applyFiltersToTableQuery(Builder $query): Builder
    {
        if (!$this->isLoaded) {
            return $query;
        }

        $maxTimeFilter = $this->getMaxTimeFilter();

        $query = $query
            ->when($maxTimeFilter['date_from'], fn (Builder $query, $value) => $query->having('max_time', '>', $value))
            ->when($maxTimeFilter['date_until'], fn (Builder $query, $value) => $query->having('max_time', '<', $value));

        $data = $this->getTableFiltersForm()->getRawState();

        $cachedTableFilters = collect($this->getCachedTableFilters())->reject(fn ($item) => $item->getName() == 'max_time')->toArray();

        foreach ($cachedTableFilters as $filter) {
            $filter->applyToBaseQuery(
                $query,
                $data[$filter->getName()] ?? [],
            );
        }

        return $query->where(function (Builder $query) use ($data, $cachedTableFilters) {
            foreach ($cachedTableFilters as $filter) {
                $filter->apply(
                    $query,
                    $data[$filter->getName()] ?? [],
                );
            }
        });
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
            ->where('end_date', '>', now()->toDateTimeString())
            ->join('panel_user_reports', 'panel_user_reports.id', 'user_statuses.user_id')
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
                ->default([PremiumPlanEnum::SILVER->value, PremiumPlanEnum::GOLD->value]),
            SelectFilter::make('premium_duration')
                ->attribute('duration_id')
                ->label(__('names.premium_duration.title'))
                ->multiple()
                ->options(PremiumDurationEnum::columnValues()),
            Tables\Filters\Filter::make('max_time')
                ->form([
                    JalaliDatePicker::make('date_from')
                        ->default(now()->subDays(12)->startOfDay())
                        ->label(__('names.date from')),
                    JalaliDatePicker::make('date_until')
                        ->default(now()->subDays(5)->endOfDay())
                        ->label(__('names.date until')),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['date_from'],
                            fn (Builder $query, $date): Builder => $query->having('max_time', '>', $date),
                        )
                        ->when(
                            $data['date_until'],
                            fn (Builder $query, $date): Builder => $query->having('max_time', '<', $date),
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
        return view('livewire.user-status-resource.user-status-table');
    }
}
