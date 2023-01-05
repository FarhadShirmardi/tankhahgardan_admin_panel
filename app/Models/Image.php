<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'path',
        'size',
        'user_id',
        'project_id',
        'model_type',
    ];

    public function hasImage(): MorphTo
    {
        return $this->morphTo(
            null,
            'model_type',
            'model_id'
        );
    }
}
