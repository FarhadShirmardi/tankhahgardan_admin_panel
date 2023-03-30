<?php

namespace App\Enums;

use App\Traits\HasColumnValues;

enum PlatformEnum: int
{
    use HasColumnValues;

    case ANDROID = 1;
    case WEB = 3;
    case WEB_APP = 5;

    public function description(): string
    {
        return match ($this) {
            self::ANDROID => __('names.platforms.android'),
            self::WEB => __('names.platforms.web'),
            self::WEB_APP => __('names.platforms.web-app'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::ANDROID => 'success',
            self::WEB => 'warning',
            self::WEB_APP => 'secondary',
        };
    }
}
