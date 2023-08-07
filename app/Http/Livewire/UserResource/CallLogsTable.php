<?php

namespace App\Http\Livewire\UserResource;

use App\Filament\Components\JalaliDateTimeColumn;
use App\Filament\Components\RowIndexColumn;
use App\Filament\Resources\TransactionResource;
use App\Models\CallLog;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class CallLogsTable extends UserDetailTable
{
    protected function getTableQuery(): Builder|Relation
    {
        if (!$this->isLoaded) {
            return $this->user->callLogs()->whereRaw('false')->getQuery();
        }

        return $this->user->callLogs()
            ->latest('date')
            ->getQuery();
    }

    protected function getTableColumns(): array
    {
        return [
            RowIndexColumn::make(),
            TextColumn::make('username')
                ->formatStateUsing(fn (CallLog $record) => $record->panelUser->username)
                ->tooltip(fn (CallLog $record) => reformatPhoneNumber($record->panelUser->phone_number))
                ->label(__('names.full name')),
            JalaliDateTimeColumn::make('date')
                ->label(__('names.date_time'))
                ->dateTime(),
            TextColumn::make('text')
                ->label(__('names.description')),
        ];
    }

    protected function getTablePaginationPageName(): string
    {
        return 'call_logs_page';
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
