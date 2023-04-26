<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use App\Models\Project;
use App\Models\Team;
use Filament\Resources\Pages\Page;

class ViewTeam extends Page
{
    public Project $project;
    public Team $team;

    protected static string $resource = ProjectResource::class;

    protected static string $view = 'filament.resources.project-resource.pages.view-team';

    public function getBreadcrumb(): ?string
    {
        return __('names.project').' '.$this->project->name;
    }

    protected function getTitle(): string
    {
        return __('names.team').' '.$this->team->name;
    }

    public function mount(int $record, int $subRecord): void
    {
        $this->project = Project::findOrFail($record);
        $this->team = $this->project->teams()->findOrFail($subRecord);
    }
}
