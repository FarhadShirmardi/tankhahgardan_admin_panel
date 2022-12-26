<?php

namespace App\Filament\Resources;

use App\Enums\TicketStateEnum;
use App\Filament\Resources\TicketResource\Pages;
use App\Filament\Resources\TicketResource\RelationManagers\TicketMessagesRelationManager;
use App\Models\Ticket;
use Ariaieboy\FilamentJalaliDatetime\JalaliDateTimeColumn;
use Derakht\Jalali\Jalali;
use Exception;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    public static function getModelLabel(): string
    {
        return __('filament::pages/ticket.title');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament::pages/ticket.title_plural');
    }

    protected static ?string $navigationIcon = 'tni-message-text-alt-o';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Placeholder::make('title')
                                    ->label(__('names.title'))
                                    ->content(fn (Ticket $record): ?string => $record->title),

                                Forms\Components\Placeholder::make('lastTicketMessage.created_at')
                                    ->label(__('names.last update'))
                                    ->extraAttributes(['class' => 'ltr-col'])
                                    ->content(fn (Ticket $record): ?string => Jalali::parse($record->lastTicketMessage->created_at)->toJalaliDateTimeString()),

                                Forms\Components\Placeholder::make('user.phone_number')
                                    ->label(__('names.phone number'))
                                    ->content(fn (Ticket $record): ?string => $record->user->phone_number),

                                Forms\Components\Placeholder::make('user.full_name')
                                    ->label(__('names.username'))
                                    ->content(fn (Ticket $record): ?string => $record->user->full_name),

                                Forms\Components\Select::make('state')
                                    ->label(__('names.state'))
                                    ->options(self::getStateOptions()),
                            ])
                            ->columns(2)
                            ->columnSpan(['lg' => 2]),
                    ]),
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make(__('names.table.row index'))
                    ->rowIndex(),
                TextColumn::make('username')
                    ->label(__('names.username'))
                    ->getStateUsing(fn (Ticket $record) => $record->user->username)
                    ->copyable(),
                TextColumn::make('title')
                    ->label(__('names.title'))
                    ->words(4)
                    ->tooltip(fn (Ticket $record) => $record->title),
                TextColumn::make('lastTicketMessage.text')
                    ->label(__('names.last message'))
                    ->words(4)
                    ->tooltip(fn (Ticket $record) => $record->lastTicketMessage->text),
                TextColumn::make('state')
                    ->label(__('names.state'))
                    ->getStateUsing(fn (Ticket $record) => $record->state->description()),
                JalaliDateTimeColumn::make('lastTicketMessage.created_at')
                    ->label(__('names.last update'))
                    ->extraAttributes([
                        'class' => 'ltr-col',
                    ])
                    ->sortable()
                    ->dateTime(),
            ])
            ->filters(
                [
                    SelectFilter::make('state')
                        ->label(__('names.state'))
                        ->multiple()
                        ->options(self::getStateOptions())
                        ->default([
                            TicketStateEnum::PENDING->value,
                            TicketStateEnum::OPENED->value,
                        ])
                        ->query(fn (Builder $query, array $state) => $query->when(filled($state['values']), fn ($q) => $q->whereIn('state', $state['values']))),
                ],
                layout: Tables\Filters\Layout::AboveContent
            )
            ->actions([
                //Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            TicketMessagesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
            'messageCreate' => Pages\CreateTicketMessage::route('/{record}/ticketMessage')
        ];
    }

    private static function getStateOptions(): array
    {
        return collect(TicketStateEnum::cases())->mapWithKeys(function ($item) {
            return [$item->value => $item->description()];
        })->toArray();
    }
}
