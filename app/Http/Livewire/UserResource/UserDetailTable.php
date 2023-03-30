<?php

namespace App\Http\Livewire\UserResource;

use App\Models\User;
use Filament\Tables;
use Livewire\Component;

class UserDetailTable extends Component implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    public User $user;

    public bool $isLoaded = false;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function loadData()
    {
        $this->isLoaded = true;
    }

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'created_at';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }

    protected function getTableEmptyStateIcon(): ?string
    {
        return 'heroicon-o-download';
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return __('message.loading_data');
    }
}
