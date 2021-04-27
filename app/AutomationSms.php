<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AutomationSms extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'type',
        'sms_text',
        'sent_time',
    ];
}
