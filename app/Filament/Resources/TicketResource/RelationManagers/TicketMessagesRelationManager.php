<?php

namespace App\Filament\Resources\TicketResource\RelationManagers;

use App\Filament\Components\JalaliDateTimeColumn;
use App\Filament\Components\RowIndexColumn;
use App\Filament\Resources\ProjectResource;
use App\Filament\Resources\TicketResource;
use App\Models\TicketMessage;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
                    ->multiple(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                RowIndexColumn::make(),
                Tables\Columns\TextColumn::make('user_text')
                    ->label('متن کاربر')
                    ->tooltip(fn (TicketMessage $record) => ($record->panel_user_id == null ? $record->text : ' - '))
                    ->getStateUsing(fn (TicketMessage $record) => Str::limit(($record->panel_user_id == null ? $record->text : ' - '), 40)),
                Tables\Columns\TextColumn::make('panel_text')
                    ->label('متن پشتیبان')
                    ->tooltip(fn (TicketMessage $record) => $record->panel_user_id != null ? $record->text : ' - ')
                    ->getStateUsing(fn (TicketMessage $record) => Str::limit(($record->panel_user_id != null ? $record->text : ' - '), 40)),
                Tables\Columns\TextColumn::make('project')
                    ->label('پروژه')
                    ->url(fn (TicketMessage $record) => ($record->project_user_id != null and $record->getProjectUser()?->project_id != null) ? ProjectResource::getUrl('view', ['record' => $record->getProjectUser()?->project_id]) : null)
                    ->getStateUsing(fn (TicketMessage $record) => $record->project_user_id != null ? Str::words($record->getProjectUser()?->getProjectTeamText(), 2) : ' - '),
                Tables\Columns\IconColumn::make('has_image')
                    ->label('دارای عکس؟')
                    ->boolean()
                    ->getStateUsing(fn (TicketMessage $record) => $record->images()->count() != 0),
                JalaliDateTimeColumn::make('created_at')
                    ->label('تاریخ و ساعت')
                    ->sortable()
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('names.response to ticket'))
                    ->icon('lucide-reply')
                    ->url(function (RelationManager $livewire) {
                        return TicketResource::getUrl('messageCreate', ['record' => $livewire->ownerRecord->id]);
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(fn (TicketMessage $record) => is_null($record->panel_user_id) ? __('filament::resources/pages/view-record.form.tab.label') : __('filament::resources/pages/edit-record.form.tab.label'))
                    ->icon(fn (TicketMessage $record) => is_null($record->panel_user_id) ? 'heroicon-s-eye' : 'heroicon-s-pencil')
                    ->url(function (RelationManager $livewire, Model $record) {
                        return TicketResource::getUrl('messageEdit', [
                            'record' => $livewire->ownerRecord->id,
                            'subRecord' => $record->id,
                        ]);
                    }),
            ])
            ->bulkActions([
            ]);
    }
}
