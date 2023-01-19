<x-filament::page>
    <livewire:user-resource.user-detail :user="$this->user"/>
    <livewire:user-resource.user-consumption :user="$this->user"/>
    <livewire:user-resource.projects-table :user="$this->user"/>
</x-filament::page>
