<?php

namespace App\Enums;

use App\Traits\HasColumnValues;
use Illuminate\Support\Collection;

enum ProjectTypeEnum: int
{
    use HasColumnValues;

    case BUILDING = 1;
    case BUSINESS = 2;
    case PRODUCTION = 3;
    case SERVICES = 4;
    case OTHER = 5;

    public static function list(): Collection
    {
        return collect(self::cases())
            ->map(fn (self $item) => self::convertItem($item));
    }

    public function description(): string
    {
        return match ($this) {
            self::BUILDING => __('names.project_types.building'),
            self::BUSINESS => __('names.project_types.business'),
            self::PRODUCTION => __('names.project_types.production'),
            self::SERVICES => __('names.project_types.services'),
            self::OTHER => __('names.project_types.other'),
        };
    }

    public function item(): array
    {
        return self::convertItem($this);
    }

    private static function convertItem(self $item): array
    {
        return [
            'id' => $item->value,
            'text' => $item->text(),
        ];
    }
}
