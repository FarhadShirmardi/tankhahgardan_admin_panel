<?php

namespace App\Filament\Resources\SaleResource\Widgets;

use App\Enums\PremiumDurationEnum;
use App\Enums\UserStatusTypeEnum;
use App\Helpers\Colors;
use App\Models\DateMapping;
use App\Models\UserStatusLog;
use DB;
use Derakht\Jalali\Jalali;
use Filament\Widgets\BarChartWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;

class SalesTargetChart extends BarChartWidget
{
    protected static ?int $sort = 2;

    protected function getOptions(): ?array
    {
        return [
            'plugins' => [
                'title' => [
                    'display' => true,
                    'text' => 'نمودار اهداف فروش',
                ],

                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }

    protected function getData(): array
    {
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

        $data = DateMapping::query()
            ->join('panel_sale_targets', 'panel_sale_targets.jalali_date', 'date_mappings.jalali_date')
            ->join('user_status_logs', fn (JoinClause $join) => $join->whereColumn('user_status_logs.created_at', '>=', 'date_mappings.start_date')
                ->whereColumn('user_status_logs.created_at', '<=', 'date_mappings.end_date')
            )
            ->groupByRaw('CONCAT_WS(year, month)')
            ->whereRaw('date_mappings.jalali_date >= ?', [$this->getFormattedDateText($minDate)])
            ->whereRaw('date_mappings.jalali_date <= ?', [$this->getFormattedDateText($maxDate)])
            ->where(fn (Builder $query) => $query->where('duration_id', '!=', PremiumDurationEnum::HALF_MONTH->value)->orWhere('price_id', '!=', PremiumDurationEnum::HALF_MONTH->value))
            ->where('status', UserStatusTypeEnum::SUCCEED)
            ->get([
                'user_status_logs.id',
                'date_mappings.jalali_date',
                'panel_sale_targets.amount as target',
                DB::raw('date(user_status_logs.created_at) as date'),
                DB::raw("substr(date_mappings.jalali_date, 1, 4) as year"),
                DB::raw("substr(date_mappings.jalali_date, 6, 2) as month"),
                DB::raw("SUM(total_amount + added_value_amount - wallet_amount - credit_amount - discount_amount) as total_sum")
            ]);

        return [
            'datasets' => [
                [
                    'type' => 'bar',
                    'data' => $data->pluck('total_sum')->map(fn ($value) => round($value / 10000000, 2))->toArray(),
                    'backgroundColor' => Colors::getBackground(),
                    'borderColor' => Colors::getBorder(),
                ],
                [
                    'type' => 'line',
                    'data' => $data->pluck('target')->toArray(),
                    'borderColor' => \Arr::random(Colors::getBorder()),
                ],
            ],
            'labels' => $data->map(fn ($item) => Jalali::parse($item->date)->toJalaliDateString())->toArray(),
        ];
    }

    private function getFormattedDateText(Jalali $date): string
    {
        return str($date->jYear)->padLeft(4, '0').
            '-'.str($date->jMonth)->padLeft(2, '0');
    }
}
