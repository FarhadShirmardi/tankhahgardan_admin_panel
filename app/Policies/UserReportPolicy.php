<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\PanelUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserReportPolicy
{
    use HandlesAuthorization;

    public function viewAny(PanelUser $user): bool
    {
        return $user->hasPermissionTo(PermissionEnum::USER_REPORT->value);
    }
}
