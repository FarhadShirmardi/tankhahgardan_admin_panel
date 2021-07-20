<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MonthlyReport extends Model
{
    protected $fillable = [
        'year',
        'month',
        'new_user_data',
        'old_user_data',
    ];
}
