<x-filament::page>
    <livewire:user-resource.user-detail :user="$this->user"/>
    <livewire:user-resource.user-consumption :user="$this->user"/>
    <hr>
    <livewire:user-resource.projects-table :user="$this->user"/>

    <x-forms::field-wrapper.hint color="danger" icon="heroicon-s-check-circle">
        this is test
    </x-forms::field-wrapper.hint>
</x-filament::page>
