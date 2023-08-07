<div wire:init="loadData">
    <x-filament::card>
        <x-filament::card.heading>
            <div>
                {{ __('filament::pages/user.call logs title') }}
            </div>
        </x-filament::card.heading>
        {{ $this->table }}
    </x-filament::card>
</div>
