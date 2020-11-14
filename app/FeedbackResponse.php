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
}
