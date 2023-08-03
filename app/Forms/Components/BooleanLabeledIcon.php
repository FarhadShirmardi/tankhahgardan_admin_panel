<?php

namespace App\Forms\Components;

use App\Traits\HasIcon;
use Closure;
use Filament\Forms\Components;

class BooleanLabeledIcon extends Components\Field
{
    use Components\Concerns\CanBeInline;
    use Components\Concerns\HasLabel;
    use HasIcon;

    protected string $view = 'forms.components.boolean-labeled-icon';

    protected bool | Closure | null $hasInlineLabel = true;

    protected function setUp(): void
    {
        parent::setUp();

        $this->default(false);

        $this->afterStateHydrated(static function (BooleanLabeledIcon $component, $state): void {
            $state = (bool) $state;
            $component->state($state);
            $component->icon($state ? 'heroicon-s-check-circle' : 'heroicon-s-x-circle');
            $component->iconColor($state ? 'success' : 'danger');
        });

        $this->rule('boolean');
    }
}
