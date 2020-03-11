<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SyncLog extends Model
{
    protected $fillable = [
        'user_id',
        'project_id',
        'data',
        'platform',
        'version'
    ];
}
