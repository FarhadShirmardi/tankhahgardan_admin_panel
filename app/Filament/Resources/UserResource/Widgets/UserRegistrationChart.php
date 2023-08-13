<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Enums\ActivityTypeEnum;
use App\Enums\UserStateEnum;
use App\Filament\Components\JalaliDateTimeColumn;
use App\Models\User;
use App\Models\UserReport;
use Derakht\Jalali\Jalali;
use Filament\Widgets\BarChartWidget;
use Filament\Widgets\LineChartWidget;

class UserRegistrationChart extends LineChartWidget
{
    protected static ?int $sort = 3;

    protected function getMaxHeight(): ?string
    {
        return '300';
    }

    protected function getOptions(): ?array
    {
        return [
            'plugins' => [
                'title' => [
                    'display' => true,
                    'text' => 'نمودار تعداد ثبت‌نامی ۷ روز اخیر',
                ],

                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }

    protected function getData(): array
    {
        $counts = User::query()
            ->groupByRaw('date')
            ->orderBy('date')
            ->where('state', UserStateEnum::ACTIVE)
            ->where('created_at', '>', now()->subDays(6))
            ->get([
                \DB::raw("count(*) count"),
                \DB::raw("date(verification_time) date"),
            ]);

        return [
            'datasets' => [
                [
                    'label' => 'تعداد',
                    'data' => $counts->pluck('count')->toArray(),
                ],
            ],
            'labels' => $counts->map(fn ($item) => Jalali::parse($item->date)->toJalaliDateString())->toArray(),
        ];
    }
}
