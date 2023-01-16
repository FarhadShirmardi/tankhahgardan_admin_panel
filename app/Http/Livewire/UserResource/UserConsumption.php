<?php

namespace App\Http\Livewire\UserResource;

use App\DataObjects\UserPremiumData;
use App\Forms\Components\BooleanLabeledIcon;
use App\Forms\Components\LineGraph;
use App\Forms\Components\ProgressBar;
use App\Models\User;
use Closure;
use Filament\Forms;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class UserConsumption extends Component implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    public function mount(User $user)
    {
        $data = new UserPremiumData($user);
        $this->form->fill($data->toArray());
        //dd($data);
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Section::make(__('names.user_consumption'))
                ->columns(4)
                ->schema([
                    ProgressBar::make('transaction_count')
                        ->label(__('names.consumption.transaction count'))
                        ->total($this->getLimitClosure('transaction_count'))
                        ->progressed($this->getRemainClosure('transaction_count')),
                    ProgressBar::make('image_count')
                        ->label(__('names.consumption.image count'))
                        ->total($this->getLimitClosure('image_count'))
                        ->progressed($this->getRemainClosure('image_count')),
                    ProgressBar::make('project_count')
                        ->label(__('names.consumption.project count'))
                        ->total($this->getLimitClosure('project_count'))
                        ->progressed($this->getRemainClosure('project_count')),
                    ProgressBar::make('imprest_count')
                        ->label(__('names.consumption.imprest count'))
                        ->total($this->getLimitClosure('imprest_count'))
                        ->progressed($this->getRemainClosure('imprest_count')),
                    ProgressBar::make('user_count')
                        ->label(__('names.consumption.user count'))
                        ->total($this->getLimitClosure('user_count'))
                        ->progressed($this->getRemainClosure('user_count')),
                    Forms\Components\Placeholder::make('transaction_image_count')
                        ->label(__('names.consumption.transaction image count'))
                        ->inlineLabel()
                        ->content($this->getLimitClosure('transaction_image_count')),
                    ProgressBar::make('pdf_count')
                        ->label(__('names.consumption.pdf count'))
                        ->total($this->getLimitClosure('pdf_count'))
                        ->progressed($this->getRemainClosure('pdf_count')),
                    BooleanLabeledIcon::make('monthly_report_limit')
                        ->label(__('names.consumption.monthly report')),
                    BooleanLabeledIcon::make('hashtag_report_limit')
                        ->label(__('names.consumption.hashtag report')),
                    BooleanLabeledIcon::make('read_sms_limit')
                        ->label(__('names.consumption.read sms')),
                    BooleanLabeledIcon::make('account_title_import_limit')
                        ->label(__('names.consumption.account title import')),
                    BooleanLabeledIcon::make('transaction_print_limit')
                        ->label(__('names.consumption.transaction print')),
                    BooleanLabeledIcon::make('excel_count_limit')
                        ->label(__('names.consumption.excel count')),
                    BooleanLabeledIcon::make('memo_count_limit')
                        ->label(__('names.consumption.memo count')),
                    BooleanLabeledIcon::make('reminder_count_limit')
                        ->label(__('names.consumption.reminder count')),
                    BooleanLabeledIcon::make('task_count_limit')
                        ->label(__('names.consumption.task count')),
                    BooleanLabeledIcon::make('transaction_duplicate_limit')
                        ->label(__('names.consumption.transaction duplicate')),
                    BooleanLabeledIcon::make('contact_report_limit')
                        ->label(__('names.consumption.contact report')),
                    BooleanLabeledIcon::make('offline_transaction_limit')
                        ->label(__('names.consumption.offline transaction')),
                    BooleanLabeledIcon::make('transaction_import_limit')
                        ->label(__('names.consumption.transaction import')),
                    BooleanLabeledIcon::make('accountant_report_limit')
                        ->label(__('names.consumption.accountant import')),
                    BooleanLabeledIcon::make('monthly_budget_limit')
                        ->label(__('names.consumption.monthly budget')),
                    BooleanLabeledIcon::make('transaction_copy_limit')
                        ->label(__('names.consumption.transaction copy')),
                    BooleanLabeledIcon::make('admin_transaction_limit')
                        ->label(__('names.consumption.admin transaction')),
                    BooleanLabeledIcon::make('team_limit')
                        ->label(__('names.consumption.team limit')),
                    BooleanLabeledIcon::make('admin_panel_limit')
                        ->label(__('names.consumption.admin panel')),
                    BooleanLabeledIcon::make('imprest_budget_limit')
                        ->label(__('names.consumption.imprest budget')),
                    BooleanLabeledIcon::make('team_level_limit')
                        ->label(__('names.consumption.team level')),
                ]),
        ];
    }

    public function render(): View
    {
        return view('livewire.user-resource.user-consumption');
    }

    private function getLimitClosure(string $key): Closure
    {
        return fn (Closure $get) => $get($key.'_limit') == 1000000 ? null : $get($key.'_limit');
    }

    private function getRemainClosure(string $key): Closure
    {
        return fn (Closure $get) => $get($key.'_limit') - $get($key.'_remain');
    }
}
