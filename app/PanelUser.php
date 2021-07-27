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
        'type',
    ];

    public function files()
    {
        return $this->hasMany(PanelFile::class, 'user_id', 'id')->orWhere('user_id', 0);
    }

    public function logs()
    {
        return $this->hasMany(PanelLogCenter::class, 'panel_user_id', 'id');
    }
}
