@php
    $evaluatedColor = $getColor();
    $color = match ($evaluatedColor) {
        'primary' => 'bg-primary-600',
        'secondary' => 'bg-secondary-600',
        'danger' => 'bg-danger-600',
        'success' => 'bg-success-600',
        'warning' => 'bg-warning-600',
        default => $evaluatedColor
    };
    $total = $getTotal();
    $progressed = $getProgressed();
    try{
        $progressBar = is_null($total) ? 0 : ((int) $progressed / (int) $total * 100);
    } catch (Error|Exception) {
        $progressBar = 100;
    }
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :id="$getId()"
    :label="$getLabel()"
    :label-sr-only="$isLabelHidden()"
    :helper-text="$getHelperText()"
    :hint="$getHint()"
    :hint-action="$getHintAction()"
    :hint-color="$getHintColor()"
    :hint-icon="$getHintIcon()"
    :required="$isRequired()"
    :state-path="$getStatePath()"
>
    <div x-data="{ state: $wire.entangle('{{ $getStatePath() }}').defer }">
        <div
            class="filament-tables-progress-column pt-2"
        >
            <div class="flex items-center space-x-reverse-4 px-4">
                <div class="bg-gray-200 rounded-full h-2.5 dark:bg-gray-600 w-full">
                    <div @class([
                'h-2.5 rounded-full',
                $color,
            ]) style="width: {{ $progressBar }}%"></div>
                </div>
            </div>
            <div class="flex justify-between px-4">
                <span class="text-sm text-gray-700 dark:text-gray-200">{{ $progressed }}</span>
                <span class="text-sm text-gray-700 dark:text-gray-200">{{ is_null($total) ? 'نامحدود' : $total }}</span>
            </div>
        </div>
    </div>
</x-dynamic-component>
