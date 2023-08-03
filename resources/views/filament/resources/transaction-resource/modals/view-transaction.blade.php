@php
    $planLabel = 'test';
    $planAmount = 1000;
@endphp
<div class="flex flex-col gap-4">
    <x-user-premium-invoice-row :label="$planLabel" :value="$planAmount" />
</div>
