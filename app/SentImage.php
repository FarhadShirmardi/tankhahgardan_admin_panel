<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SentImage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'path',
        'size',
        'user_id',
        'project_id',
    ];

    public function hasImage()
    {
        return $this->morphTo(
            null,
            'model_type',
            'model_id'
        );
    }
}
