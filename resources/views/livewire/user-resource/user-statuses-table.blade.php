<div wire:init="loadData">
    <x-filament::card>
        <x-filament::card.heading class="flex justify-between">
            <div>
                {{ __('filament::pages/user.user statuses title') }}
            </div>
            @if(\Filament\Facades\Filament::auth()->user()->can('viewAny', \App\Models\PremiumPlan::class))
                <div>
                    <x-filament::button
                            tag="a"
                            color="success"
                            icon="heroicon-o-plus"
                            href="{{ \App\Filament\Resources\UserResource::getUrl('new-premium-plan', ['record' => $user]) }}"
                    >
                        {{ __('names.create new plan') }}
                    </x-filament::button>
                </div>
            @endif
        </x-filament::card.heading>
        {{ $this->table }}
    </x-filament::card>
</div>
