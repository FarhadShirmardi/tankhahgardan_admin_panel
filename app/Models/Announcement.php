<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function announcementUser(): HasMany
    {
        return $this->hasMany(AnnouncementUser::class);
    }
}
