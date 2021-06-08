<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AutomationMetric extends Model
{
    protected $connection = 'mysql_panel';
    protected $fillable = [
        'date',
        'metric',
    ];

    protected $casts = [
        'metric' => 'array',
    ];
}
