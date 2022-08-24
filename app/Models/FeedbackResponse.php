<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class FeedbackResponse extends Model
{
    protected $connection = 'mysql';

    protected $fillable = [
        'panel_user_id',
        'text',
        'response_updated_at',
        'read_at'
    ];

    public function images(): MorphMany
    {
        return $this->morphMany(
            Image::class,
            'hasImage',
            'model_type',
            'model_id'
        );
    }
}
