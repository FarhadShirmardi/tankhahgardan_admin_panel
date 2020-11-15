<?php

namespace App;

use App\Helpers\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SentImprest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'imprest_number',
        'source_id',
        'start_date',
        'end_date',
        'project_id',
        'creator_user_id',
        'user_id',
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

    public function user()
    {
        return $this->belongsTo(User::class, 'creator_user_id', 'id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function imprest()
    {
        return $this->hasOne(Imprest::class, 'id', 'source_id');
    }

    public function payments()
    {
        return $this->hasMany(SentPayment::class, 'imprest_id', 'id');
    }

    public function receives()
    {
        return $this->hasMany(SentReceive::class, 'imprest_id', 'id');
    }
}
