<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\PanelUser;
use App\Models\Ticket;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketPolicy
{
    use HandlesAuthorization;

    public function viewAny(PanelUser $user): bool
    {
        return $user->hasPermissionTo(PermissionEnum::VIEW_TICKET->value);
    }

    public function view(PanelUser $user, Ticket $ticket): bool
    {
        return $user->hasPermissionTo(PermissionEnum::VIEW_TICKET->value);
    }

    public function create(PanelUser $user): bool
    {
        return $user->hasPermissionTo(PermissionEnum::RESPONSE_TICKET->value);
    }

    public function update(PanelUser $user, Ticket $ticket): bool
    {
        return true;
    }

    public function delete(PanelUser $user, Ticket $ticket): bool
    {
    }

    public function restore(PanelUser $user, Ticket $ticket): bool
    {
    }

    public function forceDelete(PanelUser $user, Ticket $ticket): bool
    {
    }
}
