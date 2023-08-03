<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamLevel extends Model
{
    protected $connection = 'mysql';

    protected $fillable = [
        'level',
        'name',
        'state',
    ];
}
