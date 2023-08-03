<?php

namespace App\Forms\Components;

use Closure;
use Filament\Forms\Components;

class ProgressBar extends Components\Field
{
    use Components\Concerns\CanBeInline;
    use Components\Concerns\HasLabel;

    protected string $view = 'forms.components.progress-bar';

    protected bool | Closure | null $hasInlineLabel = true;

    protected string | Closure | null $color = 'primary';

    protected string | int | Closure | null $total;

    protected int | Closure | null $progressed;

    public function color(string | Closure $callback): static
    {
        $this->color = $callback;

        return $this;
    }

    public function getColor(): string
    {
        return $this->evaluate($this->color);
    }

    public function total(null | int | Closure $callback): static
    {
        $this->total = $callback;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->evaluate($this->total);
    }

    public function progressed(int | Closure $callback): static
    {
        $this->progressed = $callback;

        return $this;
    }

    public function getProgressed(): ?int
    {
        return $this->evaluate($this->progressed);
    }
}
