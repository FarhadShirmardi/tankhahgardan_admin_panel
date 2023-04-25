@php
    $amount = formatPrice($record->payable_amount);
@endphp
<div class="pb-5">
    <p>پیش فاکتور کاربر به مبلغ {{ $amount }} ریال پرداخت شود؟</p>
</div>
