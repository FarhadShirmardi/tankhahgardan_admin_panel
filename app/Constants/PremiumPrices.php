<?php
/**
 * Created by PhpStorm.
 * User: farhad
 * Date: 10/6/18
 * Time: 5:25 PM
 */

namespace App\Constants;

class PremiumPrices
{
    public static function getPrice($id)
    {
        $prices = collect(self::toArray());
        return $prices->where('id', $id)->first();
    }

    private static function toArray()
    {
        return [
            [
                'id' => PremiumDuration::HALF_MONTH,
                'user_price' => PremiumConstants::USER_PRICE * PremiumConstants::USER_COUNT_STEP * 0.5,
                'volume_price' => PremiumConstants::VOLUME_PRICE * PremiumConstants::VOLUME_SIZE_STEP * 0.5,
                'title' => trans('names.plan_title', ['plan' => PremiumDuration::getTitle(PremiumDuration::HALF_MONTH)]),
                'title2' => PremiumDuration::getSecondTitle(PremiumDuration::HALF_MONTH),
                'month_count' => 0.5,
                'day_count' => 15,
                'discount_percent' => 0,
                'active' => false,
                'is_gift' => true
            ],
            [
                'id' => PremiumDuration::MONTH,
                'user_price' => PremiumConstants::USER_PRICE * PremiumConstants::USER_COUNT_STEP,
                'volume_price' => PremiumConstants::VOLUME_PRICE * PremiumConstants::VOLUME_SIZE_STEP,
                'title' => trans('names.plan_title', ['plan' => PremiumDuration::getTitle(PremiumDuration::MONTH)]),
                'title2' => PremiumDuration::getSecondTitle(PremiumDuration::MONTH),
                'month_count' => 1,
                'day_count' => 31,
                'discount_percent' => 0,
                'active' => true,
                'is_gift' => false
            ],
            [
                'id' => PremiumDuration::YEAR,
                'user_price' => PremiumConstants::USER_PRICE * PremiumConstants::USER_COUNT_STEP * PremiumConstants::YEARLY_COEFFICIENT,
                'volume_price' => PremiumConstants::VOLUME_PRICE * PremiumConstants::VOLUME_SIZE_STEP * PremiumConstants::YEARLY_COEFFICIENT,
                'title' => trans('names.plan_title', ['plan' => PremiumDuration::getTitle(PremiumDuration::YEAR)]),
                'title2' => PremiumDuration::getSecondTitle(PremiumDuration::YEAR),
                'month_count' => 12,
                'day_count' => 365,
                'discount_percent' => 17,
                'active' => true,
                'is_gift' => false
            ]
        ];
    }

    public static function getPrices()
    {
        $prices = collect(self::toArray());
        return $prices->where('active', true)->toArray();
    }
}
