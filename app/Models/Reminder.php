<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reminder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'creator_user_id',
    ];

    public function note(): HasOne
    {
        return $this->hasOne(Note::class, 'id', 'note_id');
    }

    public function intervals(): HasMany
    {
        return $this->hasMany(ReminderInterval::class, 'reminder_id', 'id');
    }
}
