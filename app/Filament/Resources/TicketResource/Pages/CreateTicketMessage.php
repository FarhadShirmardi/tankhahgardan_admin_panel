<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Enums\SmsTemplateEnum;
use App\Enums\TicketStateEnum;
use App\Filament\Resources\TicketResource;
use App\Models\Ticket;
use App\Models\TicketMessage;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use GuzzleHttp\Client;
use Illuminate\Contracts\View\View;

/**
 * @property Forms\ComponentContainer|View|mixed|null $form
 */
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
            Forms\Components\Textarea::make('text')
                ->label(__('names.message text'))
                ->required(),

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
        $this->ticket->update(['state' => TicketStateEnum::ANSWERED]);

        $http = new Client();
        collect($this->image)->map(fn ($item) => $http->post(
            config('app.app_direct_url')."/ticketMessage/$ticketMessage->id/images",
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
        ));

        $user = $this->ticket->user;
        $http->post(
            config('app.app_direct_url')."/sendSms",
            [
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'form_params' => [
                    'receptor' => $user->phone_number,
                    'template' => SmsTemplateEnum::TICKET_RESPONSE->value,
                    'token1' => 'کاربر',
                ],
            ]
        );

        Notification::make()
            ->success()
            ->title(__('filament::pages/ticket-message.ticket message created successfully'))
            ->send();

        $this->redirect(TicketResource::getUrl('edit', ['record' => $this->ticket->id]));
    }
}
