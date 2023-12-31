<?php

namespace App\Http\Livewire\ProjectResource;

use App\Enums\ProjectUserStateEnum;
use App\Enums\ProjectUserTypeEnum;
use App\Filament\Components\RowIndexColumn;
use App\Filament\Resources\UserResource;
use App\Http\Livewire\UserResource\ProjectsTable;
use App\Models\Imprest;
use App\Models\Payment;
use App\Models\Project;
use App\Models\ProjectReport;
use App\Models\ProjectUser;
use App\Models\Receive;
use Closure;
use DB;
use Exception;
use Filament\Tables;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class UsersTable extends ProjectDetailTable
{
    public ProjectReport $projectReport;

    public function mount(Project $project): void
    {
        $this->project = $project;
        $this->projectReport = ProjectReport::findOrFail($this->project->id);
    }

    protected function getTableRecordUrlUsing(): ?Closure
    {
        return fn (Model $record): string => UserResource::getUrl('view', ['record' => $record->user_id]);
    }

    protected function getTableQuery(): Builder|Relation
    {
        if (! $this->isLoaded) {
            return $this->project->projectUsers()->whereRaw('false')->getQuery();
        }
        $countQuery = 'count(*)';

        $paymentCountQuery = Payment::query()
            ->withoutTrashed()
            ->whereColumn('project_user_id', 'project_user.id')
            ->selectRaw($countQuery)
            ->getQuery();
        $receiveCountQuery = Receive::query()
            ->withoutTrashed()
            ->whereColumn('project_user_id', 'project_user.id')
            ->selectRaw($countQuery)
            ->getQuery();
        $imprestCountQuery = Imprest::query()
            ->withoutTrashed()
            ->whereColumn('project_user_id', 'project_user.id')
            ->selectRaw($countQuery)
            ->getQuery();

        return $this->project->projectUsers()
            ->with('teams')
            ->join('users', 'users.id', 'project_user.user_id')
            ->addSelect('users.phone_number as phone_number')
            ->addSelect(DB::raw("CONCAT_WS(' ', IFNULL(users.name, ''), IFNULL(users.family, '')) as name"))
            ->addSelect('project_user.created_at as created_at')
            ->addSelect('project_user.id as id')
            ->addSelect('user_id')
            ->addSelect('user_type')
            ->addSelect('project_user.state as user_state')
            ->selectSub($paymentCountQuery, 'payment_count')
            ->selectSub($receiveCountQuery, 'receive_count')
            ->selectSub($imprestCountQuery, 'imprest_count')
            ->getQuery();
    }

    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }

    /**
     * @throws Exception
     */
    protected function getTableFilters(): array
    {
        return [
            Tables\Filters\SelectFilter::make('user_type')
                ->label(__('names.role'))
                ->multiple()
                ->options(ProjectUserTypeEnum::columnValues()),
            Tables\Filters\SelectFilter::make('user_state')
                ->label(__('names.user state'))
                ->multiple()
                ->query(fn (Builder $query, array $data): Builder => $query->when(filled($data['values']), fn ($q) => $q->where('project_user.state', $data['values'])))
                ->options(ProjectUserStateEnum::columnValues()),
        ];
    }

    protected function getTableColumns(): array
    {
        return [
            RowIndexColumn::make(),
            Tables\Columns\TextColumn::make('name')
                ->label(__('names.full name'))
                ->tooltip(fn (ProjectUser $record) => $record->name)
                ->words(2)
                ->copyable(),
            Tables\Columns\TextColumn::make('phone_number')
                ->label(__('names.phone number'))
                ->getStateUsing(fn (ProjectUser $record) => reformatPhoneNumber($record->phone_number))
                ->copyable(),
            Tables\Columns\TextColumn::make('created_at')
                ->hidden()
                ->label(__('names.project created at')),
            Tables\Columns\BadgeColumn::make('user_type')
                ->label(__('names.role'))
                ->tooltip(ProjectsTable::getTeamNames())
                ->enum(ProjectUserTypeEnum::columnValues())
                ->color(static fn ($state) => ProjectUserTypeEnum::from($state)->color()),
            Tables\Columns\IconColumn::make('user_state')
                ->options([
                    'heroicon-o-x-circle' => ProjectUserStateEnum::INACTIVE->value,
                    'heroicon-o-clock' => ProjectUserStateEnum::PENDING->value,
                    'heroicon-o-check-circle' => ProjectUserStateEnum::ACTIVE->value
                ])
                ->tooltip(fn ($record) => ProjectUserStateEnum::from($record->user_state)->description())
                ->colors([
                    'danger' => ProjectUserStateEnum::INACTIVE->value,
                    'warning' => ProjectUserStateEnum::PENDING->value,
                    'success' => ProjectUserStateEnum::ACTIVE->value,
                ])
                ->label(__('names.user state')),
            Tables\Columns\TextColumn::make('payments_count')
                ->sortable()
                ->label(__('names.payment count'))
                ->counts('payments'),
            Tables\Columns\TextColumn::make('receive_count')
                ->sortable()
                ->label(__('names.receive count')),
            Tables\Columns\TextColumn::make('imprest_count')
                ->sortable()
                ->label(__('names.imprest count')),
        ];
    }

    protected function getTableContentFooter(): ?View
    {
        return \view('livewire.project-resource.users-table-footer', [
            'footer_columns' => [
                '',
                __('names.sum'),
                '',
                '',
                '',
                $this->projectReport->payment_count,
                $this->projectReport->receive_count,
                $this->projectReport->imprest_count,
            ],
        ]);
    }

    public function render(): View
    {
        return view('livewire.project-resource.users-table');
    }
}
