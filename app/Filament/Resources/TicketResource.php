<?php

namespace App\Filament\Resources;

use App\Enums\TicketStateEnum;
use App\Filament\Components\JalaliDateTimeColumn;
use App\Filament\Components\RowIndexColumn;
use App\Filament\Resources\TicketResource\Pages;
use App\Filament\Resources\TicketResource\RelationManagers\AllTicketsRelationManager;
use App\Filament\Resources\TicketResource\RelationManagers\TicketMessagesRelationManager;
use App\Models\Ticket;
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

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?int $navigationSort = 3;

    protected static function getNavigationBadge(): ?string
    {
        return Ticket::query()
            ->whereIn('state', [TicketStateEnum::PENDING, TicketStateEnum::OPENED])
            ->count();
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['user', 'lastTicketMessage'])
            ->withAggregate('lastTicketMessage', 'created_at');
    }

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
                                    ->content(fn (Ticket $record): ?string => Jalali::parse($record->lastTicketMessage?->created_at)->toJalaliDateTimeString()),

                                Forms\Components\Placeholder::make('user.phone_number')
                                    ->label(__('names.phone number'))
                                    ->content(fn (Ticket $record): ?string => $record->user->phone_number),

                                Forms\Components\Placeholder::make('user.full_name')
                                    ->label(__('names.username'))
                                    ->content(fn (Ticket $record): ?string => $record->user->full_name),

                                Forms\Components\Select::make('state')
                                    ->label(__('names.state'))
                                    ->options(TicketStateEnum::columnValues()),
                            ])
                            ->columns(2)
                            ->columnSpan(['lg' => 2]),
                    ]),
            ]);
    }

    public static function getTicketsTableColumns(bool $showUsername = true): array
    {
        return [
            RowIndexColumn::make(),
            TextColumn::make('username')
                ->label(__('names.username'))
                ->words(3)
                ->hidden(!$showUsername)
                ->tooltip(fn (Ticket $record) => $record->user->username)
                ->getStateUsing(fn (Ticket $record) => $record->user->username)
                ->copyable(),
            TextColumn::make('title')
                ->label(__('names.title'))
                ->words(4)
                ->tooltip(fn (Ticket $record) => $record->title),
            TextColumn::make('lastTicketMessage.text')
                ->label(__('names.last message'))
                ->words(4)
                ->tooltip(fn (Ticket $record) => $record->lastTicketMessage?->text),
            TextColumn::make('state')
                ->label(__('names.state'))
                ->getStateUsing(fn (Ticket $record) => $record->state->description()),
            JalaliDateTimeColumn::make('last_ticket_message_created_at')
                ->label(__('names.last update'))
                ->sortable()
                ->dateTime(),
        ];
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::getTicketsTableColumns())
            ->defaultSort('last_ticket_message_created_at', 'desc')
            ->filters(
                [
                    SelectFilter::make('state')
                        ->label(__('names.state'))
                        ->multiple()
                        ->options(TicketStateEnum::columnValues())
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
            TicketMessagesRelationManager::class,
            AllTicketsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
            'messageCreate' => Pages\CreateTicketMessage::route('/{record}/ticketMessage'),
            'messageEdit' => Pages\EditTicketMessage::route('/{record}/ticketMessage/{subRecord}')
        ];
    }
}
