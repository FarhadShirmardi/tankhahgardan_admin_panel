<?php

namespace App\Http\Livewire\UserResource;

use App\Data\PayInvoiceData;
use App\Enums\PremiumDurationEnum;
use App\Enums\UserStatusTypeEnum;
use App\Models\Invoice;
use App\Services\PremiumService;
use Ariaieboy\FilamentJalaliDatetime\JalaliDateTimeColumn;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class InvoicesTable extends UserDetailTable
{
    protected function getTableQuery(): Builder|Relation
    {
        if (!$this->isLoaded) {
            return $this->user->invoices()->whereRaw('false')->getQuery();
        }

        return $this->user->invoices()
            ->orderByDesc('end_date')
            ->getQuery();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make(__('names.table.row index'))->rowIndex(),
            Tables\Columns\ColorColumn::make('premiumPlan.type')
                ->label(__('names.plan'))
                ->tooltip(fn (Invoice $record) => $record->premiumPlan->type->title())
                ->alignCenter()
                ->getStateUsing(fn (Invoice $record) => $record->premiumPlan->type->color()),
            Tables\Columns\BadgeColumn::make('duration_id')
                ->label(__('names.plan type'))
                ->enum(PremiumDurationEnum::columnValues())
                ->color(static fn ($state) => PremiumDurationEnum::from($state)->color()),
            JalaliDateTimeColumn::make('start_date')
                ->label(__('names.start date'))
                ->extraAttributes([
                    'class' => 'ltr-col',
                ])
                ->dateTime()
                ->sortable(),
            JalaliDateTimeColumn::make('end_date')
                ->label(__('names.end date'))
                ->extraAttributes([
                    'class' => 'ltr-col',
                ])
                ->dateTime()
                ->sortable(),
            TextColumn::make('payable_amount')
                ->formatStateUsing(fn ($state) => formatPrice($state))
                ->label(__('names.total amount column'))
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\Action::make('Delete Invoice')
                ->label(__('names.delete invoice'))
                ->visible(fn (Tables\Actions\Action $action) => $action->getRecord()->status->isPending())
                ->icon('lucide-x')
                ->action(function (Invoice $record) {
                    $record->status = UserStatusTypeEnum::FAILED;
                    $record->save();

                    Notification::make()
                        ->success()
                        ->title(__('message.invoice deleted successfully'))
                        ->send();

                    $this->emitSelf('$refresh');
                })
                ->requiresConfirmation(),
            Tables\Actions\Action::make('Pay Invoice')
                ->label(__('names.pay invoice'))
                ->visible(fn (Tables\Actions\Action $action) => $action->getRecord()->status->isPending())
                ->icon('lucide-credit-card')
                ->action(function (Invoice $record, array $data) {
                    $success = PremiumService::payInvoice($record, PayInvoiceData::from($data));

                    if ($success) {
                        Notification::make()
                            ->success()
                            ->title(__('message.invoice payed successfully'))
                            ->send();

                        $this->emitSelf('$refresh');
                    }
                })
                ->modalHeading(__('names.pay invoice'))
                ->form([
                    Textarea::make('text')
                        ->label(__('names.description'))
                        ->inlineLabel()
                        ->required()
                ])
                ->modalContent(fn (Invoice $record) => \view('filament.resources.user-status-resource.modals.pay-invoice', ['record' => $record]))
        ];
    }

    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }

    public function render(): View
    {
        return view('livewire.user-resource.invoices-table');
    }
}
