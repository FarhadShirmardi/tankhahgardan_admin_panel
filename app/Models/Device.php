<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $connection = 'mysql';

    protected $fillable = [
        'user_id',
        'serial',
        'model',
        'platform',
        'os_version'
    ];
}
