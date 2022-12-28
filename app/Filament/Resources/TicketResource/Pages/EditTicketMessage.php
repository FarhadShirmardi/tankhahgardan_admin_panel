<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use App\Models\Image;
use App\Models\Ticket;
use App\Models\TicketMessage;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\Layout\Grid;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;

class EditTicketMessage extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static string $resource = TicketResource::class;

    protected static string $view = 'filament.resources.ticket-resource.pages.edit-ticket-message';

    public Ticket $ticket;
    public TicketMessage $ticketMessage;
    public Collection $oldImages;

    public string $text;

    public function mount(Ticket $record, TicketMessage $subRecord)
    {
        $this->ticket = $record;
        $this->ticketMessage = $subRecord;
        $this->oldImages = $subRecord->images()->get()->map(fn (Image $image) => [
            'id' => $image->id,
            'path' => config('app.app_url').'/images?path='.$image->path,
        ]);
        $this->form->fill([
            'text' => $subRecord->text,
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
                ->required()
                ->maxLength(255),

            Forms\Components\FileUpload::make('image')
                ->label('Image')
                ->disk('public')
                ->multiple()
                ->maxFiles(3)
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
