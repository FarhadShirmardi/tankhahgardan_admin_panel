<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Team extends Model
{
    protected $connection = 'mysql';

    protected $fillable = ['name', 'is_default'];

    public function projectUserTeams(): HasMany
    {
        return $this->hasMany(ProjectUserTeam::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function teamLevels(): HasMany
    {
        return $this->hasMany(TeamLevel::class);
    }

    public function maxTeamLevel(): HasOne
    {
        return $this->hasOne(TeamLevel::class)->latestOfMany('level');
    }

    public function teamLevelUsers(): HasManyThrough
    {
        return $this->hasManyThrough(TeamLevelUser::class, TeamLevel::class);
    }
}
