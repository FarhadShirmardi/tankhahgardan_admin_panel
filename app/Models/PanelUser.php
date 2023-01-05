<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PanelUser extends Authenticatable implements FilamentUser
{
    public function canAccessFilament(): bool
    {
        return true;
    }
}
