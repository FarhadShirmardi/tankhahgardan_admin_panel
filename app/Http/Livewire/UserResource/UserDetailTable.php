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

    public function mount(User $user): void
    {
        $this->user = $user;
    }

    public function loadData(): void
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
        return $this->isLoaded ? null : 'heroicon-o-download';
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return $this->isLoaded ? null : __('message.loading_data');
    }

    protected function getTablePaginationPageName(): string
    {
        return 'user_detail_page';
    }
}
