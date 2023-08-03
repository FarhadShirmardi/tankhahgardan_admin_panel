<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserReport extends Model
{
    protected $connection = 'mysql_panel';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'phone_number',
        'registered_at',
        'payment_count',
        'receive_count',
        'imprest_count',
        'file_count',
        'image_count',
        'image_size',
        'device_count',
        'step_by_step',
        'project_count',
        'own_project_count',
        'max_time',
        'user_type',
        'user_state',
        'ticket_count',
    ];
}
