<?php

namespace App\Http\Livewire\UserResource;

use App\Enums\PlatformEnum;
use Ariaieboy\FilamentJalaliDatetime\JalaliDateTimeColumn;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use stdClass;

class DevicesTable extends UserDetailTable
{
    public int $device_page = 1;

    protected function getTableQuery(): Builder|Relation
    {
        if (!$this->isLoaded) {
            return $this->user->devices()->whereRaw('false')->getQuery();
        }

        return $this->user->devices()
            ->orderByDesc('devices.created_at')
            ->getQuery();
    }

    protected function getDefaultTableRecordsPerPageSelectOption(): int
    {
        return 5;
    }

    protected function getTablePaginationPageName(): string
    {
        return 'device_page';
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make(__('names.table.row index'))->getStateUsing(
                static function (stdClass $rowLoop, Tables\Contracts\HasTable $livewire): string {
                    return (string)($rowLoop->iteration + ($rowLoop->count * ($livewire->device_page - 1)));
                }
            ),
            Tables\Columns\BadgeColumn::make('platform')
                ->label(__('names.platform'))
                ->enum(PlatformEnum::columnValues())
                ->color(static fn ($state) => PlatformEnum::from($state)->color()),
            TextColumn::make('model')
                ->label(__('names.device model')),
            TextColumn::make('os_version')
                ->label(__('names.os version')),
            TextColumn::make('app_version')
                ->label(__('names.app version')),
            JalaliDateTimeColumn::make('created_at')
                ->label(__('names.device created at'))
                ->extraAttributes([
                    'class' => 'ltr-col',
                ])
                ->dateTime()
        ];
    }

    public function render(): View
    {
        return view('livewire.user-resource.devices-table');
    }
}
