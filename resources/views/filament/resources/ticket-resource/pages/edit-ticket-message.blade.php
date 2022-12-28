<x-filament::page>
    {{ $this->form }}

    @if($this->oldImages->count() != 0)
        <x-filament-support::grid :default=3 class="gap-x-2">
            @foreach($this->oldImages as $oldImage)
                <x-filament-support::grid.column >
                    <img src="{{ $oldImage['path'] }}"/>
                </x-filament-support::grid.column>
            @endforeach
        </x-filament-support::grid>
    @endif

    <x-filament::button type="submit" wire:click="save">
        {{ __('filament::resources/pages/edit-record.form.actions.save.label') }}
    </x-filament::button>
</x-filament::page>
