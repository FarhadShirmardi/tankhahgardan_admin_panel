<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Exports\AllUserExport;
use App\UserReport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Dashboard\ReportController;
use App\ProjectReport;
use App\Exports\AllProjectExport;

class ProjectReportExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $filter;
    private $link;

    /**
     * Create a new job instance.
     *
     * @param $filter
     * @param $link
     */
    public function __construct($filter, $link)
    {
        $this->filter = $filter;
        $this->link = $link;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $projectsQuery = ProjectReport::query();
        $reportController = app()->make(ReportController::class);
        $projectsQuery = $reportController->applyFilterProjectQuery($projectsQuery, $this->filter);
        $projects = $projectsQuery->get();
        $filename = 'projects.xlsx';
        Excel::store((new AllProjectExport($projects)), $filename);
    }
}
