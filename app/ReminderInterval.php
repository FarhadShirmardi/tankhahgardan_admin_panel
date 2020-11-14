<?php

namespace App;

use App\Helpers\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReminderInterval extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type',
        'day',
        'start_date',
        'end_date',
        'title',
        'text',
    ];

    public function setStartDateAttribute($value)
    {
        if (!$value) {
            return;
        }
        $dateTime = explode(' ', $value);
        $this->attributes['start_date'] = Helpers::jalaliDateStringToGregorian($dateTime[0]) . ' ' . $dateTime[1];
    }

    public function getStartDateAttribute($value)
    {
        if (!$value) {
            return $value;
        }
        $dateTime = explode(' ', $value);
        return Helpers::gregorianDateStringToJalali($dateTime[0]) . ' ' . $dateTime[1];
    }

    public function setEndDateAttribute($value)
    {
        if (!$value) {
            return;
        }
        $dateTime = explode(' ', $value);
        $this->attributes['end_date'] = Helpers::jalaliDateStringToGregorian($dateTime[0]) . ' ' . $dateTime[1];
    }

    public function getEndDateAttribute($value)
    {
        if (!$value) {
            return $value;
        }
        $dateTime = explode(' ', $value);
        return Helpers::gregorianDateStringToJalali($dateTime[0]) . ' ' . $dateTime[1];
    }
}
