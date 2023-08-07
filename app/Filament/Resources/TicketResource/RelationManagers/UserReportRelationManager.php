<?php

namespace App\Filament\Resources\TicketResource\RelationManagers;

use App\Filament\Resources\TicketResource;
use App\Filament\Resources\UserResource;
use App\Models\UserReport;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class UserReportRelationManager extends RelationManager
{
    protected static string $relationship = 'userReport';

    public static function getPluralModelLabel(): string
    {
        return __('filament::pages/ticket.user report title');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(UserResource::getColumns())
            ->filters([
                //
            ])
            ->headerActions([
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn (UserReport $record) => UserResource::getUrl('view', ['record' => $record->id])),
            ])
            ->bulkActions([
            ]);
    }    
}
