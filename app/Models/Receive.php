<?php

namespace App\Models;

use App\Traits\ProjectUserFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receive extends Model
{
    use SoftDeletes, ProjectUserFilter;

    protected $connection = 'mysql';
}
