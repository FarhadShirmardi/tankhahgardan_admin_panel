<?php

namespace App\Filament\Resources\SaleResource\Widgets;

use App\Enums\PremiumDurationEnum;
use App\Enums\UserStatusTypeEnum;
use App\Models\UserStatusLog;
use DB;
use Derakht\Jalali\Jalali;
use Filament\Widgets\BarChartWidget;
use Filament\Widgets\LineChartWidget;
use Illuminate\Database\Eloquent\Builder;

class SalesSumChart extends BarChartWidget
{

    protected function getOptions(): ?array
    {
        return [
            'plugins' => [
                'title' => [
                    'display' => true,
                    'text' => 'نمودار فروش ۷ روز اخیر',
                ],

                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }

    protected function getData(): array
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
            'datasets' => [
                [
                    'label' => 'نمودار فروش بر حسب روز',
                    'data' => $data->pluck('total_sum')->toArray(),
                    'backgroundColor' => [
                        '#FF6384',
                        '#FF9F40',
                        '#FFCD56',
                        '#4BC0C0',
                        '#36A2EB',
                        '#9966FF',
                        '#C9CBCE'
                    ],
                    'borderColor' => [
                        '#FF6384',
                        '#FF9F40',
                        '#FFCD56',
                        '#4BC0C0',
                        '#36A2EB',
                        '#9966FF',
                        '#C9CBCE'
                    ],
                ],
            ],
            'labels' => $data->map(fn ($item) => Jalali::parse($item->date)->toJalaliDateString())->toArray(),
        ];
    }
}
