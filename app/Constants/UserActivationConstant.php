<?php

namespace App\Constants;

class UserActivationConstant
{
    const STATE_ACTIVE_USER = 0;
    const STATE_FIRST_SMS = 1;
    const STATE_FIRST_STEP_INACTIVE = 100;
    const STATE_FIRST_CALL = 2;
    const STATE_SECOND_SMS = 3;
    const STATE_SECOND_STEP_INACTIVE = 300;
    const STATE_SECOND_CALL = 4;
    const STATE_THIRD_SMS = 5;
    const STATE_THIRD_STEP_INACTIVE = 500;
    const STATE_THIRD_CALL = 6;
    const STATE_NPS_SMS = 7;
    const STATE_REFERRAL_SMS = 8;

    const STATE_FIRST_ATTEMPT_DIE = 1000;
    const STATE_SECOND_ATTEMPT_DIE = 2000;
    const STATE_THIRD_ATTEMPT_DIE = 3000;

    const SMS_TEXT_FIRST = 'بیا آموزش ببین و پشتیبانی بگیر.';
    const SMS_TEXT_SECOND = 'پیامک فعال سازی دوم.';
    const SMS_TEXT_FIRST_POLL = 'پیامک نظرسنجی اول';
    const SMS_TEXT_THIRD = 'پیامک فعال سازی سوم.';
    const SMS_TEXT_SECOND_POLL = 'پیامک نظرسنجی دوم';
    const SMS_TEXT_NPS = 'متن پیامک NPS';
    const SMS_TEXT_REFERRAL = 'متن پیامک ریفرال';

    public static function getStates($state = null)
    {
        $data = collect([
            [
                'name' => 'فعال',
                'value' => self::STATE_ACTIVE_USER,
            ],
            [
                'name' => 'پیامک اول',
                'value' => self::STATE_FIRST_SMS,
            ],
            [
                'name' => 'تماس اول',
                'value' => self::STATE_FIRST_CALL
            ],
            [
                'name' => 'پیامک دوم',
                'value' => self::STATE_SECOND_SMS,
            ],
            [
                'name' => 'تماس دوم',
                'value' => self::STATE_SECOND_CALL
            ],
            [
                'name' => 'پیامک سوم',
                'value' => self::STATE_THIRD_SMS,
            ],
            [
                'name' => 'تماس سوم',
                'value' => self::STATE_THIRD_CALL
            ],
            [
                'name' => 'کاربر مرده در تلاش اول',
                'value' => self::STATE_FIRST_ATTEMPT_DIE,
            ],
            [
                'name' => 'کاربر مرده در تلاش دوم',
                'value' => self::STATE_SECOND_ATTEMPT_DIE,
            ],
            [
                'name' => 'کاربر مرده در تلاش سوم',
                'value' => self::STATE_THIRD_ATTEMPT_DIE,
            ],
        ]);

        if ($state !== null) {
            $data = $data->where('value', $state)->first();
        }

        return $data;
    }
}
