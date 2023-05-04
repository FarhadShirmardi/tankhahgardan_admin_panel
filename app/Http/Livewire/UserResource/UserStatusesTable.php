<?php

namespace App\Http\Livewire\UserResource;

use App\Data\EndPremiumPlanData;
use App\Enums\EndPremiumPlanReturnTypeEnum;
use App\Enums\PremiumDurationEnum;
use App\Filament\Components\JalaliDateTimeColumn;
use App\Models\UserStatus;
use App\Services\PremiumService;
use Closure;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class UserStatusesTable extends UserDetailTable
{
    protected function getTableQuery(): Builder|Relation
    {
        if (!$this->isLoaded) {
            return $this->user->userStatuses()->whereRaw('false')->getQuery();
        }

        return $this->user->userStatuses()
            ->orderByDesc('user_statuses.end_date')
            ->getQuery();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make(__('names.table.row index'))->rowIndex(),
            Tables\Columns\ColorColumn::make('premiumPlan.type')
                ->label(__('names.plan'))
                ->tooltip(fn (UserStatus $record) => $record->premiumPlan->type->title())
                ->alignCenter()
                ->getStateUsing(fn (UserStatus $record) => $record->premiumPlan->type->color()),
            Tables\Columns\BadgeColumn::make('duration_id')
                ->label(__('names.plan type'))
                ->enum(PremiumDurationEnum::columnValues())
                ->color(static fn ($state) => PremiumDurationEnum::from($state)->color()),
            JalaliDateTimeColumn::make('start_date')
                ->label(__('names.start date'))
                ->dateTime()
                ->sortable(),
            JalaliDateTimeColumn::make('end_date')
                ->label(__('names.end date'))
                ->dateTime()
                ->sortable(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\Action::make('Cancel plan')
                ->label(__('names.cancel plan'))
                ->visible(fn (Tables\Actions\Action $action) => $action->getRecord()->start_date < now()->toDateTimeString() and $action->getRecord()->end_date > now()->toDateTimeString())
                ->icon('lucide-x')
                ->action(function (UserStatus $record, array $data) {
                    PremiumService::endPlan($record, EndPremiumPlanData::from($data));

                    Notification::make()
                        ->success()
                        ->title(__('message.plan ended successfully'))
                        ->send();

                    $this->emitSelf('$refresh');
                })
                ->modalHeading(__('names.cancel plan title'))
                ->form([
                    Select::make('type')
                        ->label(__('names.return money type.title'))
                        ->inlineLabel()
                        ->required()
                        ->options(EndPremiumPlanReturnTypeEnum::columnValues()),
                    Textarea::make('text')
                        ->label(__('names.description'))
                        ->inlineLabel()
                        ->required()
                ])
                ->modalContent(fn (UserStatus $record) => \view('filament.resources.user-status-resource.modals.end-plan', ['record' => $record]))
        ];
    }

    protected function getTableRecordClassesUsing(): ?Closure
    {
        return function (UserStatus $record) {
            if ($record->start_date < now()->toDateTimeString() and $record->end_date > now()->toDateTimeString()) {
                return 'bg-success-500/10';
            }
            if ($record->start_date > now()->toDateTimeString() and $record->end_date > now()->toDateTimeString()) {
                return 'bg-gray-200';
            }
            return '';
        };
    }

    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }

    public function render(): View
    {
        return view('livewire.user-resource.user-statuses-table');
    }
}
