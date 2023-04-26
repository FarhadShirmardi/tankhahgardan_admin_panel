<?php

namespace App\Http\Livewire\ProjectResource;

use App\Models\Project;
use Filament\Tables;
use Livewire\Component;

class ProjectDetailTable extends Component implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    public Project $project;

    public bool $isLoaded = false;

    public function mount(Project $project): void
    {
        $this->project = $project;
    }

    public function loadData(): void
    {
        $this->isLoaded = true;
    }

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'created_at';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }

    protected function getTableEmptyStateIcon(): ?string
    {
        return $this->isLoaded ? null : 'heroicon-o-download';
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return $this->isLoaded ? null : __('message.loading_data');
    }
}
