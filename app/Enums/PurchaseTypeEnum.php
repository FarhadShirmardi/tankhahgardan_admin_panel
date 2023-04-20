<?php

namespace App\Enums;

enum PurchaseTypeEnum: int
{
    case NEW = 1;
    case UPGRADE = 2;
    case EXTEND = 3;

    public function isNew(): bool
    {
        return $this == self::NEW;
    }

    public function isUpgrade(): bool
    {
        return $this == self::UPGRADE;
    }

    public function isExtend(): bool
    {
        return $this == self::EXTEND;
    }
}
