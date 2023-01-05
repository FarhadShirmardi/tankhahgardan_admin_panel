<?php

namespace App\Filament\Resources\TicketResource\RelationManagers;

use App\Filament\Resources\TicketResource;
use App\Filament\Resources\TicketResource\Pages\CreateTicketMessage;
use App\Models\TicketMessage;
use Ariaieboy\FilamentJalaliDatetime\JalaliDateTimeColumn;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasRelationshipTable;
use Illuminate\Database\Eloquent\Model;

class TicketMessagesRelationManager extends RelationManager
{
    protected static string $relationship = 'ticketMessages';

    public static function getModelLabel(): string
    {
        return __('filament::pages/ticket-message.title');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament::pages/ticket-message.title_plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('text')
                    ->required()
                    ->maxLength(255),

                Forms\Components\SpatieMediaLibraryFileUpload::make('images')
                    ->label('Image')
                    ->image()
                    ->multiple()
                    ->saveUploadedFileUsing(function (Model $record) {
                        dd($record);
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make(__('names.table.row index'))
                    ->rowIndex(),

                Tables\Columns\TextColumn::make('user_text')
                    ->label('متن کاربر')
                    ->getStateUsing(fn (TicketMessage $record) => ($record->panel_user_id == null ? $record->text : ' - ')),

                Tables\Columns\TextColumn::make('panel_text')
                    ->label('متن پشتیبان')
                    ->getStateUsing(fn (TicketMessage $record) => $record->panel_user_id != null ? $record->text : ' - '),

                JalaliDateTimeColumn::make('created_at')
                    ->label('تاریخ و ساعت')
                    ->extraAttributes([
                        'class' => 'ltr-col',
                    ])
                    ->sortable()
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->headerActions([

                Tables\Actions\CreateAction::make()
                    ->url(function (RelationManager $livewire) {
                        return TicketResource::getUrl('messageCreate', ['record' => $livewire->ownerRecord->id]);
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(function (RelationManager $livewire, Model $record) {
                        return TicketResource::getUrl('messageEdit', [
                            'record' => $livewire->ownerRecord->id,
                            'subRecord' => $record->id
                        ]);
                    })
            ])
            ->bulkActions([
            ]);
    }
}
