<?php

namespace App\Http\Livewire\UserResource;

use App\Filament\Resources\TicketResource;
use App\Models\Ticket;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class TicketsTable extends UserDetailTable
{
    public int $ticket_page = 1;

    protected function getTableQuery(): Builder|Relation
    {
        if (!$this->isLoaded) {
            return $this->user->tickets()->whereRaw('false')->getQuery();
        }

        return $this->user->tickets()
            ->orderByDesc('tickets.created_at')
            ->getQuery();
    }

    protected function getDefaultTableRecordsPerPageSelectOption(): int
    {
        return 5;
    }

    protected function getTablePaginationPageName(): string
    {
        return 'ticket_page';
    }

    protected function getTableColumns(): array
    {
        return TicketResource::getTicketsTableColumns(showUsername: false);
    }

    protected function getTableRecordUrlUsing(): ?Closure
    {
        return fn (Ticket $record): string => TicketResource::getUrl('edit', ['record' => $record]);
    }

    public function render(): View
    {
        return view('livewire.user-resource.tickets-table');
    }
}
