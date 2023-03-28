<?php

namespace App\Http\Livewire\UserResource;

use App\Enums\ProjectUserTypeEnum;
use App\Filament\Resources\ProjectResource;
use App\Models\Image;
use App\Models\Imprest;
use App\Models\Payment;
use App\Models\ProjectUser;
use App\Models\Receive;
use App\Models\User;
use App\Models\UserReport;
use Closure;
use Exception;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Livewire\Component;

class DevicesTable extends Component implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    public User $user;

    private bool $isLoaded = false;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function loadData()
    {
        $this->isLoaded = true;
    }

    protected function getTableQuery(): Builder|Relation
    {
        if (!$this->isLoaded) {
            return $this->user->devices()->whereRaw('false')->getQuery();
        }

        return $this->user->devices()
            ->select([
                'devices.id',
                'devices.serial',
                'devices.platform',
                'devices.model',
                'devices.app_version',
                'devices.os_version',
            ])
            ->orderByDesc('devices.created_at')
            ->getQuery();
    }

    protected function getTableRecordsPerPage(): int
    {
        return 5;
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

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make(__('names.table.row index'))
                ->rowIndex(),
        ];
    }

    public function render(): View
    {
        return view('livewire.user-resource.devices-table');
    }
}
