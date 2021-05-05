<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AutomationBurntUser extends Model
{
    public $timestamps = false;
    protected $connection = 'mysql_panel';
    protected $fillable = [
        'date',
        'text',
    ];
}
