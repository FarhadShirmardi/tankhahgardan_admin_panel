<div wire:init="loadData">
    <x-filament::card>
        <x-filament::card.heading>
            <div>
                {{ __('filament::pages/user.invoices title') }}
            </div>
        </x-filament::card.heading>
        {{ $this->table }}
    </x-filament::card>
</div>
