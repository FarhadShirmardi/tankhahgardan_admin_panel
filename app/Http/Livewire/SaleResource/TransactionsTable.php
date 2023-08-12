<?php

namespace App\Http\Livewire\SaleResource;

use App\Enums\PremiumDurationEnum;
use App\Enums\SaleReportTypeEnum;
use App\Enums\UserStatusTypeEnum;
use App\Filament\Components\JalaliDateTimeColumn;
use App\Filament\Components\RowIndexColumn;
use App\Helpers\UtilHelpers;
use App\Models\UserStatusLog;
use DB;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
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
            return UserStatusLog::query()->whereRaw('false');
        }

        return UserStatusLog::query()
            ->join('date_mappings', fn (JoinClause $join) => $join
                ->whereRaw('date_mappings.start_date <= date(user_status_logs.created_at)')
                ->whereRaw('date_mappings.end_date >= date(user_status_logs.created_at)')
            )
            ->with('user')
            ->where(fn (Builder $query) => $query->where('duration_id', '!=', PremiumDurationEnum::HALF_MONTH->value)->orWhere('price_id', '!=', PremiumDurationEnum::HALF_MONTH->value))
            ->select([
                'id',
                'jalali_date',
                DB::raw('date(created_at) as date'),
                DB::raw("count(*) as total_count"),
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
                        return $this->getCachedTableFilters()['type']->getState()['value'] == SaleReportTypeEnum::BY_DAY->value;
                    } catch (\Exception) {
                        return true;
                    }
                })
                ->date(),
            TextColumn::make('month_name')
                ->label(__('names.month and year'))
                ->getStateUsing(function (UserStatusLog $record) {
                    return UtilHelpers::getMonthName((int) $record->month) . ' ' . $record->year;
                })
                ->visible(function () {
                    try {
                        return $this->getCachedTableFilters()['type']->getState()['value'] == SaleReportTypeEnum::BY_MONTH->value;
                    } catch (\Exception) {
                        return true;
                    }
                }),
            TextColumn::make('total_count')
                ->formatStateUsing(fn ($record) => formatPrice($record->total_count))
                ->label(__('names.total count')),
            TextColumn::make('total_sum')
                ->formatStateUsing(fn ($record) => formatPrice($record->total_sum))
                ->label(__('names.total amount')),
        ];
    }

    protected function applyFiltersToTableQuery(Builder $query): Builder
    {
        if ($this->isLoaded) {
            try {
                /** @var Tables\Filters\SelectFilter $typeFilter */
                $typeFilter = $this->getCachedTableFilters()['type'];


                $query = match ((int) $typeFilter->getState()['value']) {
                    SaleReportTypeEnum::BY_DAY->value => $query->groupBy('date'),
                    SaleReportTypeEnum::BY_MONTH->value => $query->groupBy('jalali_date')
                };
            } catch (\Exception) {

            }
        }

        $data = $this->getTableFiltersForm()->getRawState();

        foreach ($this->getCachedTableFilters() as $filter) {
            $filter->applyToBaseQuery(
                $query,
                $data[$filter->getName()] ?? [],
            );
        }

        return $query->where(function (Builder $query) use ($data) {
            foreach ($this->getCachedTableFilters() as $filter) {
                $filter->apply(
                    $query,
                    $data[$filter->getName()] ?? [],
                );
            }
        });
    }

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('type')
                ->label(__('names.sale report type'))
                ->options(SaleReportTypeEnum::columnValues())
                ->default(SaleReportTypeEnum::BY_DAY->value),
            TernaryFilter::make('user_status_state')
                ->label(__('names.user status state.label'))
                ->default()
                ->placeholder(__('names.user status state.all'))
                ->trueLabel(__('names.user status state.success'))
                ->falseLabel(__('names.user status state.failed'))
                ->queries(
                    true: fn (Builder $query) => $query->where('user_status_logs.status', UserStatusTypeEnum::SUCCEED),
                    false: fn (Builder $query) => $query->where('user_status_logs.status', UserStatusTypeEnum::FAILED),
                    blank: fn (Builder $query) => $query,
                )
        ];
    }

    protected function getTableRecordsPerPage(): int
    {
        return 100;
    }

    public function render(): View
    {
        return view('livewire.sale-resource.transactions-table');
    }
}
