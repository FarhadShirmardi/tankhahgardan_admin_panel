<?php

namespace App;

use App\Helpers\Helpers;
use App\Note as Notes;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Builder as Builders;
use Illuminate\Support\Carbon;


class Note extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql';

    protected $fillable = [
        'text',
        'project_id',
        'creator_user_id',
        'date',
        'is_done',
        'related_user_id',
        'reminder_id',
        'description',
        'reminder_id',
    ];

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = Helpers::jalaliDateStringToGregorian($value);
    }

    public function getDateAttribute($value)
    {
        return Helpers::gregorianDateStringToJalali($value);
    }

    public function reminder()
    {
        return $this->hasOne(Reminder::class, 'id', 'reminder_id');
    }
}
