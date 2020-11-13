<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class PanelUser extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $connection = 'mysql_panel';
    protected $table = 'users';

    protected $fillable = [
        'name',
        'phone_number',
        'password',
        'type'
    ];
}
