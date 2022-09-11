<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserStatusLog extends Model
{
    protected $connection = 'mysql';
    protected $fillable = [
        'transaction_id',
        'status',
        'start_date',
        'end_date',
        'volume_size',
        'user_count',
        'total_amount',
        'discount_amount',
        'added_value_amount',
        'price_id',
        'promo_code_id',
        'campaign_id',
        'type'
    ];
    protected $with = ['transaction'];

    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class, 'id', 'transaction_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
