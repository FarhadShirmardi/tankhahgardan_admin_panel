<?php

namespace App\Models;

use App\Enums\ProjectUserStateEnum;
use App\Enums\ProjectUserTypeEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectUser extends Pivot
{
    use SoftDeletes;

    protected $connection = 'mysql';

    protected $table = 'project_user';

    protected $casts = [
        'user_type' => ProjectUserTypeEnum::class,
    ];

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'project_user_id');
    }

    public function receives(): HasMany
    {
        return $this->hasMany(Receive::class, 'project_user_id');
    }

    public function isCustodian(): bool
    {
        return $this->user_type->isCustodian();
    }

    public function getProjectTeamText(): string
    {
        $project = $this->getProject();

        if ($this->isCustodian()) {
            /** @var Team $team */
            $team = $this->getTeam();

            return $project->name.((!$team or $team->is_default) ? '' : "($team->name)");
        }
        return $project->name.' ('.__('names.management panel').')';
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function getProject(): Project
    {
        $this->loadMissing('project');

        return $this->project;
    }

    public function team(): HasOneThrough
    {
        return $this->hasOneThrough(
            Team::class,
            ProjectUserTeam::class,
            'project_user_id',
            'id',
            'id',
            'team_id'
        );
    }

    public function getTeam()
    {
        $this->loadMissing('team');

        return $this->team;
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(
            Team::class,
            foreignPivotKey: 'project_user_id'
        )
            ->as('team')
            ->using(ProjectUserTeam::class)
            ->withPivot([
                'id',
                'account_title_permission',
                'cost_center_permission',
                'hashtag_permission',
                'contact_permission',
                'imprest_finalized_permission',
                'admin_transaction_add_permission',
                'admin_transaction_edit_permission',
                'admin_transaction_delete_permission',
            ])
            ->withTimestamps();
    }

    public function scopeIsActive(Builder $query): Builder
    {
        return $query->where('state', ProjectUserStateEnum::ACTIVE);
    }
}
