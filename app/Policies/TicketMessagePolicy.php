<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\PanelUser;
use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketMessagePolicy
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

    public function update(PanelUser $user, TicketMessage $ticketMessage): bool
    {
        return $user->hasPermissionTo(PermissionEnum::RESPONSE_TICKET->value);
    }

    public function delete(PanelUser $user, TicketMessage $ticketMessage): bool
    {
    }

    public function restore(PanelUser $user, TicketMessage $ticketMessage): bool
    {
    }

    public function forceDelete(PanelUser $user, TicketMessage $ticketMessage): bool
    {
    }
}
