<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use App\Models\Project;
use App\Models\User;
use Filament\Resources\Pages\Page;

class ViewProject extends Page
{
    public Project $project;

    protected static string $resource = ProjectResource::class;

    protected static string $view = 'filament.resources.project-resource.pages.view-project';

    protected function getTitle(): string
    {
        return __('names.project').' '.$this->project->name;
    }

    public function mount(int $record)
    {
        $this->project = Project::findOrFail($record);
        $this->project->updateProjectReport();
    }
}
