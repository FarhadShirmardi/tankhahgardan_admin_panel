<div>
     {{ $this->form }}

    <x-filament::button type="submit" wire:click="save">
        {{ __('filament::resources/pages/edit-record.form.actions.save.label') }}
    </x-filament::button>
</div>