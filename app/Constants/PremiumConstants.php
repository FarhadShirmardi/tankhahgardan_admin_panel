<?php
/**
 * Created by PhpStorm.
 * User: farhad
 * Date: 10/6/18
 * Time: 5:25 PM
 */

namespace App\Constants;

class PremiumConstants
{
    public const FIRST_REGISTER_CAMPAIGN_SYMBOL = 'REG_15';
    public const FIRST_REGISTER_CAMPAIGN_NAME = 'هدیه ثبت‌نام در تنخواه‌گردان';
    public const FIRST_REGISTER_PROMO_TEXT = 'کد تخفیف هدیه ثبت‌نام در تنخواه‌گردان';

    public const REFERENCE_CAMPAIGN_SYMBOL = 'REG_REF';
    public const REFERENCE_CAMPAIGN_NAME = 'دعوت از کاربر';
    public const REFERENCE_PROMO_TEXT = 'هدیه عضویت از طریق دوستان';
    public const REFERENCE_PROMO_PERCENT = 25;
    public const REFERENCE_PROMO_MAX_DISCOUNT = null;
    public const REFERENCE_CHARGE_AMOUNT = 30000;

    public const YEARLY_COEFFICIENT = 7.66;
    public const ADDED_VALUE_PERCENT = 0.09;
    public const NEAR_END_THRESHOLD = 7;
    public const FREE_PROJECT_LIMIT = 3;
    public const FREE_USER_COUNT = 0;
    public const FREE_VOLUME_SIZE = 100;
    public const IMAGE_COUNT_LIMIT = 100;
    public const PDF_COUNT_LIMIT = 1;
    public const IMPREST_COUNT_LIMIT = 5;

    public const USER_PRICE = [
        [
            'value' => 1,
            'price' => 150000,
            'active' => true,
        ],
        [
            'value' => 2,
            'price' => 300000,
            'active' => true,
        ],
        [
            'value' => 3,
            'price' => 450000,
            'active' => true,
        ],
        [
            'value' => 4,
            'price' => 600000,
            'active' => true,
        ],
        [
            'value' => 5,
            'price' => 750000,
            'active' => true,
        ],
        [
            'value' => 6,
            'price' => 900000,
            'active' => true,
        ],
        [
            'value' => 7,
            'price' => 1050000,
            'active' => true,
        ],
        [
            'value' => 8,
            'price' => 1200000,
            'active' => true,
        ],
        [
            'value' => 9,
            'price' => 1350000,
            'active' => true,
        ],
        [
            'value' => 10,
            'price' => 1500000,
            'active' => true,
        ],
        [
            'value' => 12,
            'price' => 1800000,
            'active' => true,
        ],
        [
            'value' => 15,
            'price' => 2250000,
            'active' => true,
        ],
        [
            'value' => 20,
            'price' => 3000000,
            'active' => true,
        ],
        [
            'value' => 30,
            'price' => 4500000,
            'active' => true,
        ],
        [
            'value' => 40,
            'price' => 6000000,
            'active' => true,
        ],
        [
            'value' => 50,
            'price' => 7500000,
            'active' => true,
        ],
        [
            'value' => 75,
            'price' => 11250000,
            'active' => true,
        ],
        [
            'value' => 100,
            'price' => 15000000,
            'active' => true,
        ],
    ];
    public const VOLUME_PRICE = [
        [
            'value' => 1000,
            'price' => 40000,
            'active' => true,
        ],
        [
            'value' => 2000,
            'price' => 80000,
            'active' => true,
        ],
        [
            'value' => 3000,
            'price' => 120000,
            'active' => true,
        ],
        [
            'value' => 4000,
            'price' => 160000,
            'active' => true,
        ],
        [
            'value' => 5000,
            'price' => 200000,
            'active' => true,
        ],
        [
            'value' => 6000,
            'price' => 240000,
            'active' => true,
        ],
        [
            'value' => 7000,
            'price' => 280000,
            'active' => true,
        ],
        [
            'value' => 8000,
            'price' => 320000,
            'active' => true,
        ],
        [
            'value' => 9000,
            'price' => 360000,
            'active' => true,
        ],
        [
            'value' => 10000,
            'price' => 400000,
            'active' => true,
        ],
    ];
    public const CONSTANT_PRICE = 70000;
}
