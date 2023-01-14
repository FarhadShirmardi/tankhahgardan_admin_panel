<?php

namespace App\Http\Livewire\UserResource;

use App\Enums\UserPremiumStateEnum;
use App\Models\User;
use App\Models\UserReport;
use Derakht\Jalali\Jalali;
use Filament\Forms;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class UserDetail extends Component implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    private UserReport $userReport;

    public function mount(User $user)
    {
        $this->userReport = UserReport::findOrFail($user->id);
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Card::make()
                ->columns(3)
                ->inlineLabel()
                ->schema([
                    Forms\Components\Placeholder::make('user_state')
                        ->label(__('names.user state'))
                        ->content(fn (UserReport $record) => UserPremiumStateEnum::from($record->user_state)->description()),

                    Forms\Components\Placeholder::make('registered_at')
                        ->label(__('names.registered at'))
                        ->extraAttributes(['class' => 'ltr-col'])
                        ->content(fn (UserReport $record): ?string => Jalali::parse($record->registered_at)->toJalaliDateTimeString()),

                    Forms\Components\Placeholder::make('max_time')
                        ->label(__('names.last record time'))
                        ->extraAttributes(['class' => 'ltr-col'])
                        ->content(fn (UserReport $record): ?string => $record->max_time ? Jalali::parse($record->max_time)->toJalaliDateTimeString() : '-'),

                    Forms\Components\Placeholder::make('payment_count')
                        ->label(__('names.payment count'))
                        ->content(fn (UserReport $record): ?string => $record->payment_count),

                    Forms\Components\Placeholder::make('receive_count')
                        ->label(__('names.receive count'))
                        ->content(fn (UserReport $record): ?string => $record->receive_count),

                    Forms\Components\Placeholder::make('imprest_count')
                        ->label(__('names.imprest count'))
                        ->content(fn (UserReport $record): ?string => $record->imprest_count),

                    Forms\Components\Placeholder::make('image_count')
                        ->label(__('names.image count'))
                        ->content(fn (UserReport $record): ?string => $record->image_count),

                    Forms\Components\Placeholder::make('image_size')
                        ->label(__('names.image size'))
                        ->content(fn (UserReport $record): ?string => $record->image_size),
                ]),
        ];
    }

    protected function getFormModel(): Model|string|null
    {
        return $this->userReport;
    }

    public function render(): View
    {
        return view('livewire.user-resource.user-detail');
    }
}
