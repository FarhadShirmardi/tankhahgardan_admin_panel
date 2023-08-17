<?php

namespace App\Helpers;

use Illuminate\Support\Arr;

class Colors
{
    /**
     * @return string[]
     */
    public static function getBackground(): array
    {
        return collect([
            '#FF6384',
            '#FF9F40',
            '#FFCD56',
            '#4BC0C0',
            '#36A2EB',
            '#9966FF',
            '#C9CBCE'
        ])->shuffle()->toArray();
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
}