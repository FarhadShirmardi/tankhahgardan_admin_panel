<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectReport extends Model
{
    protected $fillable = [
        'id',
        'name',
        'state_id',
        'city_id',
        'created_at',
        'user_count',
        'active_user_count',
        'not_active_user_count',
        'payment_count',
        'receive_count',
        'note_count',
        'imprest_count',
        'max_time',
        'type',
        'project_state',
        'project_type',
    ];
}
