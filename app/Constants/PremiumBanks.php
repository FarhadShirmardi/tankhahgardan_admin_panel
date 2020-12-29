<?php
/**
 * Created by PhpStorm.
 * User: farhad
 * Date: 10/6/18
 * Time: 5:25 PM
 */

namespace App\Constants;

class PremiumBanks
{
    public static function getBank($id)
    {
        $banks = collect(self::toArray());
        return $banks->where('id', $id)->first();
    }

    private static function toArray()
    {
        return [
            [
                'id' => 1,
                'name' => 'زرین‌پال',
                'driver' => 'zarinpal',
                'price_percent' => 0.1,
                'active' => true,
            ],
            [
                'id' => 2,
                'name' => 'بانک سامان',
                'driver' => 'saman',
                'price_percent' => 1,
                'active' => false,
            ]
        ];
    }

    public static function getBanks()
    {
        $banks = collect(self::toArray());
        return $banks->where('active', true)->toArray();
    }
}
