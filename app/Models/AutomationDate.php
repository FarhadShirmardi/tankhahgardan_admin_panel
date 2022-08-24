<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutomationDate extends Model
{
    public $timestamps = false;
    protected $connection = 'mysql_panel';
    protected $fillable = [
        'date_time',
    ];
}
