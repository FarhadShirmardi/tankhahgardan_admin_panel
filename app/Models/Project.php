<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql';

    public function projectUsers(): HasMany
    {
        return $this->hasMany(ProjectUser::class, 'project_id', 'id');
    }
}
