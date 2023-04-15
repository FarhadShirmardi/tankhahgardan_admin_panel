<?php

namespace App\Data;

use App\Enums\PremiumDurationEnum;
use Spatie\LaravelData\Data;

class PremiumDurationData extends Data
{
    public int $id;
    public string $title;

    public function __construct(
        private readonly PremiumDurationEnum $duration,
        public bool $is_gift = false,
        public int $real_price = 0,
        public int $price = 0
    ) {
        $this->id = $this->duration->value;
        $this->title = $this->duration->getTitle();
    }
}
