<?php

namespace App\Filament\Resources\SaleResource\Widgets;

use App\Enums\PremiumDurationEnum;
use App\Enums\UserStatusTypeEnum;
use App\Models\UserStatusLog;
use DB;
use Derakht\Jalali\Jalali;
use Illuminate\Database\Eloquent\Builder;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class SalesSumChart extends ApexChartWidget
{
    protected static ?int $sort = 2;

    protected static string $chartId = 'salesSumChart';

    protected static ?string $heading = 'نمودار فروش ۷ روز اخیر';

    protected function getOptions(): array
    {
        $data = UserStatusLog::query()
            ->groupBy('date')
            ->orderBy('created_at')
            ->where('created_at', '>', now()->subDays(7)->startOfDay())
            ->where(fn (Builder $query) => $query->where('duration_id', '!=', PremiumDurationEnum::HALF_MONTH->value)->orWhere('price_id', '!=', PremiumDurationEnum::HALF_MONTH->value))
            ->where('status', UserStatusTypeEnum::SUCCEED)
            ->get([
                DB::raw('date(created_at) as date'),
                DB::raw("SUM(total_amount + added_value_amount - wallet_amount - credit_amount - discount_amount) as total_sum")
            ]);

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 250,
            ],
            'series' => [
                [
                    'name' => 'TasksChart',
                    'data' => $data->pluck('total_sum'),
                ],
            ],
            'xaxis' => [
                'categories' => $data->map(fn ($item) => Jalali::parse($item->date)->toJalaliDateString()),
                'labels' => [
                    'style' => [
                        'colors' => '#9ca3af',
                        'fontWeight' => 600,
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'colors' => '#9ca3af',
                        'fontWeight' => 600,
                    ],
                ],
            ],
            'colors' => ['#6366f1'],
            'plotOptions' => [
                'bar' => [
                    'borderRadius' => 10,
                    'columnWidth' => '65%',
                ],
            ],
        ];
    }
}
