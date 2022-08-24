<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'panel_user_id',
        'type'
    ];

    public function user(): HasMany
    {
        return $this->hasMany(BannerUser::class, 'banner_id', 'id');
    }
}
