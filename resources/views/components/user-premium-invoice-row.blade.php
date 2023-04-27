<div class="flex justify-between">
    <x-forms::field-wrapper.label>{{ $label }}</x-forms::field-wrapper.label>
    <div class="flex justify-between gap-2">
        <x-forms::field-wrapper.label class="ltr-col"
                                      error="{{ $hasError }}">{{ $isPrice ? formatPrice($value) : $value }}</x-forms::field-wrapper.label>
        @if($isPrice)
            <x-forms::field-wrapper.hint>{{ $unit }}</x-forms::field-wrapper.hint>
        @endif
    </div>
</div>