<?php

namespace App\Policies;

use App\Enums\PanelUserTypeEnum;
use App\Enums\PermissionEnum;
use App\Models\PanelUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class PanelUserPolicy
{
    use HandlesAuthorization;

    public function viewAny(PanelUser $user): bool
    {
        return $user->hasPermissionTo(PermissionEnum::EDIT_USER_PANELS->value);
    }
}
