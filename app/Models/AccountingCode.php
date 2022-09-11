<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountingCode extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type',
        'code',
        'level',
    ];
}
