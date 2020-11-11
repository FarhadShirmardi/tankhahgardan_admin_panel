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
    const USER_COUNT_MIN = 1;
    const USER_COUNT_MAX = 10;
    const USER_COUNT_STEP = 1;

    const VOLUME_SIZE_MIN = 1000;
    const VOLUME_SIZE_MAX = 10000;
    const VOLUME_SIZE_STEP = 1000;

    const YEARLY_COEFFICIENT = 10;
    const ADDED_VALUE_PERCENT = 0.09;

    const USER_PRICE = 120000;
    const VOLUME_PRICE = 20;

    const NEAR_END_THRESHOLD = 5;

    const FREE_PROJECT_LIMIT = 3;
    const FREE_USER_COUNT = 0;
    const FREE_VOLUME_SIZE = 200;
    const IMAGE_COUNT_LIMIT = 250;
    const PDF_COUNT_LIMIT = 1;
    const ACTIVITY_COUNT_LIMIT = 100;

    const FIRST_REGISTER_CAMPAIGN_SYMBOL = 'REG_15';
    const FIRST_REGISTER_CAMPAIGN_NAME = 'هدیه ثبت‌نام در تنخواه‌گردان';
    const FIRST_REGISTER_PROMO_TEXT = 'کد تخفیف هدیه ثبت‌نام در تنخواه‌گردان';

    const REFERENCE_CAMPAIGN_SYMBOL = 'REG_REF';
    const REFERENCE_CAMPAIGN_NAME = 'دعوت از کاربر';
    const REFERENCE_PROMO_TEXT = 'هدیه عضویت از طریق دوستان';
    const REFERENCE_PROMO_PERCENT = 25;
    const REFERENCE_PROMO_MAX_DISCOUNT = null;
    const REFERENCE_CHARGE_AMOUNT = 30000;
}
