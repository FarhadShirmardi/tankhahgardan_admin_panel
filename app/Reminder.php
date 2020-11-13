<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reminder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'creator_user_id',
    ];

    public function note()
    {
        return $this->hasOne(Note::class, 'id', 'note_id');
    }

    public function intervals()
    {
        return $this->hasMany(ReminderInterval::class, 'reminder_id', 'id');
    }

    public function reminderState()
    {
        return $this->hasMany(ReminderState::class, 'reminder_id', 'id');
    }
}
