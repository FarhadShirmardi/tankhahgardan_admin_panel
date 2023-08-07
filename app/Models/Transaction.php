<?php

namespace App\Models;

use App\Enums\BankEnum;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $connection = 'mysql';

    protected $casts = [
        'bank_id' => BankEnum::class
    ];
}
