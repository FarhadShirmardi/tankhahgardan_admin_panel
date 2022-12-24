<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Filament\Resources\TicketResource\RelationManagers;
use App\Models\Ticket;
use Ariaieboy\FilamentJalaliDatetime\JalaliDateTimeColumn;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
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
                Forms\Components\TextInput::make('user_id')
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('state')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ردیف')
                    ->rowIndex(),
                TextColumn::make('username')
                    ->label('نام کاربری')
                    ->getStateUsing(fn (Ticket $record) => reformatPhoneNumber($record->user->username))
                    ->copyable(),
                TextColumn::make('title')
                    ->words(4)
                    ->tooltip(fn (Ticket $record) => $record->title),
                TextColumn::make('state'),
                JalaliDateTimeColumn::make('lastTicketMessage.created_at')
                    ->extraAttributes([
                        'class' => 'ltr-col'
                    ])
                    ->sortable()
                    ->dateTime(),
            ])
            ->filters([
        //
    ])
        ->actions([
            Tables\Actions\ViewAction::make(),
        ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'view' => Pages\ViewTicket::route('/{record}'),
        ];
    }
}
