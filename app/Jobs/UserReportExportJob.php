<?php

namespace App\Jobs;

use App\Exports\AllUserExport;
use App\Http\Controllers\Dashboard\ReportController;
use App\PanelFile;
use App\UserReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class UserReportExportJob implements ShouldQueue
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
            $usersQuery = UserReport::query();
            $reportController = app()->make(ReportController::class);
            $usersQuery = $reportController->applyFilterUserQuery($usersQuery, $this->filter);
            $users = $usersQuery->get();
            if (!\Storage::disk('local')->exists('export')) {
                \Storage::disk('local')->makeDirectory('export');
            }
            Excel::store((new AllUserExport($users)), $this->panelFile->path, 'local');
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            PanelFile::query()->find($this->panelFile)->delete();
            \Storage::disk('local')->delete($this->panelFile->path);
            \Log::info($exception->getMessage());
        }
    }
}
