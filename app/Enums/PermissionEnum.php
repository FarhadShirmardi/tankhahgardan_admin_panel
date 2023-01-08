<?php

namespace App\Enums;

enum PermissionEnum: string
{
    case VIEW_TICKET = 'view_ticket';
    case RESPONSE_TICKET = 'response_ticket';
}
