<?php

namespace App\Enums;

enum SmsTemplateEnum: string
{
    case TICKET_RESPONSE = 'ticket-response';

    case PROMO_CODE = 'discount-code';
}
