@php
    /* @var \App\Models\UserStatusLog $record */
    $premiumPlan = $record->premium_plan_id ? \App\Models\PremiumPlan::query()->find($record->premium_plan_id) : null;
    $duration = \App\Enums\PremiumDurationEnum::from($record->duration_id);
    $planLabel = $premiumPlan ? ($premiumPlan->type->title().' - '.$duration->getTitle()) : 'مبلغ طرح';
    $planAmount = $record->total_amount;
    $addedValueLabel = \App\Constants\PremiumConstants::ADDED_VALUE_PERCENT * 100 .' '.__('names.percent').' '.__('names.added value amount');
    $addedValueAmount = $record->added_value_amount;
    $walletLabel = __('names.use wallet');
    $walletAmount = -1 * $record->wallet_amount;
    $discountLabel = __('names.discount');
    $discountAmount = -1 * $record->discount_amount;
    $creditLabel = __('names.credit');
    $creditAmount = -1 * $record->credit_amount;

    $payableAmount = \App\Helpers\UtilHelpers::getPayableAmount($planAmount, $addedValueAmount, -1 * $discountAmount, -1 * $walletAmount, -1 * $creditAmount);
    $payableAmountLabel = __('names.payable amount');

    $startDateLabel = __('names.start date');
    $startDate = \Derakht\Jalali\Jalali::parse($record->start_date)->toJalaliDateTimeString();
    $endDateLabel = __('names.end date');
    $endDate = \Derakht\Jalali\Jalali::parse($record->end_date)->toJalaliDateTimeString();
@endphp
<div class="flex flex-col gap-4">
    <x-user-premium-invoice-row :label="$planLabel" :value="$planAmount" />
    <x-user-premium-invoice-row :label="$startDateLabel" :value="$startDate" is-price=0 />
    <x-user-premium-invoice-row :label="$endDateLabel" :value="$endDate" is-price=0 />
    <x-user-premium-invoice-row :label="$addedValueLabel" :value="$addedValueAmount" />
    @if($walletAmount)
        <x-user-premium-invoice-row :label="$walletLabel" :value="$walletAmount" />
    @endif
    @if($discountAmount)
        <x-user-premium-invoice-row :label="$discountLabel" :value="$discountAmount" />
    @endif
    @if($creditAmount)
        <x-user-premium-invoice-row :label="$creditLabel" :value="$creditAmount" />
    @endif
    <hr>
    <x-user-premium-invoice-row :label="$payableAmountLabel" :value="$payableAmount" />
</div>
