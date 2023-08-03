<div wire:init="loadData" @class(['animate-pulse' => !$this->isLoaded])>
    {{ $this->table }}
</div>
