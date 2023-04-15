<?php

namespace App\Models;

use App\Enums\PremiumDurationEnum;
use App\Enums\UserStatusTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserStatus extends Model
{
    protected $connection = 'mysql';

    protected $fillable = [
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'type' => UserStatusTypeEnum::class,
        'duration_id' => PremiumDurationEnum::class,
    ];

    public function premiumPlan(): BelongsTo
    {
        return $this->belongsTo(PremiumPlan::class);
    }
}
