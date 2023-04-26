<?php

namespace App\Http\Livewire\ProjectResource;

use App\Enums\CurrencyEnum;
use App\Enums\ProjectTypeEnum;
use App\Models\City;
use App\Models\Project;
use App\Models\ProjectReport;
use App\Models\Province;
use Derakht\Jalali\Jalali;
use Filament\Forms;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

/**
 * @property Forms\ComponentContainer|View|mixed|null $form
 */
class ProjectDetail extends Component implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    private ProjectReport $projectReport;
    private Project $project;

    public function mount(Project $project): void
    {
        $this->project = $project;
        $this->projectReport = ProjectReport::findOrFail($project->id);
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Card::make()
                ->columns(3)
                ->inlineLabel()
                ->schema([
                    Forms\Components\Placeholder::make('type')
                        ->label(__('names.project type'))
                        ->content(fn (ProjectReport $record) => ProjectTypeEnum::from($record->type)->description()),

                    Forms\Components\Placeholder::make('created_at')
                        ->label(__('names.created at'))
                        ->extraAttributes(['class' => 'ltr-col'])
                        ->content(fn (ProjectReport $record): ?string => Jalali::parse($record->created_at)->toJalaliDateTimeString()),

                    Forms\Components\Placeholder::make('max_time')
                        ->label(__('names.last record time'))
                        ->extraAttributes(['class' => 'ltr-col'])
                        ->content(fn (ProjectReport $record): ?string => $record->max_time ? Jalali::parse($record->max_time)->toJalaliDateTimeString() : '-'),

                    Forms\Components\Placeholder::make('province')
                        ->label(__('names.province'))
                        ->content(fn (ProjectReport $record) => Province::query()->find( $record->province_id)->name),

                    Forms\Components\Placeholder::make('city')
                        ->label(__('names.city'))
                        ->content(fn (ProjectReport $record) => City::query()->find( $record->city_id)->name),

                    Forms\Components\Placeholder::make('unit')
                        ->label(__('names.currency unit'))
                        ->content(fn () => CurrencyEnum::from($this->project->currency)->symbolFa()),
                ]),
        ];
    }

    protected function getFormModel(): Model|string|null
    {
        return $this->projectReport;
    }

    public function render(): View
    {
        return view('livewire.project-resource.project-detail');
    }
}
