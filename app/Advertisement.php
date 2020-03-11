<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $fillable = [
        'id',
        'title',
        'text',
        'link',
        'expire_time',
        'panel_user_id'
    ];
}
