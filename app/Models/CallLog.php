<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CallLog extends Model
{
    public $timestamps = false;
    protected $connection = 'mysql_panel';

    protected $fillable = [
        'panel_user_id',
        'text',
        'date'
    ];

    public function panelUser(): BelongsTo
    {
        return $this->belongsTo(PanelUser::class);
    }
}
