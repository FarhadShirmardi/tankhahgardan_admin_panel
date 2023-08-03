<div wire:init="loadData">
    <x-filament::card @class(['animate-pulse' => !$this->isLoaded])>
        <x-filament::card.heading>
            {{ __('filament::pages/user.devices title') }}
        </x-filament::card.heading>
        {{ $this->table }}
    </x-filament::card>
</div>
