<?php

namespace App\Helpers;

class Colors
{
    /**
     * @return string[]
     */
    public static function getBackground(): array
    {
        return [
            '#FF6384',
            '#FF9F40',
            '#FFCD56',
            '#4BC0C0',
            '#36A2EB',
            '#9966FF',
            '#C9CBCE'
        ];
    }

    /**
     * @return string[]
     */
    public static function getBorder(): array
    {
        return [
            '#FF1E50',
            '#FF7F1E',
            '#FFB820',
            '#2D9E9E',
            '#2769BB',
            '#8040BF',
            '#B5B7BC'
        ];
    }

    public static function getColorByPercentage($percentage): ?string
    {
        if ($percentage < 10) {
            return '#2D2D2D';  // Dark gray color for under 10%
        } elseif ($percentage <= 50) {
            return '#FFD700';  // Gold color for 10% to 50%
        } elseif ($percentage <= 90) {
            return '#4169E1';  // Royal blue color for 50% to 90%
        } else {
            return '#00FF00';  // Green color for 90% to 100%
        }
    }
}