<?php

namespace App\Models;

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
}
