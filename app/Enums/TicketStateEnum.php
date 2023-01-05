<?php

namespace App\Enums;

enum TicketStateEnum: int
{
    case CLOSED = 1;
    case OPENED = 2;
    case PENDING = 3;
    case ANSWERED = 4;

    public function description(): string
    {
        return match ($this) {
            self::CLOSED => __('names.ticket state.closed'),
            self::OPENED => __('names.ticket state.opened'),
            self::PENDING => __('names.ticket state.pending'),
            self::ANSWERED => __('names.ticket state.answered'),
        };
    }
}
