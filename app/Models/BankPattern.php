<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BankPattern extends Model
{
    protected $casts = [
        'numbers' => 'array',
        'transaction_types' => 'array',
    ];

    public function bank(): HasOne
    {
        return $this->hasOne(Bank::class, 'id', 'bank_id');
    }
}
