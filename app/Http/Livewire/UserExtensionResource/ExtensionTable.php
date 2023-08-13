<?php

namespace App\Http\Livewire\UserExtensionResource;

use App\Filament\Components\JalaliDateTimeColumn;
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
            ->orderBy('date_diff')
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
            Tables\Columns\ColorColumn::make('color')
                ->label('')
                ->tooltip(fn ($record) => $record->has_extended? 'تمدید کرده است' : ($record->date_diff < 0 ? 'تمدید نکرده است' : ''))
                ->getStateUsing(fn ($record) => $record->has_extended? '#33d48f' : ($record->date_diff < 0 ? '#F1F3F4' : '')),
            TextColumn::make('name')
                ->label(__('names.full name'))
                ->getStateUsing(fn ($record) => filled($record->name) ? $record->name : '-')
                ->words(4),
            TextColumn::make('phone_number')
                ->label(__('names.phone number'))
                ->getStateUsing(fn ($record) => reformatPhoneNumber($record->phone_number)),
            JalaliDateTimeColumn::make('max_time')
                ->label(__('names.last record time'))
                ->dateTime(),
            TextColumn::make('date_diff')
                ->label(__('names.days remain'))
                ->tooltip(fn ($record) => Jalali::parse($record->end_date)->toJalaliDateTimeString()),
        ];
    }

    protected function getTableRecordUrlUsing(): ?Closure
    {
        return fn ($record) => UserResource::getUrl('view', ['record' => $record->id]);
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
