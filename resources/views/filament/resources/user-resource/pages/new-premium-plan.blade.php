@php
    $planLabel = $this->getPlanText();
    $planAmount = $this->form->getComponent('total_amount')->getState() ?? 0;
    $addedValueLabel = \App\Constants\PremiumConstants::ADDED_VALUE_PERCENT * 100 .' '.__('names.percent').' '.__('names.added value amount');
    $addedValueAmount = $this->getAddedValueAmount();
    $useWallet = $this->form->getComponent('use_wallet')->getState();
    $useWalletLabel = __('names.use wallet');
    $walletAmount = -1 * $this->getUseWalletAmount();
    $useDiscount = !is_null($this->form->getComponent('promo_code_id')->getState());
    $useDiscountLabel = __('names.discount');
    $discountAmount = -1 * $this->getDiscountAmount($planAmount);

    $payableAmount = \App\Helpers\UtilHelpers::getPayableAmount($planAmount, $addedValueAmount, -1 * $discountAmount, -1 * $walletAmount);
    $payableAmountLabel = __('names.payable amount');
@endphp
<x-filament::page>
    <livewire:user-resource.user-consumption collapsed=0 :user="$this->user" show-only-bars=1 />
    <div class="pt-10">
        {{ $this->form }}

        <div class="grid grid-cols-3 pt-10">
            <x-filament::card class="col-start-2">
                <x-filament::card.heading>
                    {{ __('names.final invoice') }}
                </x-filament::card.heading>
                <div class="flex flex-col gap-4">
                    <x-user-premium-invoice-row :label="$planLabel" :value="$planAmount" />
                    <x-user-premium-invoice-row :label="$addedValueLabel" :value="$addedValueAmount" />
                    @if($useWallet)
                        <x-user-premium-invoice-row :label="$useWalletLabel" :value="$walletAmount" />
                    @endif
                    @if($useDiscount)
                        <x-user-premium-invoice-row :label="$useDiscountLabel" :value="$discountAmount" />
                    @endif
                    <hr>
                    <x-user-premium-invoice-row :label="$payableAmountLabel" :value="$payableAmount" />
                </div>
            </x-filament::card>
        </div>

        <div class="pt-5">
            <x-filament::button type="submit" wire:click="save">
                {{ __('filament::resources/pages/edit-record.form.actions.save.label') }}
            </x-filament::button>
        </div>
    </div>
</x-filament::page>
