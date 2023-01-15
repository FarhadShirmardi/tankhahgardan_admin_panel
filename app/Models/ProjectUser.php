<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectUser extends Pivot
{
    use SoftDeletes;

    protected $connection = 'mysql';

    protected $table = 'project_user';

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'project_user_id');
    }

    public function receives(): HasMany
    {
        return $this->hasMany(Receive::class, 'project_user_id');
    }
}
