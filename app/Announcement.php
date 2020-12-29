<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $connection = 'mysql';

    protected $fillable = [
        'title',
        'text',
        'summary',
        'icon_path',
        'image_path',
        'gif_path',
        'link_type',
        'external_link',
        'button_name',
        'button_link',
        'expire_at',
        'send_at',
        'user_type',
        'panel_user_id'
    ];

    public function announcementUser()
    {
        return $this->hasMany(AnnouncementUser::class);
    }
}
