<?php

namespace App\Http\Livewire\UserResource;

use App\Enums\ProjectUserTypeEnum;
use App\Filament\Resources\ProjectResource;
use App\Models\Image;
use App\Models\Imprest;
use App\Models\Payment;
use App\Models\ProjectUser;
use App\Models\Receive;
use App\Models\User;
use App\Models\UserReport;
use Closure;
use Exception;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class ProjectsTable extends UserDetailTable
{
    public UserReport $userReport;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->userReport = UserReport::findOrFail($this->user->id);
    }

    protected function getTableRecordUrlUsing(): ?Closure
    {
        return fn (Model $record): string => ProjectResource::getUrl('view', ['record' => $record->project_id]);
    }

    protected function getTableQuery(): Builder|Relation
    {
        if (! $this->isLoaded) {
            return $this->user->projectUsers()->whereRaw('false')->getQuery();
        }
        $countQuery = 'count(*)';

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
        $imageCountQuery = Image::query()
            ->withoutTrashed()
            ->whereHasMorph(
                'hasImage',
                [Payment::class, Receive::class],
                fn (Builder $query) => $query->whereColumn('project_user_id', 'project_user.id')
            )
            ->selectRaw($countQuery)
            ->getQuery();
        $imageSizeQuery = Image::query()
            ->withoutTrashed()
            ->whereHasMorph(
                'hasImage',
                [Payment::class, Receive::class],
                fn (Builder $query) => $query->whereColumn('project_user_id', 'project_user.id')
            )
            ->selectRaw('IFNULL(sum(size), 0) / 1024 / 1024')
            ->getQuery();

        return $this->user->projectUsers()
            ->with('teams')
            ->join('projects', 'projects.id', 'project_user.project_id')
            ->addSelect('projects.name as name')
            ->addSelect('projects.created_at as created_at')
            ->addSelect('project_user.id as id')
            ->addSelect('project_id')
            ->addSelect('user_type')
            ->selectSub($receiveCountQuery, 'receive_count')
            ->selectSub($imprestCountQuery, 'imprest_count')
            ->selectSub($imageCountQuery, 'image_count')
            ->selectSub($imageSizeQuery, 'image_size')
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
        ];
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make(__('names.table.row index'))
                ->rowIndex(),
            Tables\Columns\TextColumn::make('name')
                ->label(__('names.project name')),
            Tables\Columns\TextColumn::make('created_at')
                ->hidden()
                ->label(__('names.project created at')),
            Tables\Columns\BadgeColumn::make('user_type')
                ->label(__('names.role'))
                ->tooltip($this->getTeamNames())
                ->enum(ProjectUserTypeEnum::columnValues())
                ->color(static fn ($state) => ProjectUserTypeEnum::from($state)->color()),
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
            Tables\Columns\TextColumn::make('image_count')
                ->sortable()
                ->label(__('names.image count')),
            Tables\Columns\TextColumn::make('image_size')
                ->formatStateUsing(fn (string $state): string => round($state, 2))
                ->sortable()
                ->label(__('names.image size')),
        ];
    }

    protected function getTableContentFooter(): ?View
    {
        return \view('livewire.user-resource.projects-table-footer', [
            'footer_columns' => [
                '',
                __('names.sum'),
                '',
                $this->userReport->payment_count,
                $this->userReport->receive_count,
                $this->userReport->imprest_count,
                $this->userReport->image_count,
                $this->userReport->image_size,
            ],
        ]);
    }

    public function render(): View
    {
        return view('livewire.user-resource.projects-table');
    }

    private function getTeamNames(): Closure
    {
        return function (ProjectUser $record) {
            $teams = $record->teams;
            $isDefault = (($teams->count() == 1) and ($teams->first()->is_default));
            $text = $teams->pluck('name')->implode('، ');
            $text .= $isDefault ? ' ('.__('names.default').')' : '';
            return $text;
        };
    }
}
