<?php

namespace App;

use App\Helpers\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SentTurnoverDetail extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'amount',
        'description',
        'account_title_id'
    ];

    public function accountTitle()
    {
        return $this->hasOne(AccountTitle::class, 'id', 'account_title_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function payments()
    {
        return $this->belongsToMany(SentPayment::class);
    }

    public function receives()
    {
        return $this->belongsToMany(SentReceive::class);
    }

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = Helpers::jalaliDateStringToGregorian($value);
    }

    public function getDateAttribute($value)
    {
        return Helpers::gregorianDateStringToJalali($value);
    }
}
