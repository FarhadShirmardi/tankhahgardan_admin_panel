<?php

namespace App\Models;

use App\Traits\StartEndDateCaster;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Imprest extends Model
{
    use SoftDeletes, StartEndDateCaster;

    protected $connection = 'mysql';

    protected $fillable = [
        'imprest_number',
        'start_date',
        'end_date',
        'project_id',
        'creator_user_id',
        'state',
        'description'
    ];

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function receives(): HasMany
    {
        return $this->hasMany(Receive::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
