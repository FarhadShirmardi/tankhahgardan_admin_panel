<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogCenter extends Model
{
    public $timestamps = false;
    protected $connection = 'mysql_panel';

    protected $fillable = [
        'panel_user_id',
        'user_id',
        'type',
        'date_time',
        'description',
        'old_json',
        'new_json'
    ];
}
