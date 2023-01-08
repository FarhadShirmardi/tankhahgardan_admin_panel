<x-filament::page>
    {{ $this->form }}

    @if($this->oldImages->count() != 0)
        <x-filament-support::grid :default=3 class="gap-x-2 gap-y-4">
            @foreach($this->oldImages as $oldImage)
                <x-filament-support::grid.column x-data="{hideDelete: {{$oldImage['is_deleted'] ? 1 : 0}}}" x-show="!hideDelete">
                    <div class="relative">
                        <img class="w-full" src="{{ $oldImage['path'] }}" alt="image"/>
                        <div class="absolute bottom-0 left-0 p-2">
                            <x-forms::icon-button
                                wire:click="deleteImage({{ $oldImage['id'] }})"
                                @click="hideDelete = 1;"
                                class="bg-white hover:bg-gray-100 text-red-500"
                                icon="heroicon-o-trash"
                            />
                        </div>
                    </div>
                </x-filament-support::grid.column>
            @endforeach
        </x-filament-support::grid>
    @endif

    @if(!$this->isUser)
        <x-filament::button type="submit" wire:click="save">
            {{ __('filament::resources/pages/edit-record.form.actions.save.label') }}
        </x-filament::button>
    @else
        <x-filament::button type="submit" wire:click="response">
            {{ __('names.response to message') }}
        </x-filament::button>
    @endif
</x-filament::page>
