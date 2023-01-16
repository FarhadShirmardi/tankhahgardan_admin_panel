<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Kirschbaum\PowerJoins\PowerJoins;

class TeamLevelUser extends Model
{
    protected $connection = 'mysql';

    protected $fillable = [
        'project_user_id',
    ];

    public function user(): HasOneThrough
    {
        return $this->hasOneThrough(
            User::class,
            ProjectUser::class,
            'id',
            'id',
            'project_user_id',
            'user_id'
        );
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(TeamLevel::class, 'team_level_id', 'id');
    }
}
