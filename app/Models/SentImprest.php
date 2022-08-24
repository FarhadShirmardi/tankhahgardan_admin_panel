<?php

namespace App\Models;

use App\Traits\StartEndDateCaster;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SentImprest extends Model
{
    use SoftDeletes, StartEndDateCaster;

    protected $fillable = [
        'imprest_number',
        'source_id',
        'start_date',
        'end_date',
        'project_id',
        'creator_user_id',
        'user_id',
        'state',
        'description'
    ];
}
