<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    protected $connection = 'mysql';

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'count',
        'symbol',
    ];

    public function promoCodes(): HasMany
    {
        return $this->hasMany(PromoCode::class);
    }
}
