<?php

namespace App\Http\Livewire\UserResource;

use App\Enums\ActivityTypeEnum;
use App\Enums\PlatformEnum;
use App\Enums\PremiumDurationEnum;
use App\Enums\PremiumPlanEnum;
use App\Models\ProjectReport;
use App\Models\UserReport;
use App\Models\UserStatus;
use Ariaieboy\FilamentJalaliDatetime\JalaliDateTimeColumn;
use Closure;
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
//            ->join('premium_plans','user_statuses.premium_plan_id', 'premium_plans.id')
//            ->addSelect('premium_plans.type as plan_type')
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
                ->extraAttributes([
                    'class' => 'ltr-col',
                ])
                ->date()
                ->sortable(),
            JalaliDateTimeColumn::make('end_date')
                ->label(__('names.end date'))
                ->extraAttributes([
                    'class' => 'ltr-col',
                ])
                ->date()
                ->sortable(),
        ];
    }

    protected function getTableRecordClassesUsing(): ?Closure
    {
        return fn (UserStatus $record) => ($record->start_date < now()->toDateTimeString() and $record->end_date > now()->toDateTimeString()) ? 'bg-success-500/10' : '';
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
