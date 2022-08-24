<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReminderState extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'date',
        'done_date',
    ];
}
