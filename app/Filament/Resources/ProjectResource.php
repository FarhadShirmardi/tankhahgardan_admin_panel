<?php

namespace App\Filament\Resources;

use App\Enums\ActivityTypeEnum;
use App\Enums\ProjectTypeEnum;
use App\Filament\Resources\ProjectResource\Pages;
use App\Models\City;
use App\Models\ProjectReport;
use App\Models\Province;
use Ariaieboy\FilamentJalaliDatetime\JalaliDateTimeColumn;
use Exception;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class ProjectResource extends Resource
{
    protected static ?string $model = ProjectReport::class;

    public static function getModelLabel(): string
    {
        return __('filament::pages/project.title');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament::pages/project.title');
    }

    protected static ?string $navigationIcon = 'heroicon-o-library';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
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
                ColorColumn::make('project_type')
                    ->label('')
                    ->tooltip(fn (ProjectReport $record) => ActivityTypeEnum::from($record->project_type)->description())
                    ->getStateUsing(fn (ProjectReport $record) => ActivityTypeEnum::from($record->project_type)->color()),
                TextColumn::make('name')
                    ->label(__('names.project name'))
                    ->copyable(),
                TextColumn::make('province.name')
                    ->label(__('names.province')),
                TextColumn::make('city.name')
                    ->label(__('names.city')),
                TextColumn::make('type')
                    ->label(__('names.project type'))
                    ->enum(ProjectTypeEnum::columnValues()),
                JalaliDateTimeColumn::make('created_at')
                    ->label(__('names.project created at'))
                    ->extraAttributes([
                        'class' => 'ltr-col',
                    ])
                    ->dateTime()
                    ->sortable(),
                JalaliDateTimeColumn::make('max_time')
                    ->label(__('names.last record time'))
                    ->extraAttributes([
                        'class' => 'ltr-col',
                    ])
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('user_count')
                    ->label(__('names.total user count'))
                    ->sortable(),
                TextColumn::make('active_user_count')
                    ->label(__('names.active user count'))
                    ->sortable(),
                TextColumn::make('payment_count')
                    ->label(__('names.payment count'))
                    ->sortable(),
                TextColumn::make('receive_count')
                    ->label(__('names.receive count'))
                    ->sortable(),
                TextColumn::make('imprest_count')
                    ->label(__('names.imprest count'))
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('location')
                    ->label(__('names.project location'))
                    ->columns()
                    ->columnSpan(2)
                    ->form([
                        Forms\Components\Select::make('province_id')
                            ->label(__('names.province'))
                            ->searchable()
                            ->reactive()
                            ->options(
                                fn () => Province::query()->pluck('name', 'id')->toArray()
                            )
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                $province = Province::find($state);

                                if ($province) {
                                    $cityId = (int) $get('city_id');

                                    if ($cityId and $city = City::find($cityId)) {
                                        if ($city->province_id !== $province->id) {
                                            $set('city_id', null);
                                        }
                                    }
                                } else {
                                    $set('city_id', null);
                                }
                            }),
                        Forms\Components\Select::make('city_id')
                            ->label(__('names.city'))
                            ->searchable()
                            ->options(function (callable $get) {
                                $province = Province::find($get('province_id'));

                                if ($province) {
                                    return $province->cities->pluck('name', 'id');
                                }

                                return City::all()->pluck('name', 'id');
                            }),
                    ])
                    ->query(function (Builder $query, array $data) {
                        $provinceId = (int) $data['province_id'];
                        $cityId = (int) $data['city_id'];

                        if ($provinceId != 0) {
                            $query->where('province_id', $provinceId);
                        }

                        if ($cityId != 0) {
                            $query->where('city_id', $cityId);
                        }

                    }),
                Tables\Filters\SelectFilter::make('project_type')
                    ->label(__('names.last record time state'))
                    ->multiple()
                    ->options(ActivityTypeEnum::columnValues()),
                Tables\Filters\SelectFilter::make('type')
                    ->label(__('names.project type'))
                    ->multiple()
                    ->options(ProjectTypeEnum::columnValues()),
            ], layout: Tables\Filters\Layout::AboveContent)
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
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
            'index' => Pages\ListProjects::route('/'),
            'view' => Pages\ViewProject::route('/{record}'),
            'viewTeam' => Pages\ViewTeam::route('/{record}/teams/{subRecord}')
        ];
    }
}
