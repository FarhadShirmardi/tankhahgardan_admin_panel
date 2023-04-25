<?php

namespace App\Enums;

use App\Traits\HasColumnValues;

enum ProjectUserTypeEnum: int
{
    use HasColumnValues;

    case OWNER = 1;
    case ADMIN = 2;
    case MANAGER = 3;
    case CUSTODIAN = 4;

    public function description(): string
    {
        return match ($this) {
            self::OWNER => __('names.project_user_type.owner'),
            self::ADMIN => __('names.project_user_type.admin'),
            self::MANAGER => __('names.project_user_type.manager'),
            self::CUSTODIAN => __('names.project_user_type.custodian'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::OWNER => 'success',
            self::ADMIN => 'warning',
            self::MANAGER => 'primary',
            self::CUSTODIAN => 'secondary',
        };
    }

    public function isCustodian(): bool
    {
        return $this == self::CUSTODIAN;
    }
}
