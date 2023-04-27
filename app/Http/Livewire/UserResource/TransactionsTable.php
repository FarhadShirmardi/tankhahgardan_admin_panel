<?php

namespace App\Http\Livewire\UserResource;

use App\Filament\Resources\TransactionResource;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class TransactionsTable extends UserDetailTable
{
    protected function getTableQuery(): Builder|Relation
    {
        if (!$this->isLoaded) {
            return $this->user->userStatusLogs()->whereRaw('false')->getQuery();
        }

        return $this->user->userStatusLogs()
            ->latest('end_date')
            ->getQuery();
    }

    protected function getTableColumns(): array
    {
        return TransactionResource::getColumns(showUsername: false);
    }

    protected function getTableActions(): array
    {
        return TransactionResource::getActions();
    }

    protected function getTableRecordsPerPage(): int
    {
        return 5;
    }

    public function render(): View
    {
        return view('livewire.user-resource.transactions-table');
    }
}
