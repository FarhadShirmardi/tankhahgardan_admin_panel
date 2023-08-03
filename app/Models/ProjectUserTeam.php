<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Kirschbaum\PowerJoins\PowerJoins;

class ProjectUserTeam extends Pivot
{
    protected $connection = 'mysql';

    protected $table = 'project_user_team';

    protected $fillable = [
        'team_id',
        'project_user_id',
        'account_title_permission',
        'cost_center_permission',
        'hashtag_permission',
        'contact_permission',
        'imprest_finalized_permission',
        'admin_transaction_add_permission',
        'admin_transaction_edit_permission',
        'admin_transaction_delete_permission',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function projectUser(): BelongsTo
    {
        return $this->belongsTo(ProjectUser::class);
    }

    public function scopeByTeam(Builder $query, Team $team): Builder
    {
        return $query->where('team_id', $team->id);
    }
}
