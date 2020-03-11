<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectInviteNotification extends Model
{
    protected $primaryKey = 'project_id';

    protected $fillable = [
        'project_id',
        'user_id',
        'type'
    ];
}
