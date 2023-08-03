<?php

namespace App\Traits;

use Closure;

trait HasIcon
{
    protected string | Closure | null $icon = 'heroicon-s-check-circle';

    protected string | Closure | null $iconColor = null;

    public function iconColor(string | Closure | null $iconColor): static
    {
        $this->iconColor = $iconColor;

        return $this;
    }

    public function icon(string | Closure | null $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function getIconColor(): ?string
    {
        return $this->evaluate($this->iconColor);
    }

    public function getIcon(): ?string
    {
        return $this->evaluate($this->icon);
    }
}
