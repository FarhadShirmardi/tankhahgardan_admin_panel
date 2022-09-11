<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Feedback extends Model
{
    protected $connection = 'mysql';

    protected $fillable = [
        'user_id',
        'feedback_title_id',
        'text',
        'device_id',
        'application_version',
        'state'
    ];

    public function feedbackTitles(): HasOne
    {
        return $this->hasOne(FeedbackTitle::class, 'id', 'feedback_title_id');
    }

    public function feedbackResponse(): HasOne
    {
        return $this->hasOne(FeedbackResponse::class, 'id', 'feedback_response_id');
    }

    public function images(): MorphMany
    {
        return $this->morphMany(
            Image::class,
            'hasImage',
            'model_type',
            'model_id'
        );
    }

    public function device(): HasOne
    {
        return $this->hasOne(Device::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
