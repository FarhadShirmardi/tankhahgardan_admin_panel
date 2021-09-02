<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AutomationCall extends Model
{
    public $timestamps = false;
    protected $connection = 'mysql_panel';
    protected $fillable = [
        'text',
        'type',
        'call_time',
        'is_missed_call',
    ];
}
