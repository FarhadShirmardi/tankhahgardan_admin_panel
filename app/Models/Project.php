<?php

namespace App\Models;

use App\Enums\ProjectUserTypeEnum;
use App\Services\ProjectReportService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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

    public function projectReport(): HasOne
    {
        return $this->hasOne(ProjectReport::class, 'id');
    }

    public function owner(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->using(ProjectUser::class)
            ->as('projectUser')
            ->with('userStatuses')
            ->wherePivot('user_type', ProjectUserTypeEnum::OWNER)
            ->withPivot(['id', 'user_type', 'state'])->withTimestamps();
    }

    public function getOwner()
    {
        $this->loadMissing('owner');

        return collect($this->owner)->first();
    }

    public function updateProjectReport()
    {
        $this->projectReport()->update(ProjectReportService::getSingleProject($this->id)->toArray());
    }
}
