<?php

namespace App;

use App\Helpers\Helpers;
use Illuminate\Database\Eloquent\Model;

class ApplicationVersion extends Model
{
    protected $casts = [
        'features' => 'array'
    ];

    public function setReleaseDateAttribute($value)
    {
        $this->attributes['release_date'] = Helpers::jalaliDateStringToGregorian($value);
    }

    public function getReleaseDateAttribute($value)
    {
        return Helpers::gregorianDateStringToJalali($value);
    }
}
