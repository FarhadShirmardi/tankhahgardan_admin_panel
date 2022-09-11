<?php

namespace App\Models;

use App\Traits\DateCaster;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
    use SoftDeletes, DateCaster;

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

    public function reminder(): HasOne
    {
        return $this->hasOne(Reminder::class, 'id', 'reminder_id');
    }
}
