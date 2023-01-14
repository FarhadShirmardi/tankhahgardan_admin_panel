<x-filament::page>
    <livewire:user-resource.user-detail :user="$this->user"/>
    <hr>
    <livewire:user-resource.projects-table :user="$this->user"/>
</x-filament::page>
