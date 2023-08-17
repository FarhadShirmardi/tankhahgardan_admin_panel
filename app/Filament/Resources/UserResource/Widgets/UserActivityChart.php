<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Enums\ActivityTypeEnum;
use App\Models\User;
use App\Models\UserReport;
use Filament\Widgets\BarChartWidget;

class UserActivityChart extends BarChartWidget
{
    protected static ?int $sort = 3;
    private static function totalUserCount(): int
    {
        return User::query()->count();
    }

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
                    'text' => 'نمودار تعداد بر حسب وضعیت',
                ],
                'subtitle' => [
                    'display' => true,
                    'text' => "تعداد کل کاربر: " . self::totalUserCount(),
                ],

                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }

    protected function getData(): array
    {
        $counts = UserReport::query()
            ->groupBy('user_type')
            ->orderBy('user_type')
            ->where('user_type', '<>', ActivityTypeEnum::DISABLE)
            ->get([
                \DB::raw("count(*) count"),
                'user_type',
            ]);

        return [
            'datasets' => [
                [
                    'label' => 'نمودار تعداد بر حسب وضعیت',
                    'backgroundColor' => $counts->map(fn ($item) => ActivityTypeEnum::from($item->user_type)->color())->toArray(),
                    'data' => $counts->pluck('count')->toArray(),
                ],
            ],
            'labels' => $counts->map(fn ($item) => ActivityTypeEnum::from($item->user_type)->description())->toArray(),
        ];
    }
}
