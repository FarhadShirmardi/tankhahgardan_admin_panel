<div wire:init="loadData">
    <x-filament::card>
        <x-filament::card.heading>
            <div>
                {{ __('filament::pages/user.transactions title') }}
            </div>
        </x-filament::card.heading>
        {{ $this->table }}
    </x-filament::card>
</div>
