<div wire:init="loadData">
    <x-filament::card>
        <x-filament::card.heading>
            {{ __('filament::pages/user.tickets title') }}
        </x-filament::card.heading>
        {{ $this->table }}
    </x-filament::card>
</div>
