<?php

namespace App;

use App\Helpers\Helpers;
use App\Imprest as Imprests;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Imprest
 *
 * @property int $id
 * @property int $imprest_number
 * @property int $state
 * @property string $start_date
 * @property string $end_date
 * @property string|null $description
 * @property int $project_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read Collection|Payment[] $payments
 * @property-read Collection|Receive[] $receives
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|Imprests onlyTrashed()
 * @method static bool|null restore()
 * @method static Builder|Imprests whereCreatedAt($value)
 * @method static Builder|Imprests whereDeletedAt($value)
 * @method static Builder|Imprests whereDescription($value)
 * @method static Builder|Imprests whereEndDate($value)
 * @method static Builder|Imprests whereId($value)
 * @method static Builder|Imprests whereImprestNumber($value)
 * @method static Builder|Imprests whereProjectId($value)
 * @method static Builder|Imprests whereStartDate($value)
 * @method static Builder|Imprests whereState($value)
 * @method static Builder|Imprests whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Imprests withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Imprests withoutTrashed()
 * @mixin Eloquent
 */
class Imprest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'imprest_number',
        'start_date',
        'end_date',
        'project_id',
        'creator_user_id',
        'state',
        'description'
    ];

    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] = Helpers::jalaliDateStringToGregorian($value);
    }

    public function getStartDateAttribute($value)
    {
        return Helpers::gregorianDateStringToJalali($value);
    }

    public function setEndDateAttribute($value)
    {
        $this->attributes['end_date'] = Helpers::jalaliDateStringToGregorian($value);
    }

    public function getEndDateAttribute($value)
    {
        return Helpers::gregorianDateStringToJalali($value);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function receives()
    {
        return $this->hasMany(Receive::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function sentImprest()
    {
        return $this->hasOne(SentImprest::class, 'source_id', 'id');
    }
}
