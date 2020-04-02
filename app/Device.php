<?php

namespace App;

use App\Device as Devices;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

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
