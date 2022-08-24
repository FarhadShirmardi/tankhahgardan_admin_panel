<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TurnoverDetail extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'amount',
        'description',
        'account_title_id'
    ];

    protected $casts = [
        'amount' => 'double'
    ];
}
