<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StepByStep extends Model
{

    protected $connection = 'mysql';

    protected $fillable = [
        'code',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
