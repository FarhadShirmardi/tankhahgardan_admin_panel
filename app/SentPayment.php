<?php

namespace App;

use App\Helpers\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SentPayment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'source_id',
        'amount',
        'description',
        'creator_user_id',
        'date',
        'imprest_id',
        'project_id',
        'payment_subject'
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
        return $this->hasMany(SentTurnoverDetail::class, 'payment_id', 'id');
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
