<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeedbackResponse extends Model
{

    protected $connection = 'mysql';

    protected $fillable = [
        'panel_user_id',
        'text',
        'response_updated_at',
        'read_at'
    ];

    public function panelUser()
    {
        return $this->hasOne(PanelUser::class, 'id', 'panel_user_id');
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
}
