<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use App\Models\Image;
use App\Models\Ticket;
use App\Models\TicketMessage;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;

class EditTicketMessage extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static string $resource = TicketResource::class;

    protected static string $view = 'filament.resources.ticket-resource.pages.edit-ticket-message';

    protected function getTitle(): string
    {
        return __('names.edit ticket');
    }

    public Ticket $ticket;
    public TicketMessage $ticketMessage;
    public Collection $oldImages;

    public string $text;
    private bool $isUser = false;

    public function mount(Ticket $record, TicketMessage $subRecord)
    {
        $this->isUser = $subRecord->panel_user_id == null;
        $this->ticket = $record;
        $this->ticketMessage = $subRecord;
        $this->oldImages = $subRecord->images()->get()->map(fn (Image $image) => [
            'id' => $image->id,
            'path' => config('app.app_url').'/images?path='.$image->path,
            'is_deleted' => false,
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
            Forms\Components\Textarea::make('text')
                ->label(__('names.message text'))
                ->when(!$this->isUser)
                ->required(),

            Forms\Components\Placeholder::make('user_text')
                ->label(__('names.message text'))
                ->when($this->isUser)
                ->content(fn (TicketMessage $record): ?string => $record->text),

            Forms\Components\FileUpload::make('image')
                ->when(!$this->isUser)
                ->label(__('names.image'))
                ->disk('public')
                ->multiple()
                ->image()
            // ...
        ];
    }

    public function deleteImage($id)
    {
        $this->oldImages = $this->oldImages->map(function ($oldImage) use ($id) {
            if ($oldImage['id'] == $id) {
                $oldImage['is_deleted'] = true;
            }

            return $oldImage;
        });
    }

    public function response()
    {
        $this->redirect(TicketResource::getUrl('messageCreate', ['record' => $this->ticket->id]));
    }

    public function save()
    {
        foreach ($this->oldImages as $oldImage) {
            if ($oldImage['is_deleted']) {
                Image::query()->where('id', $oldImage['id'])->delete();
            }
        }

        $this->ticketMessage->update([
            ...$this->form->getState()
        ]);

        collect($this->image)->map(function ($item) {
            $http = new Client();
            $http->post(
                config('app.app_direct_url')."/ticketMessage/{$this->ticketMessage->id}/images",
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
            ->title(__('filament::resources/pages/edit-record.messages.saved'))
            ->send();

        $this->redirect(TicketResource::getUrl('edit', ['record' => $this->ticket->id]));
    }
}
