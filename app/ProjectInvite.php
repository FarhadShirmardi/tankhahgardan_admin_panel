<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectInvite extends Model
{
    protected $fillable = [
        'user_id',
        'count',
        'token',
        'phone_number'
    ];

    protected $guarded = ['id'];
}
