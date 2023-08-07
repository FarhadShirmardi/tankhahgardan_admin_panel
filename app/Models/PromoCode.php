<?php

namespace App\Models;

use App\Enums\PremiumDurationEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromoCode extends Model
{
    protected $connection = 'mysql';

    protected $fillable = [
        'campaign_id',
        'code',
        'text',
        'discount_percent',
        'max_discount',
        'max_count',
        'user_id',
        'start_at',
        'expire_at',
        'duration_id',
    ];

    protected $casts = [
        'duration_id' => PremiumDurationEnum::class,
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function panelUser(): BelongsTo
    {
        return $this->belongsTo(PanelUser::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
