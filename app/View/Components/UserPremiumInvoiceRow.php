<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UserPremiumInvoiceRow extends Component
{
    public function __construct(
        public string $label,
        public string $value,
        public string $unit = ''
    ) {
        $this->unit = blank($this->unit) ? __('names.rial') : $this->unit;
    }

    public function hasError(): bool
    {
        return $this->value < 0;
    }

    public function render(): View
    {
        return view('components.user-premium-invoice-row');
    }
}
