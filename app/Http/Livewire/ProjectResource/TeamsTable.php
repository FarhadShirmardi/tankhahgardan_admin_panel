<?php

namespace App\Http\Livewire\ProjectResource;

use App\Enums\ProjectUserTypeEnum;
use App\Filament\Components\RowIndexColumn;
use App\Filament\Resources\ProjectResource;
use App\Filament\Resources\UserResource;
use App\Models\Imprest;
use App\Models\Payment;
use App\Models\Project;
use App\Models\ProjectReport;
use App\Models\ProjectUser;
use App\Models\Receive;
use App\Models\Team;
use Closure;
use DB;
use Exception;
use Filament\Tables;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Livewire\Component;

class TeamsTable extends ProjectDetailTable
{
    public function mount(Project $project): void
    {
        $this->project = $project;
    }

    protected function getTableRecordUrlUsing(): ?Closure
    {
        return fn (Team $record): string => ProjectResource::getUrl('viewTeam', ['record' => $record->project_id, 'subRecord' => $record->id]);
    }

    protected function getTableQuery(): Builder|Relation
    {
        return $this->project->teams()
            ->when(!$this->isLoaded, fn ($query) => $query->whereRaw('false'))
            ->getQuery();
    }

    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }

    protected function getTableColumns(): array
    {
        return [
            RowIndexColumn::make(),
            Tables\Columns\TextColumn::make('name')
                ->label(__('names.team name'))
                ->words(4),
            Tables\Columns\IconColumn::make('is_default')
                ->boolean()
                ->label(__('names.default team')),
        ];
    }

    public function render(): View
    {
        return view('livewire.project-resource.teams-table');
    }
}
