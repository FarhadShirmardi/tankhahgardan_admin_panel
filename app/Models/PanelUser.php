<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class PanelUser extends Authenticatable implements FilamentUser
{
    protected $connection = 'mysql';
    
    use HasRoles;

    protected $fillable = [
        'name',
        'phone_number',
        'password'
    ];

    protected $hidden = [
        'password',
    ];

    public function canAccessFilament(): bool
    {
        return true;
    }
}
