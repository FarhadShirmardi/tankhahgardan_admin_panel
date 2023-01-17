<?php

namespace App\Models;

use App\Enums\ProjectUserStateEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
