<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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

    public function promoCodes()
    {
        return $this->hasMany(PromoCode::class);
    }
}
