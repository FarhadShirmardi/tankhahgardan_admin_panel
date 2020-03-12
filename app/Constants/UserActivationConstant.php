<?php

namespace App\Http\Controllers\Api\V1\Constants;

class UserActivationConstant
{
    const STATE_ACTIVE_USER = 0;
    const STATE_FIRST_SMS = 1;
    const STATE_FIRST_CALL = 2;
    const STATE_SECOND_SMS = 3;
    const STATE_SECOND_CALL = 4;
    const STATE_THIRD_SMS = 5;
    const STATE_THIRD_CALL = 4;

    const STATE_FIRST_ATTEMPT_DIE = 1000;
    const STATE_SECOND_ATTEMPT_DIE = 2000;
    const STATE_THIRD_ATTEMPT_DIE = 3000;

    const SMS_TEXT_FIRST = 'بیا آموزش ببین و پشتیبانی بگیر.';
}
