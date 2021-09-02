<?php

namespace App\Jobs;

use App\Exports\AllProjectExport;
use App\Helpers\Helpers;
use App\Http\Controllers\Dashboard\ReportController;
use App\PanelFile;
use App\ProjectReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ProjectReportExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $filter;
    private $user;

    /**
     * Create a new job instance.
     *
     * @param $filter
     */
    public function __construct($user, $filter)
    {
        $this->filter = $filter;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $today = str_replace('/', '_', Helpers::gregorianDateStringToJalali(now()->toDateString()));
        $filename = "export/allProjectActivity - {$today} - " . Str::random('6') . '.xlsx';
        $panelId = null;
        try {
            $panelId = PanelFile::query()
                ->create([
                    'user_id' => $this->user->id,
                    'path' => $filename,
                    'description' => 'گزارش وضعیت پروژه - ' . str_replace('_', '/', $today),
                    'date_time' => now()->toDateTimeString(),
                ]);
            \DB::beginTransaction();
            $projectsQuery = ProjectReport::query();
            $reportController = app()->make(ReportController::class);
            $projectsQuery = $reportController->applyFilterProjectQuery($projectsQuery, $this->filter);
            $projects = $projectsQuery->get();
            if (!\Storage::disk('local')->exists('export')) {
                \Storage::disk('local')->makeDirectory('export');
            }
            Excel::store((new AllProjectExport($projects)), $filename, 'local');
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Storage::disk('local')->delete($filename);
            if ($panelId) {
                PanelFile::query()->find($panelId)->delete();
            }
            \Log::info($exception->getLine());
            \Log::info($exception->getFile());
            \Log::info($exception->getMessage());
        }
    }
}
