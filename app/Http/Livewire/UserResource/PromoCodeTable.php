<?php

namespace App\Http\Livewire\UserResource;

use App\Filament\Resources\CampaignResource\RelationManagers\PromoCodesRelationManager;
use App\Services\PromoCodeService;
use Filament\Tables;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class PromoCodeTable extends UserDetailTable
{
    public function getTableModelLabel(): string
    {
        return __('filament::pages/promoCode.title');
    }

    protected function getTableQuery(): Builder|Relation
    {
        if (!$this->isLoaded) {
            return PromoCodeService::promoCodesQuery($this->user)->whereRaw('false');
        }

        return PromoCodeService::promoCodesQuery($this->user);
    }

    protected function getDefaultTableRecordsPerPageSelectOption(): int
    {
        return 5;
    }

    protected function getTablePaginationPageName(): string
    {
        return 'promo_code_page';
    }

    protected function getTableColumns(): array
    {
        return PromoCodesRelationManager::getTableColumnsArray(hasUser: true);
    }

    protected function getTableHeaderActions(): array
    {
        return [
            Tables\Actions\CreateAction::make()
                ->form(PromoCodesRelationManager::getFormArray(hasUser: true))
                ->mutateFormDataUsing(function (array $data): array {
                    $data['user_id'] = $this->user->id;
                    $data['max_count'] = 1;

                    return $data;
                })
        ];
    }

    public function render(): View
    {
        return view('livewire.user-resource.promo-codes-table');
    }
}
