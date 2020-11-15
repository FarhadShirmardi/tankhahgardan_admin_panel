<?php

namespace App;

use App\Helpers\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SentReceive extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'source_id',
        'amount',
        'description',
        'date',
        'imprest_id',
        'project_id',
        'receive_subject',
        'creator_user_id'
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
        return $this->hasMany(SentTurnoverDetail::class, 'receive_id', 'id');
    }

    public function images()
    {
        return $this->morphMany(
            SentImage::class,
            'hasImage',
            'model_type',
            'model_id'
        );
    }

    public function imprest()
    {
        return $this->hasOne(SentImprest::class, 'id', 'imprest_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'creator_user_id', 'id');
    }
}
