<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    protected $connection = 'mysql';

    protected $fillable = [
        'code',
        'text',
        'discount_percent',
        'max_discount',
        'max_count',
        'user_id',
        'start_at',
        'expire_at'
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
