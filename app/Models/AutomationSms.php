<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutomationSms extends Model
{
    protected $connection = 'mysql_panel';
    public $timestamps = false;
    protected $fillable = [
        'type',
        'sms_text',
        'sent_time',
    ];
}
