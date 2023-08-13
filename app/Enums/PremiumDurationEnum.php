<?php

namespace App\Enums;

use App\Data\PremiumDurationData;
use App\Traits\HasColumnValues;
use Illuminate\Support\Collection;

enum PremiumDurationEnum: int
{
    use HasColumnValues;

    case MONTH = 1;
    case HALF_MONTH = 2;
    case ONE_WEEK = 3;

    case YEAR = 4;

    case SPECIAL = 5;

    public function description(): string
    {
        return match ($this) {
            self::MONTH => __('names.premium_duration.one_month'),
            self::HALF_MONTH => __('names.premium_duration.half_month'),
            self::ONE_WEEK => __('names.premium_duration.one_week'),
            self::YEAR => __('names.premium_duration.one_year'),
            self::SPECIAL => __('names.premium_duration.special'),
        };
    }

    public function getDayCount(): int
    {
        return match ($this) {
            self::ONE_WEEK => 7,
            self::MONTH => 31,
            self::YEAR => 365,
            self::HALF_MONTH => 15,
            self::SPECIAL => 0,
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::MONTH => 'primary',
            self::YEAR => 'success',
            self::HALF_MONTH, self::ONE_WEEK => 'secondary',
            self::SPECIAL => 'warning',
        };
    }

    public function getTitle(): string
    {
        return match ($this) {
            self::ONE_WEEK => __('names.premium_duration.one_week'),
            self::MONTH => __('names.premium_duration.one_month'),
            self::YEAR => __('names.premium_duration.one_year'),
            self::HALF_MONTH => __('names.premium_duration.half_month'),
            self::SPECIAL => __('names.premium_duration.special'),
        };
    }

    public static function getItem(int $id, int $basePrice = 0, float $yearlyDiscountPercent = 0): PremiumDurationData
    {
        $items = self::getList($basePrice, $yearlyDiscountPercent);

        return $items->firstWhere('id', $id);
    }

    public static function getList(int $basePrice = 0, float $yearlyDiscountPercent = 0, bool $firstBuyFlag = true): Collection
    {
        $halfMonth = self::HALF_MONTH;
        $oneMonth = self::MONTH;
        $oneYear = self::YEAR;
        $special = self::SPECIAL;
        $items = collect([
            (new PremiumDurationData($halfMonth, true, 0, 0)),
            (new PremiumDurationData($oneMonth, false, $basePrice, $basePrice)),
            (new PremiumDurationData($oneYear, false, $basePrice * 12, roundDown(
                $basePrice * 12 * (1 - $yearlyDiscountPercent),
                0
            ))),
            (new PremiumDurationData($special, false, 0,0)),
        ]);
        if (! $firstBuyFlag) {
            $items = $items->where('is_gift', false);
        }

        return $items->values();
    }
}
