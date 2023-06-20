<?php

namespace App\Filament\Resources\TicketResource\RelationManagers;

use App\Filament\Resources\TicketResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class AllTicketsRelationManager extends RelationManager
{
    protected static string $relationship = 'allTickets';

    public static function getPluralModelLabel(): string
    {
        return __('filament::pages/ticket.all ticket title');
    }

    protected function getTableQuery(): Builder|Relation
    {
        return parent::getTableQuery()
            ->with(['user', 'lastTicketMessage'])
            ->withAggregate('lastTicketMessage', 'created_at');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(TicketResource::getTicketsTableColumns(showUsername: false))
            ->filters([
                //
            ])
            ->headerActions([
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
            ]);
    }    
}
