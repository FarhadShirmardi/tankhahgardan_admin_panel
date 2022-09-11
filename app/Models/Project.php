<?php

namespace App\Models;

use App\Helpers\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql';

    protected $fillable = [
        'name',
        'state_id',
        'city_id'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->with('userStatuses')
            ->withPivot(['id', 'is_owner', 'state', 'expired_date', 'added_date', 'note'])->withTimestamps();
    }

    public function getPremiumStateAttribute(): int
    {
        /** @var User $owner */
        $owner = $this->users()->where('is_owner', true)->first();

        return Helpers::getUserStatus($owner);
    }

    public function state(): HasOne
    {
        return $this->hasOne(State::class, 'id', 'state_id');
    }

    public function city(): HasOne
    {
        return $this->hasOne(City::class, 'id', 'city_id');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function receives(): HasMany
    {
        return $this->hasMany(Receive::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    public function projectUser(): HasMany
    {
        return $this->hasMany(ProjectUser::class, 'project_id', 'id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }
}
