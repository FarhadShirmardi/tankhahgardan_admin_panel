<?php

namespace App\Constants;

use App\Http\Controllers\Api\V2\Constants\NotificationExpireTime;
use App\Project;
use App\User;

class NotificationType
{
    public const OPEN_APP = 100;
    public const WARN_OWNER_PREMIUM = 111;
    public const NEAR_EXPIRE = 112;

    public const FEEDBACK_RESPONSE = 121;

    public const CHAGE_WALLET = 131;

    public const OPEN_NOTIFICATION = 200;

    public const SEND_IMPREST = 211;
    public const REQUEST_IMPREST_RETURN = 212;
    public const RETURN_IMPREST = 213;

    public const ASSIGN_NOTE = 221;
    public const CANCEL_NOTE = 222;
    public const EDIT_NOTE = 223;
    public const DELETE_NOTE = 224;

    public const INVITE_PROJECT = 231;
    public const ACCEPT_INVITE_PROJECT = 232;
    public const REJECT_INVITE_PROJECT = 232;

    public const FILE_READY = 241;

    public const PROMO_CODE = 251;

    public const ANNOUCEMENT = 261;
}
