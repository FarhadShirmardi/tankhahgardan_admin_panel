<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\ProjectReport;
use App\Services\ProjectReportService;
use DB;
use Illuminate\Console\Command;
use Schema;

class GenerateProjectReport extends Command
{
    protected $signature = 'generate:project-report';

    protected $description = 'Command description';

    public function handle()
    {
        ProjectReport::query()->truncate();
        $columnList = Schema::getColumnListing('project_reports');
        $bar = $this->output->createProgressBar(Project::query()->count());
        Project::query()
            ->withoutEagerLoads()
            ->select(['id'])
            ->chunk(1000, function ($ids) use ($bar, $columnList) {
                $ids = $ids->pluck('id')->toArray();
                $selectQuery = DB::query()->fromSub(ProjectReportService::getProjectsQuery($ids)->getQuery(), 'projects_query')->select($columnList);
                DB::table('project_reports')->insertUsing($columnList, $selectQuery);
                $bar->advance(count($ids));
            });
    }
}
