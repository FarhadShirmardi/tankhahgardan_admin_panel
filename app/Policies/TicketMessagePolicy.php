<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\PanelUser;
use App\Models\Ticket;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketMessagePolicy
{
    use HandlesAuthorization;

    public function viewAny(PanelUser $user): bool
    {
    }

    public function view(PanelUser $user, Ticket $ticket): bool
    {
    }

    public function create(PanelUser $user): bool
    {
        return $user->hasPermissionTo(PermissionEnum::RESPONSE_TICKET->value);
    }

    public function update(PanelUser $user, Ticket $ticket): bool
    {
        return $user->hasPermissionTo(PermissionEnum::RESPONSE_TICKET->value);
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
