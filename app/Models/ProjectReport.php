<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProjectReport extends Model
{
    protected $connection = 'mysql_panel';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'type',
        'province_id',
        'city_id',
        'max_time',
        'user_count',
        'active_user_count',
        'payment_count',
        'receive_count',
        'imprest_count',
        'project_type',
    ];

    public function province(): HasOne
    {
        return $this->hasOne(Province::class, 'id', 'province_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
