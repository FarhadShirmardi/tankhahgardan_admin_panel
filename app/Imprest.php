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


class Imprest extends Model
{
    use SoftDeletes;

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
