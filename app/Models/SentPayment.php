<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SentPayment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'source_id',
        'amount',
        'description',
        'creator_user_id',
        'date',
        'imprest_id',
        'project_id',
        'payment_subject'
    ];
}
