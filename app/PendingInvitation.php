<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PendingInvitation extends Model
{
    protected $fillable = [
        'project_id',
        'user_id',
        'phone_number'
    ];
}
