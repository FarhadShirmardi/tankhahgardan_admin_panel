<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasPermissions;

class PanelUser extends Authenticatable implements FilamentUser
{
    use HasPermissions;

    public function canAccessFilament(): bool
    {
        return true;
    }
}
