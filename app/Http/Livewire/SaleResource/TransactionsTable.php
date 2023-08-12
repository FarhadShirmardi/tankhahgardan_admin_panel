<?php

namespace App\Http\Livewire\SaleResource;

use App\Enums\PremiumDurationEnum;
use App\Enums\SaleReportTypeEnum;
use App\Enums\UserStatusTypeEnum;
use App\Filament\Components\JalaliDateTimeColumn;
use App\Filament\Components\RowIndexColumn;
use App\Models\UserStatusLog;
use DB;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
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
            ->with('user')
            ->where(fn (Builder $query) => $query->where('duration_id', '!=', PremiumDurationEnum::HALF_MONTH->value)->orWhere('price_id', '!=', PremiumDurationEnum::HALF_MONTH->value))
            ->select([
                'id',
                DB::raw('date(created_at) as date'),
                DB::raw("SUM(total_amount + added_value_amount - wallet_amount - credit_amount - discount_amount) as total_sum")
            ]);
    }

    protected function getTableColumns(): array
    {
        return [
            RowIndexColumn::make(),
            JalaliDateTimeColumn::make('date')
                ->label(__('names.date_time'))
                ->date(),
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


                $query = match ($typeFilter->getState()['value']) {
                    SaleReportTypeEnum::BY_DAY->value => $query->groupBy('date')
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
