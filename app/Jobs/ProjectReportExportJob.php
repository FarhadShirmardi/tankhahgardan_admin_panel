<?php

namespace App\Jobs;

use App\Exports\AllProjectExport;
use App\Http\Controllers\Dashboard\ReportController;
use App\Models\PanelFile;
use App\Models\ProjectReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class ProjectReportExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $filter;
    /**
     * @var PanelFile
     */
    private $panelFile;

    /**
     * Create a new job instance.
     *
     * @param $filter
     */
    public function __construct($filter, PanelFile $panelFile)
    {
        $this->filter = $filter;
        $this->panelFile = $panelFile;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            \DB::beginTransaction();
            $projectsQuery = ProjectReport::query();
            $reportController = app()->make(ReportController::class);
            $projectsQuery = $reportController->applyFilterProjectQuery($projectsQuery, $this->filter);
            $projects = $projectsQuery->get();
            if (!\Storage::disk('local')->exists('export')) {
                \Storage::disk('local')->makeDirectory('export');
            }
            Excel::store((new AllProjectExport($projects)), $this->panelFile->path, 'local');
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Storage::disk('local')->delete($this->panelFile->path);
            PanelFile::query()->find($this->panelFile)->delete();
            \Log::info($exception->getLine());
            \Log::info($exception->getFile());
            \Log::info($exception->getMessage());
        }
    }
}
