<?php

namespace App\Traits;

use App\Models\ProjectUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait ProjectUserFilter
{
    public function projectUser(): BelongsTo
    {
        return $this->belongsTo(ProjectUser::class);
    }

    public function byProjectUser(Builder $query, ProjectUser $projectUser): Builder
    {
        return $query->where('project_user_id', $projectUser->id);
    }

    public function byProjectUserId(Builder $query, int $projectUserId): Builder
    {
        return $query->where('project_user_id', $projectUserId);
    }
}
