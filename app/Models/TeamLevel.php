<?php

namespace App\Models;

use App\Enums\TeamLevelStateEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kirschbaum\PowerJoins\PowerJoins;

class TeamLevel extends Model
{
    protected $connection = 'mysql';

    protected $fillable = [
        'level',
        'name',
        'state',
    ];
}
