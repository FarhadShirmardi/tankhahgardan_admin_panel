<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class PanelUser extends Authenticatable
{
    use Notifiable;

    protected $connection = 'mysql_panel';
    protected $table = 'users';
}
