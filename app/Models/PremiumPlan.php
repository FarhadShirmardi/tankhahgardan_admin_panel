<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PremiumPlan extends Model
{
    protected $connection = 'mysql';
    protected $casts = [
        'features' => 'array',
        'limits' => 'array',
    ];
}
