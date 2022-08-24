<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $connection = 'mysql';

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
