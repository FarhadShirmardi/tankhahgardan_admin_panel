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
}