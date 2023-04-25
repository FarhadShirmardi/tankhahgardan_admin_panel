<div wire:init="loadData">
    <x-filament::card>
        <x-filament::card.heading class="flex justify-between">
            <div>
                {{ __('filament::pages/user.tickets title') }}
            </div>
            <div>
                <x-filament-support::modal id="new-ticket-modal" width="lg">
                    <x-slot name="header">
                        {{ __('names.create ticket') }}
                    </x-slot>

                    <livewire:user-resource.new-ticket :user="$this->user"/>
                </x-filament-support::modal>
                <x-filament::button
                        color="success"
                        icon="heroicon-o-plus"
                        x-on:click="$dispatch('open-modal', { id: 'new-ticket-modal' })"
                >
                    {{ __('names.create ticket') }}
                </x-filament::button>
            </div>
        </x-filament::card.heading>
        {{ $this->table }}
    </x-filament::card>
</div>
