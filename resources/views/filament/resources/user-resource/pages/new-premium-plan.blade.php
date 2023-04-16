<x-filament::page>
    <livewire:user-resource.user-consumption collapsed=0 :user="$this->user" show-only-bars=1 />
        <div class="pt-10">
            {{ $this->form }}
        </div>
</x-filament::page>
