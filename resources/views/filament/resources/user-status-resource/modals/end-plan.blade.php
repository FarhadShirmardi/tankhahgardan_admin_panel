@php
    $amount = formatPrice(\App\Services\PremiumService::getCancelPlanCreditAmount($record));
@endphp
<div class="pb-5">
    <p>با لغو طرح مبلغ {{ $amount }} ریال به کاربر برگشت داده می‌شود.</p>
</div>
