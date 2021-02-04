<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $connection = 'mysql';
    protected $fillable = [
        'title',
        'button_name',
        'button_link',
        'image_path',
        'expire_at',
        'start_at',
        'panel_user_id'
    ];

    public function user()
    {
        return $this->hasMany(BannerUser::class, 'banner_id', 'id');
    }
}
