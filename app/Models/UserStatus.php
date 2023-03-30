<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserStatus extends Model
{
    protected $connection = 'mysql';

    public function premiumPlan(): BelongsTo
    {
        return $this->belongsTo(PremiumPlan::class);
    }
}
