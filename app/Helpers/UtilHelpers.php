<?php

namespace App\Helpers;

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
}