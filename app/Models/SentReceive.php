<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SentReceive extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'source_id',
        'amount',
        'description',
        'date',
        'imprest_id',
        'project_id',
        'receive_subject',
        'creator_user_id'
    ];
}
