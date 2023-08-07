<?php

namespace App\Http\Livewire\UserResource;

use App\Filament\Components\JalaliDateTimeColumn;
use App\Filament\Components\JalaliDateTimePicker;
use App\Filament\Components\RowIndexColumn;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
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
            ->with('panelUser')
            ->latest('date')
            ->getQuery();
    }

    protected function getTableColumns(): array
    {
        return [
            RowIndexColumn::make(),
            TextColumn::make('panelUser')
                ->formatStateUsing(fn (CallLog $record) => $record->panelUser->name)
                ->label(__('names.full name')),
            JalaliDateTimeColumn::make('date')
                ->label(__('names.date_time'))
                ->dateTime(),
            TextColumn::make('text')
                ->label(__('names.description')),
        ];
    }

    protected function getTableHeaderActions(): array
    {
        return [
            Tables\Actions\CreateAction::make()
                ->label(fn (): string => __('filament-support::actions/create.single.label', ['label' => __('filament::pages/callLog.title')]))
                ->action(function (array $data): void {
                    $data['panel_user_id'] = auth()->id();
                    $this->user->callLogs()->create($data);
                })
                ->form([
                    Textarea::make('text')
                        ->required()
                        ->label(__('names.description')),

                    JalaliDateTimePicker::make('date')
                        ->label(__('names.date_time'))
                        ->required()
                        ->default(now()->toDateTimeString()),
                ])
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
        return view('livewire.user-resource.call-logs-table');
    }
}
