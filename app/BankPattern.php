<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankPattern extends Model
{
    protected $casts = [
        'numbers' => 'array',
        'transaction_types' => 'array',
    ];

    public function bank()
    {
        return $this->hasOne(Bank::class, 'id', 'bank_id');
    }
}
