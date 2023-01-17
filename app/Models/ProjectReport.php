<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectReport extends Model
{
    protected $connection = 'mysql_panel';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'province_id',
        'city_id',
        'max_time',
        'user_count',
        'active_user_count',
        'payment_count',
        'receive_count',
        'imprest_count',
        'project_type',
    ];
}
