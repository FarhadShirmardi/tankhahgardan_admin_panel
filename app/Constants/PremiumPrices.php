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
    public static function getPrice($id, $minUserCount = 0, $minVolume = 0, $isUpgrade = false)
    {
        $prices = collect(self::toArray($minUserCount, $minVolume, $isUpgrade));
        return $prices->where('id', $id)->first();
    }

    private static function toArray($minUserCount = 0, $minVolume = 0, $isUpgrade = false)
    {
        return [
            [
                'id' => PremiumDuration::ONE_WEEK,
                'user_price' => self::filterPrice(PremiumConstants::USER_PRICE, 0.25, $minUserCount, $isUpgrade),
                'volume_price' => self::filterPrice(PremiumConstants::VOLUME_PRICE, 0.25, $minVolume, $isUpgrade),
                'constant_price' => PremiumConstants::CONSTANT_PRICE,
                'title' => trans('names.plan_title', ['plan' => PremiumDuration::getTitle(PremiumDuration::ONE_WEEK)]),
                'title2' => PremiumDuration::getSecondTitle(PremiumDuration::ONE_WEEK),
                'month_count' => 0.25,
                'day_count' => 7,
                'discount_percent' => 0,
                'active' => false,
                'is_gift' => true,
            ],
            [
                'id' => PremiumDuration::HALF_MONTH,
                'user_price' => self::filterPrice(PremiumConstants::USER_PRICE, 0.5, $minUserCount, $isUpgrade),
                'volume_price' => self::filterPrice(PremiumConstants::VOLUME_PRICE, 0.5, $minVolume, $isUpgrade),
                'constant_price' => PremiumConstants::CONSTANT_PRICE,
                'title' => trans('names.gift_title', ['plan' => PremiumDuration::getTitle(PremiumDuration::HALF_MONTH)]),
                'title2' => PremiumDuration::getSecondTitle(PremiumDuration::HALF_MONTH),
                'month_count' => 0.5,
                'day_count' => 15,
                'discount_percent' => 0,
                'active' => false,
                'is_gift' => true,
            ],
            [
                'id' => PremiumDuration::MONTH,
                'user_price' => self::filterPrice(PremiumConstants::USER_PRICE, 1, $minUserCount, $isUpgrade),
                'volume_price' => self::filterPrice(PremiumConstants::VOLUME_PRICE, 1, $minVolume, $isUpgrade),
                'constant_price' => PremiumConstants::CONSTANT_PRICE,
                'title' => trans('names.plan_title', ['plan' => PremiumDuration::getTitle(PremiumDuration::MONTH)]),
                'title2' => PremiumDuration::getSecondTitle(PremiumDuration::MONTH),
                'month_count' => 1,
                'day_count' => 31,
                'discount_percent' => 0,
                'active' => true,
                'is_gift' => false,
            ],
            [
                'id' => PremiumDuration::YEAR,
                'user_price' => self::filterPrice(PremiumConstants::USER_PRICE, PremiumConstants::YEARLY_COEFFICIENT, $minUserCount, $isUpgrade, 12, 0.5, PremiumConstants::CONSTANT_PRICE * (12 - PremiumConstants::YEARLY_COEFFICIENT)),
                'volume_price' => self::filterPrice(PremiumConstants::VOLUME_PRICE, PremiumConstants::YEARLY_COEFFICIENT, $minVolume, $isUpgrade, 12, 0.5),
                'constant_price' => PremiumConstants::CONSTANT_PRICE * PremiumConstants::YEARLY_COEFFICIENT,
                'title' => trans('names.plan_title', ['plan' => PremiumDuration::getTitle(PremiumDuration::YEAR)]),
                'title2' => PremiumDuration::getSecondTitle(PremiumDuration::YEAR),
                'month_count' => 12,
                'day_count' => 365,
                'discount_percent' => 17,
                'active' => true,
                'is_gift' => false,
            ],
            [
                'id' => PremiumDuration::SPECIAL,
                'user_price' => self::filterPrice(PremiumConstants::USER_PRICE, 1, $minUserCount, $isUpgrade, 1, 1, PremiumConstants::CONSTANT_PRICE),
                'volume_price' => self::filterPrice(PremiumConstants::VOLUME_PRICE, 1, $minVolume, $isUpgrade),
                'constant_price' => PremiumConstants::CONSTANT_PRICE,
                'title' => trans('names.plan_title', ['plan' => PremiumDuration::getTitle(PremiumDuration::SPECIAL)]),
                'title2' => PremiumDuration::getSecondTitle(PremiumDuration::SPECIAL),
                'month_count' => 1,
                'day_count' => 31,
                'discount_percent' => 0,
                'active' => false,
                'is_gift' => false,
            ],
        ];
    }

    public static function filterPrice(array $prices, $coef, $minValue, $isUpgrade, $coef2 = 1, $coef3 = 1, $constantPrice = 0): array
    {
        $prices = collect($prices);
        if ($isUpgrade and $minValue) {
            $minPrice = $prices->where('value', $minValue)->first()['price'];
            $prices = $prices->map(function ($item) use ($minValue, $minPrice) {
                $item['value'] -= $minValue;
                $item['price'] -= $minPrice;
                return $item;
            });
            $minValue = 0;
        }
        $prices = $prices->map(function ($item) use ($coef, $coef2, $coef3, $constantPrice) {
            $item['real_price'] = $item['price'] * $coef2 + $constantPrice;
            $item['first_buy_price'] = $item['real_price'] * $coef3;
            $item['price'] *= $coef;
            return $item;
        });
        return $prices->where('value', '>=', $minValue)->where('active', true)->toArray();
    }

    public static function getPrices($minUserCount = 0, $minVolume = 0, $isUpgrade = false)
    {
        $prices = collect(self::toArray($minUserCount, $minVolume, $isUpgrade));
        return $prices->where('active', true)->toArray();
    }
}
