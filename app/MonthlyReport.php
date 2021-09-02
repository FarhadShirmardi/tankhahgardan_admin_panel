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
        'user_return_data',
        'active_user_counts',
        'user_assessment_data',
    ];
}
