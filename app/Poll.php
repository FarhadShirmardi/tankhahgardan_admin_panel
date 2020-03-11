<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['state']);
    }
}
