<?php

namespace App\Http\Livewire\UserResource;

use App\Enums\TicketStateEnum;
use App\Filament\Resources\TicketResource;
use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Filament\Forms;

/**
 * @property Forms\ComponentContainer|View|mixed|null $form
 */
class NewTicket extends Component implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    public User $user;

    public string $title;

    public function mount(User $user): void
    {
        $this->user = $user;
    }

    protected function getFormSchema(): array
    {
        return [
            Textarea::make('title')
                ->label(__('names.title'))
                ->inlineLabel()
                ->required()
        ];
    }

    public function save()
    {
        $data = $this->form->getState();
        $ticket = $this->user->tickets()->create([
            'title' => $data['title'],
            'state' => TicketStateEnum::ANSWERED
        ]);

        Notification::make()
            ->success()
            ->title(__('filament::resources/pages/create-record.messages.created'))
            ->send();

        $this->redirect(TicketResource::getUrl('edit', ['record' => $ticket]));
    }

    public function render()
    {
        return view('livewire.user-resource.new-ticket');
    }
}
