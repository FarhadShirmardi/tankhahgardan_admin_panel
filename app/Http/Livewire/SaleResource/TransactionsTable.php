<?php

namespace App\Http\Livewire\SaleResource;

use App\Enums\PremiumDurationEnum;
use App\Enums\PremiumPlanEnum;
use App\Enums\SaleReportTypeEnum;
use App\Enums\UserStatusTypeEnum;
use App\Filament\Components\JalaliDateTimeColumn;
use App\Filament\Components\RowIndexColumn;
use App\Helpers\UtilHelpers;
use App\Models\DateMapping;
use App\Models\UserStatusLog;
use DB;
use Derakht\Jalali\Jalali;
use Exception;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Livewire\Component;

class TransactionsTable extends Component implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    public bool $isLoaded = false;

    public function loadData(): void
    {
        $this->isLoaded = true;
    }

    function getTypeFilterValue(): int|string
    {
        return $this->getCachedTableFilters()['type']->getState()['value'];
    }

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'created_at';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
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
            return DateMapping::query()->whereRaw('false');
        }

        $minMaxQuery = UserStatusLog::query()
            ->select([
                DB::raw("min(date(created_at)) as min_date"),
                DB::raw("max(date(created_at)) as max_date")
            ])
            ->first();

        $minDate = Jalali::parse($minMaxQuery->min_date);
        $minDate->updateJalali();
        $maxDate = Jalali::parse($minMaxQuery->max_date);
        $maxDate->updateJalali();

        return DateMapping::query()
            ->whereRaw('jalali_date >= ?', [$this->getFormattedDateText($minDate)])
            ->whereRaw('jalali_date <= ?', [$this->getFormattedDateText($maxDate)])
            ->join('user_status_logs', fn (JoinClause $join) => $join->whereColumn('user_status_logs.created_at', '>=', 'date_mappings.start_date')
                ->whereColumn('user_status_logs.created_at', '<=', 'date_mappings.end_date')
            )
            ->where(fn (Builder $query) => $query->where('duration_id', '!=', PremiumDurationEnum::HALF_MONTH->value)->orWhere('price_id', '!=', PremiumDurationEnum::HALF_MONTH->value))
            ->select([
                'id',
                'jalali_date',
                DB::raw('date(created_at) as date'),
                DB::raw("COUNT(*) as total_count"),
                DB::raw("SUM(IF(duration_id = '".PremiumDurationEnum::MONTH->value."' or price_id = '".PremiumDurationEnum::MONTH->value."', 1, 0)) as monthly_count"),
                DB::raw("SUM(IF(duration_id = '".PremiumDurationEnum::YEAR->value."' or price_id = '".PremiumDurationEnum::YEAR->value."', 1, 0)) as yearly_count"),
                DB::raw("SUM(IF(duration_id = '".PremiumDurationEnum::SPECIAL->value."' or price_id = '".PremiumDurationEnum::SPECIAL->value."', 1, 0)) as special_count"),
                DB::raw("substr(jalali_date, 1, 4) as year"),
                DB::raw("substr(jalali_date, 6, 2) as month"),
                DB::raw("SUM(total_amount + added_value_amount - wallet_amount - credit_amount - discount_amount) as total_sum")
            ]);
    }

    protected function getTableColumns(): array
    {
        return [
            RowIndexColumn::make(),
            JalaliDateTimeColumn::make('date')
                ->label(__('names.date_time'))
                ->visible(function () {
                    try {
                        return $this->getTypeFilterValue() == SaleReportTypeEnum::BY_DAY->value;
                    } catch (Exception) {
                        return true;
                    }
                })
                ->date(),
            TextColumn::make('year')
                ->label(__('names.year'))
                ->getStateUsing(function ($record) {
                    return $record->year;
                })
                ->visible(function () {
                    try {
                        return $this->getTypeFilterValue() == SaleReportTypeEnum::BY_YEAR->value;
                    } catch (Exception) {
                        return true;
                    }
                }),
            TextColumn::make('month_year')
                ->label(__('names.month and year'))
                ->getStateUsing(function ($record) {
                    return UtilHelpers::getMonthName((int) $record->month).' '.$record->year;
                })
                ->visible(function () {
                    try {
                        return $this->getTypeFilterValue() == SaleReportTypeEnum::BY_MONTH->value;
                    } catch (Exception) {
                        return true;
                    }
                }),
            TextColumn::make('total_count')
                ->formatStateUsing(fn ($record) => formatPrice($record->total_count))
                ->label(__('names.total count')),
            TextColumn::make('monthly_count')
                ->formatStateUsing(fn ($record) => formatPrice($record->monthly_count))
                ->label(__('names.monthly count')),
            TextColumn::make('yearly_count')
                ->formatStateUsing(fn ($record) => formatPrice($record->yearly_count))
                ->label(__('names.yearly count')),
            TextColumn::make('special_count')
                ->formatStateUsing(fn ($record) => formatPrice($record->special_count))
                ->label(__('names.special count')),
            TextColumn::make('total_sum')
                ->formatStateUsing(fn ($record) => formatPrice($record->total_sum))
                ->label(__('names.total amount')),
        ];
    }

    protected function applyFiltersToTableQuery(Builder $query): Builder
    {
        if (!$this->isLoaded) {
            return $query;
        }

        try {
            $query = match ((int) $this->getTypeFilterValue()) {
                SaleReportTypeEnum::BY_DAY->value => $query->groupBy('date'),
                SaleReportTypeEnum::BY_MONTH->value => $query->groupBy('jalali_date'),
                SaleReportTypeEnum::BY_YEAR->value => $query->groupBy('year'),
                default => $query->groupBy('user_status_logs.id')
            };
        } catch (Exception) {

        }

        $data = $this->getTableFiltersForm()->getRawState();

        $cachedTableFilters = collect($this->getCachedTableFilters())->reject(fn ($item) => $item->getName() == 'type')->toArray();

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

    /**
     * @throws Exception
     */
    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('type')
                ->label(__('names.sale report type'))
                ->options(SaleReportTypeEnum::columnValues())
                ->default(SaleReportTypeEnum::BY_DAY->value),
            SelectFilter::make('premium_plan')
                ->attribute('premium_plan_id')
                ->label(__('names.sale report plan'))
                ->multiple()
                ->options(PremiumPlanEnum::columnValues()),
            SelectFilter::make('user_status_state')
                ->label(__('names.user status state.label'))
                ->multiple()
                ->options(UserStatusTypeEnum::columnValues())
                ->default([UserStatusTypeEnum::SUCCEED->value])
                ->query(fn (Builder $query, array $state) => $query->when(filled($state['values']), fn ($q) => $q->whereIn('status', $state['values']))),
        ];
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [25, 50, -1];
    }

    public function render(): View
    {
        return view('livewire.sale-resource.transactions-table');
    }

    private function getFormattedDateText(Jalali $date): string
    {
        return str($date->jYear)->padLeft(4, '0').
            '-'.str($date->jMonth)->padLeft(2, '0');
    }

    protected function getTableFiltersLayout(): ?string
    {
        return Tables\Filters\Layout::AboveContent;
    }

    protected function shouldPersistTableFiltersInSession(): bool
    {
        return true;
    }
}
