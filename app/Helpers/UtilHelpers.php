<?php

namespace App\Helpers;

use App\Models\PromoCode;
use Illuminate\Support\Str;

class UtilHelpers
{
    public static function getPayableAmount(
        int|float $totalAmount,
        int|float $addedValueAmount = 0,
        int|float $discountAmount = 0,
        int|float $walletAmount = 0,
        int|float $creditAmount = 0,
    ): int {
        return max(0, (int) (10 * floor(($totalAmount + $addedValueAmount - $discountAmount - $walletAmount - $creditAmount) / 10)));
    }

    public static function generatePromoCode(): string
    {
        do {
            $code = Str::random(7);
            $promoCode = PromoCode::where('code', $code)->exists();
        } while ($promoCode);

        return $code;
    }

    public static function getMonthName(int $month): string
    {
        return match ($month) {
            1 => 'فروردین',
            2 => 'اردیبهشت',
            3 => 'خرداد',
            4 => 'تیر',
            5 => 'مرداد',
            6 => 'شهریور',
            7 => 'مهر',
            8 => 'آبان',
            9 => 'آذر',
            10 => 'دی',
            11 => 'بهمن',
            12 => 'اسفند',
            default => '',
        };
    }
}