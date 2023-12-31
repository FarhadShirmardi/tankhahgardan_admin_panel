<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    protected $connection = 'mysql';

    protected $fillable = [
        'name',
        'title',
        'guard',
    ];
}
