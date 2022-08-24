<?php

namespace App\Models;

use App\Traits\DateCaster;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Memo extends Model
{
    use SoftDeletes, DateCaster;

    protected $fillable = [
        'text',
        'project_id',
        'creator_user_id',
        'date',
    ];
}
