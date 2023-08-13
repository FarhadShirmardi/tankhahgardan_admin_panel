<?php

namespace App\Http\Livewire\UserExtensionResource;

use App\Filament\Components\RowIndexColumn;
use App\Filament\Resources\UserResource;
use App\Models\UserStatus;
use Closure;
use DB;
use Derakht\Jalali\Jalali;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ExtensionTable extends Component implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    public bool $isLoaded = false;

    public function loadData(): void
    {
        $this->isLoaded = true;
    }

    protected function getTableEmptyStateIcon(): ?string
    {
        return $this->isLoaded ? null : 'heroicon-o-download';
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return $this->isLoaded ? null : __('message.loading_data');
    }

    protected function getTableQuery()
    {
        if (!$this->isLoaded) {
            return UserStatus::query()->whereRaw('false');
        }

        return UserStatus::query()
            ->join('panel_user_reports', 'panel_user_reports.id', 'user_statuses.user_id')
            ->where('end_date', '>', now()->subDays(5)->startOfDay())
            ->where('end_date', '<', now()->addDays(6)->endOfDay())
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
            RowIndexColumn::make(),
            TextColumn::make('name')
                ->label(__('names.full name'))
                ->getStateUsing(fn ($record) => filled($record->name) ? $record->name : '-')
                ->words(4),
            TextColumn::make('phone_number')
                ->label(__('names.phone number'))
                ->getStateUsing(fn ($record) => reformatPhoneNumber($record->phone_number)),
            TextColumn::make('date_diff')
                ->label(__('names.days remain'))
                ->tooltip(fn ($record) => Jalali::parse($record->end_date)->toJalaliDateTimeString()),
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

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [50, 100, -1];
    }

    public function render(): View
    {
        return view('livewire.user-extension-resource.extension-table');
    }
}
