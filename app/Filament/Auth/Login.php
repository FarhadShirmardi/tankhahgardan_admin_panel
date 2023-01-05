<?php

namespace App\Filament\Auth;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Facades\Filament;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Yepsua\Filament\Forms\Components\Captcha;

/**
 * @property ComponentContainer $form
 */
class Login extends Component implements HasForms
{
    use InteractsWithForms;
    use WithRateLimiting;

    public string $phone_number = '';
    public string $password = '';
    public bool $remember = false;

    public function mount(): void
    {
        if (Filament::auth()->check()) {
            redirect()->intended(Filament::getUrl());
        }

        $this->form->fill([
            'phone_number' => ''
        ]);

    }

    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            $this->addError('phone_number', __('filament::login.messages.throttled', [
                'seconds' => $exception->secondsUntilAvailable,
                'minutes' => ceil($exception->secondsUntilAvailable / 60),
            ]));

            return null;
        }

        $data = $this->form->getState();

        if (!Filament::auth()->attempt([
            'phone_number' => formatPhoneNumber($data['phone_number']),
            'password' => $data['password'],
        ], $data['remember'])) {
            $this->addError('phone_number', __('filament::login.messages.failed'));

            return null;
        }

        return app(LoginResponse::class);
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('phone_number')
                ->telRegex('/^((0)(9){1}[0-9]{9})+$/')
                ->label(__('filament::login.fields.phone_number.label'))
                ->required(),
            TextInput::make('password')
                ->label(__('filament::login.fields.password.label'))
                ->password()
                ->required(),
            Captcha::make('captcha')->config('math'),
            Checkbox::make('remember')
                ->label(__('filament::login.fields.remember.label')),
        ];
    }

    public function render(): View
    {
        return view('filament::login')
            ->layout('filament::components.layouts.card', [
                'title' => __('filament::login.title'),
            ]);
    }
}
