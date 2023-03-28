<x-filament::page>
    <livewire:user-resource.user-detail :user="$this->user"/>
    <livewire:user-resource.user-consumption :user="$this->user"/>
    <livewire:user-resource.projects-table :user="$this->user"/>

    <x-filament::hr/>
    <x-filament::card>
        <x-filament::card.heading>
            {{ __('filament::pages/user.devices_title') }}
        </x-filament::card.heading>
        <livewire:user-resource.devices-table :user="$this->user"/>
    </x-filament::card>
</x-filament::page>
