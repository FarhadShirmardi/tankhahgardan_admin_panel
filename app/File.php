<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'path',
        'tag',
        'model_id',
        'type',
        'model_update_time',
        'project_id',
        'creator_user_id'
    ];
}
