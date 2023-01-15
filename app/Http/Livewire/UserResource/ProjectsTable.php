<?php

namespace App\Http\Livewire\UserResource;

use App\Enums\ProjectUserTypeEnum;
use App\Models\Image;
use App\Models\Imprest;
use App\Models\Payment;
use App\Models\Receive;
use App\Models\User;
use App\Models\UserReport;
use Filament\Tables;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Livewire\Component;

class ProjectsTable extends Component implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    private User $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    protected function getTableQuery(): Builder|Relation
    {
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
            ->join('projects', 'projects.id', 'project_user.project_id')
            ->addSelect('projects.name as name')
            ->addSelect('projects.id as id')
            ->addSelect('project_user.user_type as user_type')
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

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')
                ->label(__('names.project name')),
            Tables\Columns\BadgeColumn::make('user_type')
                ->label(__('names.role'))
                ->enum(ProjectUserTypeEnum::columnValues())
                ->color(static fn ($state) => ProjectUserTypeEnum::from($state)->color()),
            Tables\Columns\TextColumn::make('payments_count')
                ->label(__('names.payment count'))
                ->counts('payments'),
            Tables\Columns\TextColumn::make('receive_count')
                ->label(__('names.receive count')),
            Tables\Columns\TextColumn::make('imprest_count')
                ->label(__('names.imprest count')),
            Tables\Columns\TextColumn::make('image_count')
                ->label(__('names.image count')),
            Tables\Columns\TextColumn::make('image_size')
                ->label(__('names.image size')),
        ];
    }

    protected function getTableContentFooter(): ?View
    {
        $userReport = UserReport::findOrFail($this->user->id);
        return \view('livewire.user-resource.projects-table-footer', [
            'footer_columns' => [
                __('names.sum'),
                '',
                $userReport->payment_count,
                $userReport->receive_count,
                $userReport->imprest_count,
                $userReport->image_count,
                $userReport->image_size,
            ],
        ]);
    }

    public function render(): View
    {
        return view('livewire.user-resource.projects-table');
    }
}
