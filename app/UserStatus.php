<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserStatus extends Model
{
    protected $connection = 'mysql';
    use SoftDeletes;

    protected $fillable = [
        'start_date',
        'end_date',
        'volume_size',
        'user_count',
        'price_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
