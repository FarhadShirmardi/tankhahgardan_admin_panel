<?php

namespace App;

use App\Helpers\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReminderState extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'date',
        'done_date',
    ];

    public function setDateAttribute($value)
    {
        if (!$value) {
            return;
        }
        $this->attributes['date'] = Helpers::jalaliDateStringToGregorian($value);
    }

    public function getDateAttribute($value)
    {
        if (!$value) {
            return $value;
        }
        return Helpers::gregorianDateStringToJalali($value);
    }

    public function getDoneDateAttribute($value)
    {
        if (!$value) {
            return $value;
        }
        return Helpers::gregorianDateStringToJalali($value);
    }
}
