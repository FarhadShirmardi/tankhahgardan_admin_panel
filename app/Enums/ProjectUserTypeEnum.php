<?php

namespace App\Enums;

enum ProjectUserTypeEnum: int
{
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

    public static function columnValues(): array
    {
        return collect(self::cases())
            ->mapWithKeys(function (self $enum) {
                return [$enum->value => $enum->description()];
            })->toArray();
    }
}
