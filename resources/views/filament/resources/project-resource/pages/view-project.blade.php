<x-filament::page>
    <livewire:project-resource.project-detail :project="$this->project" />
    <livewire:user-resource.user-consumption
        :user="$this->project->getOwner()"
        is-owner="true"
        collapsed="true"
    />
</x-filament::page>
