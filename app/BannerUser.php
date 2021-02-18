<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BannerUser extends Model
{
    protected $connection = 'mysql';
    protected $fillable = [
        'banner_id',
        'user_id'
    ];
}
