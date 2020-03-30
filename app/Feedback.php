<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{

    protected $connection = 'mysql';

    protected $fillable = [
        'user_id',
        'feedback_title_id',
        'text',
        'device_id',
        'application_version'
    ];

    public function feedbackTitles()
    {
        return $this->hasOne(FeedbackTitle::class, 'id', 'feedback_title_id');
    }

    public function feedbackResponse()
    {
        return $this->hasOne(FeedbackResponse::class, 'id', 'feedback_response_id');
    }

    public function images()
    {
        return $this->morphMany(
            Image::class,
            'hasImage',
            'model_type',
            'model_id'
        );
    }

    public function device()
    {
        return $this->hasOne(Device::class);
    }
}
