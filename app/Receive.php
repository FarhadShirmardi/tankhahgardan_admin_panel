<?php

namespace App;

use App\Helpers\Helpers;
use App\Receive as Receives;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;


class Receive extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql';

    protected $fillable = [
        'amount',
        'description',
        'date',
        'imprest_id',
        'project_id',
        'receive_subject',
        'creator_user_id'
    ];

    protected $casts = [
        'amount' => 'double'
    ];

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = Helpers::jalaliDateStringToGregorian($value);
    }

    public function getDateAttribute($value)
    {
        return Helpers::gregorianDateStringToJalali($value);
    }

    public function turnoverDetails()
    {
        return $this->hasMany(TurnoverDetail::class);
    }

    public function sentTurnoverDetails()
    {
        return $this->hasMany(SentTurnoverDetail::class);
    }

    public function images()
    {
        return $this->morphMany(
            Image::class,
            'hasImage',
            'model_type',
            'model_id'
        );
    }

    public function imprest()
    {
        return $this->hasOne(Imprest::class, 'id', 'imprest_id');
    }

    public function sentReceive()
    {
        return $this->hasOne(SentReceive::class, 'source_id', 'id');
    }
}
