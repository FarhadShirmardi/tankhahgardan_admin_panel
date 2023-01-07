<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use App\Models\Ticket;
use App\Models\TicketMessage;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use GuzzleHttp\Client;

class CreateTicketMessage extends Page
{
    use Forms\Concerns\InteractsWithForms;

    protected static string $resource = TicketResource::class;

    protected static string $view = 'filament.resources.ticket-resource.pages.create-ticket-message';

    protected function getTitle(): string
    {
        return __('names.create ticket');
    }

    public Ticket $ticket;
    public TicketMessage $ticketMessage;

    public string $text;

    public function mount(Ticket $record)
    {
        $this->ticket = $record;
        //dd(config('app.app_url'));
        $this->ticketMessage = new TicketMessage();
        $this->form->fill([
            'text' => '',
            'image' => '',
        ]);
    }

    protected function getFormModel(): TicketMessage
    {
        return $this->ticketMessage;
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('text')
                ->label(__('names.message text'))
                ->required()
                ->maxLength(255),

            Forms\Components\FileUpload::make('image')
                ->label(__('names.image'))
                ->label('Image')
                ->disk('public')
                ->multiple()
                ->image()
            // ...
        ];
    }

    public function save()
    {
        /** @var TicketMessage $ticketMessage */
        $ticketMessage = $this->ticket->ticketMessages()->create([
            ...$this->form->getState(),
            'panel_user_id' => auth()->id(),
        ]);

        collect($this->image)->map(function ($item) use ($ticketMessage) {
            $http = new Client();
            $http->post(
                config('app.app_url')."/ticketMessage/$ticketMessage->id/images",
                [
                    'headers' => [
                        'Accept' => 'application/json',
                    ],
                    'multipart' => [
                        [
                            'name' => 'image',
                            'filename' => $item,
                            'contents' => file_get_contents(storage_path().'/app/public/'.$item),
                        ],
                    ],
                ]
            );
        });

        Notification::make()
            ->success()
            ->title(__('filament::resources/pages/create-record.messages.created'))
            ->send();

        $this->redirect(TicketResource::getUrl('edit', ['record' => $this->ticket->id]));
    }
}
