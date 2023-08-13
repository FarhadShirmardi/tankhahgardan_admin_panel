<?php

namespace App\Filament\Resources\SaleResource\Widgets;

use App\Filament\Resources\UserResource;
use App\Models\UserStatus;
use Closure;
use DB;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;

class SaleExtensionTable extends TableWidget
{
    protected static ?int $sort = 4;

    protected function getTableHeading(): string|Htmlable|Closure|null
    {
        return null;
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [-1, 5];
    }

    protected function getDefaultTableRecordsPerPageSelectOption(): int
    {
        return 5;
    }

    protected function getTableQuery(): Builder
    {
        return UserStatus::query()
            ->join('panel_user_reports', 'panel_user_reports.id', 'user_statuses.user_id')
            ->where('end_date', '>', now()->subDays(5)->startOfDay())
            ->where('end_date', '<', now()->addDays(5)->endOfDay())
            ->orderByRaw('date_diff desc')
            ->select([
                DB::raw("exists(select * from user_statuses ou where ou.user_id = user_statuses.user_id and ou.end_date > user_statuses.end_date) as has_extended"),
                DB::raw("datediff(end_date, now()) as date_diff"),
                DB::raw("panel_user_reports.*"),
                'end_date',
                'premium_plan_id',
                'duration_id'
            ]);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name')
                ->label(__('names.full name'))
                ->tooltip(fn ($record) => $record->phone_number)
                ->words(4),
            TextColumn::make('phone_number')
                ->label(__('names.phone number'))
                ->getStateUsing(fn ($record) => reformatPhoneNumber($record->phone_number)),
            TextColumn::make('date_diff')
                ->label(__('names.days remain'))
                ->tooltip(fn ($record) => $record->date_diff),
        ];
    }

    protected function getTableRecordUrlUsing(): ?Closure
    {
        return fn ($record) => UserResource::getUrl('view', ['record' => $record->id]);
    }

    protected function getTableRecordClassesUsing(): ?Closure
    {
        return function (UserStatus $record) {
            if ($record->has_extended) {
                return 'bg-success-500/10';
            }
            if ($record->date_diff < 0) {
                return 'bg-gray-200';
            }
            return '';
        };
    }
}
