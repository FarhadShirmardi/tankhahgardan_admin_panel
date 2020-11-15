<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $connection = 'mysql';

    protected $fillable = [
        'name',
        'state',
        'user_id',
        'panel_user_id',
        'email',
        'date',
        'response_date',
        'source',
        'user_id',
        'feedback_title_id',
        'phone_number',
        'text',
        'response',
    ];
}
