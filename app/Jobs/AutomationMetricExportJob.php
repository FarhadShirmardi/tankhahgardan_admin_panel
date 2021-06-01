<?php

namespace App\Jobs;

use App\Exports\AllUserExport;
use App\Helpers\Helpers;
use App\Http\Controllers\Dashboard\ReportController;
use App\PanelFile;
use App\UserReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class AutomationMetricExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $filter;

    /**
     * Create a new job instance.
     *
     * @param $filter
     */
    public function __construct($filter)
    {
        $this->filter = $filter;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $today = str_replace('/', '_', Helpers::gregorianDateStringToJalali(now()->toDateString()));
        $filename = "export/automationMetric - {$today}" . '.xlsx';
        try {
            \DB::beginTransaction();
            $usersQuery = UserReport::query();
            $reportController = app()->make(ReportController::class);
            $usersQuery = $reportController->applyFilterUserQuery($usersQuery, $this->filter);
            $users = $usersQuery->get();
            if (!\Storage::disk('local')->exists('export')) {
                \Storage::disk('local')->makeDirectory('export');
            }
            Excel::store((new AllUserExport($users)), $filename, 'local');
            PanelFile::query()
                ->create([
                    'user_id' => auth()->id(),
                    'path' => $filename,
                    'description' => 'گزارش وضعیت کاربران - ' . str_replace('_', '/', $today),
                    'date_time' => now()->toDateTimeString(),
                ]);
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Storage::disk('local')->delete($filename);
            \Log::info($exception->getMessage());
        }
    }
}
