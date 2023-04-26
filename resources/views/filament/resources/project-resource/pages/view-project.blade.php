<x-filament::page>
    <livewire:project-resource.project-detail :project="$this->project" />
    <livewire:user-resource.user-consumption
            :user="$this->project->getOwner()"
            is-owner="true"
            collapsed="true"
    />
    <livewire:project-resource.users-table :project="$this->project" />
    <livewire:project-resource.teams-table :project="$this->project" />
</x-filament::page>
