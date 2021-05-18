<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PanelFile extends Model
{
    protected $connection = 'mysql_panel';
    protected $table = 'files';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'path',
        'description',
        'date_time',
    ];
}
