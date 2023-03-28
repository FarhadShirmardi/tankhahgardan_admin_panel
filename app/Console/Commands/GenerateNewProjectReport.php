<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\ProjectReport;
use App\Models\User;
use App\Models\UserReport;
use App\Services\ProjectReportService;
use App\Services\UserReportService;
use DB;
use Illuminate\Console\Command;
use Illuminate\Database\Query\Builder;
use Schema;

class GenerateNewProjectReport extends Command
{
    protected $signature = 'generate:new-project-report';

    protected $description = 'Command description';

    public function handle()
    {
        $columnList = Schema::getColumnListing('project_reports');
        $bar = $this->output->createProgressBar(Project::query()->whereNotExists(
            fn (Builder $query) => $query
                ->from('panel_project_reports')
                ->whereColumn('projects.id', 'panel_project_reports.id')
        )->count());
        Project::query()
            ->withoutEagerLoads()
            ->select(['id'])
            ->whereNotExists(
                fn (Builder $query) => $query
                    ->from('panel_project_reports')
                    ->whereColumn('projects.id', 'panel_project_reports.id')
            )
            ->chunk(1000, function ($ids) use ($bar, $columnList) {
                $ids = $ids->pluck('id')->toArray();
                $selectQuery = DB::query()->fromSub(ProjectReportService::getProjectsQuery($ids)->getQuery(), 'projects_query')->select($columnList);
                DB::connection('mysql_panel')->table('project_reports')->insertUsing($columnList, $selectQuery);
                $bar->advance(count($ids));
            });
    }
}
