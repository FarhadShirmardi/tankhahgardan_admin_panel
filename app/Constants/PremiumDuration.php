<?php

namespace App\Constants;

use BenSampo\Enum\Enum;

class PremiumDuration extends Enum
{
    public const MONTH = 1;

    public const HALF_MONTH = 2;

    public const ONE_WEEK = 3;

    public const YEAR = 4;

    public const SPECIAL = 5;

    public static function getTitle(int $enum): string
    {
        return match ($enum) {
            self::ONE_WEEK => trans('names.one_week'),
            self::MONTH => trans('names.one_month'),
            self::YEAR => trans('names.one_year'),
            self::HALF_MONTH => trans('names.half_month'),
            self::SPECIAL => 'ویژه',
            default => ' - ',
        };
    }

    public static function getSecondTitle(int $enum): string
    {
        return match ($enum) {
            self::ONE_WEEK => trans('names.one_week_2'),
            self::MONTH => trans('names.one_month_2'),
            self::YEAR => trans('names.one_year_2'),
            self::HALF_MONTH => trans('names.half_month_2'),
            self::SPECIAL => 'ویژه',
            default => ' - ',
        };
    }

    public static function asCustomArray(int $basePrice = 0, float $yearlyDiscountPercent = 0, bool $firstBuyFlag = true): array
    {
        $items = collect([
            [
                'id' => self::HALF_MONTH,
                'title' => self::getSecondTitle(self::HALF_MONTH),
                'description' => self::getDescriptionValue(self::HALF_MONTH),
                'day_count' => 15,
                'month_count' => 0.5,
                'is_gift' => true,
                'real_price' => 0,
                'price' => 0,
            ],
            [
                'id' => self::MONTH,
                'title' => self::getSecondTitle(self::MONTH),
                'description' => self::getDescriptionValue(self::MONTH),
                'day_count' => 31,
                'month_count' => 1,
                'is_gift' => false,
                'real_price' => $basePrice,
                'price' => $basePrice,
            ],
            [
                'id' => self::YEAR,
                'title' => self::getSecondTitle(self::YEAR),
                'description' => self::getDescriptionValue(self::YEAR),
                'day_count' => 365,
                'month_count' => 12,
                'is_gift' => false,
                'real_price' => $basePrice * 12,
                'price' => roundDown(
                    $basePrice * 12 * (1 - $yearlyDiscountPercent),
                    0
                ),
            ],
        ]);
        if (! $firstBuyFlag) {
            $items = $items->where('is_gift', false);
        }

        return $items->values()->toArray();
    }

    public static function getItem(int $id, int $basePrice = 0, float $yearlyDiscountPercent = 0)
    {
        $items = collect(self::asCustomArray($basePrice, $yearlyDiscountPercent));

        return $items->firstWhere('id', $id);
    }

    public static function getDescriptionValue(int $enum): ?string
    {
        return match ($enum) {
            self::HALF_MONTH => '۱۵ روز رایگان تجربه کنید!',
            default => null
        };
    }
}
