<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectUser extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql';

    protected $table = 'project_user';
}
