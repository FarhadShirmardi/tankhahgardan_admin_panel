<?php

namespace App\Enums;

enum UserStatusTypeEnum: int
{
    case FAILED = 0;
    case SUCCEED = 1;
    case PENDING = 2;

    public function isFailed(): bool
    {
        return $this == self::FAILED;
    }

    public function isPending(): bool
    {
        return $this == self::PENDING;
    }
}
