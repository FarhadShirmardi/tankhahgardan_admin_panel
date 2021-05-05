<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AutomationData extends Model
{
    protected $connection = 'mysql_panel';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'name',
        'phone_number',
        'registered_at',
        'transaction_count',
        'max_time',
        'automation_state',
        'premium_state',
    ];
}
