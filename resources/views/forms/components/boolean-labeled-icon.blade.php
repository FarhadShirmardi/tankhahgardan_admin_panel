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
    :state-path="$getStatePath()"
>
    <div x-data="{ state: $wire.entangle('{{ $getStatePath() }}').defer }">
        <div {{ $attributes->class(array_merge(
            ['filament-forms-field-wrapper-hint flex items-center space-x-2 rtl:space-x-reverse'],
            match ($getIconColor()) {
                'danger' => [
                    'text-danger-500',
                    'dark:text-danger-300' => config('tables.dark_mode'),
                ],
                'success' => [
                    'text-success-500',
                    'dark:text-success-300' => config('tables.dark_mode'),
                ],
                'warning' => [
                    'text-warning-500',
                    'dark:text-warning-300' => config('filament.dark_mode'),
                ],
                'primary' => [
                    'text-primary-500',
                    'dark:text-primary-300' => config('tables.dark_mode'),
                ],
                default => [
                    'text-gray-500',
                    'dark:text-gray-300' => config('tables.dark_mode'),
                ],
            },
        )) }}>
            <x-dynamic-component :component="$getIcon()" class="h-6 w-6"/>
        </div>
    </div>
</x-dynamic-component>
