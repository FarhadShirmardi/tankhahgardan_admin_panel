<div class="flex justify-between">
    <x-forms::field-wrapper.label>{{ $label }}</x-forms::field-wrapper.label>
    <div class="flex justify-between gap-2">
        <x-forms::field-wrapper.label class="ltr-col" error="{{ $hasError }}">{{ formatPrice($value) }}</x-forms::field-wrapper.label>
        <x-forms::field-wrapper.hint>{{ $unit }}</x-forms::field-wrapper.hint>
    </div>
</div>