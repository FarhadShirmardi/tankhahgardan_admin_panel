<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SentImage extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql';

    protected $fillable = [
        'path',
        'size',
        'user_id',
        'project_id',
    ];
}
