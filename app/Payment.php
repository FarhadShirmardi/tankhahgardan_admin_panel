<?php

namespace App;

use App\Helpers\Helpers;
use App\Payment as Payments;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Payment extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql';

    protected $fillable = [
        'amount',
        'description',
        'date',
        'imprest_id',
        'project_id',
        'payment_subject',
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

    public function sentPayment()
    {
        return $this->hasOne(SentPayment::class, 'source_id', 'id');
    }
}
