<x-filament::page>
    <livewire:user-resource.user-consumption collapsed=0 :user="$this->user" show-only-bars=1/>
    <div class="pt-10">
        {{ $this->form }}

        <div class="grid grid-cols-3">
            <div></div>
            <div class="pt-10 flex justify-between">
                <x-forms::field-wrapper.label>مالیات بر ارزش افزوده</x-forms::field-wrapper.label>
                <div class="plan-info-amount-container">
                    ۵
                </div>
            </div>
        </div>

        <div class="pt-5">
            <x-filament::button type="submit" wire:click="save">
                {{ __('filament::resources/pages/edit-record.form.actions.save.label') }}
            </x-filament::button>
        </div>
    </div>
</x-filament::page>
