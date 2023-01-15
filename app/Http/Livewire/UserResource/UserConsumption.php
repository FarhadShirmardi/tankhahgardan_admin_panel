<?php

namespace App\Http\Livewire\UserResource;

use App\DataObjects\UserPremiumData;
use App\Forms\Components\LabeledIcon;
use App\Forms\Components\LineGraph;
use App\Models\User;
use Filament\Forms;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
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
                ->columns(3)
                ->schema([
                    LabeledIcon::make('read_sms_limit')
                        ->label('خواندن پیامک'),
                ]),
        ];
    }

    public function render(): View
    {
        return view('livewire.user-resource.user-consumption');
    }
}
