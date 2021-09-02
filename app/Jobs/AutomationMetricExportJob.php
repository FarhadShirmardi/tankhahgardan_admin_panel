<?php

namespace App\Jobs;

use App\Exports\MetricsExport;
use App\Helpers\Helpers;
use App\Http\Controllers\Dashboard\AutomationController;
use App\PanelFile;
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
        $filename = "export/automationMetric - {$today}" . '.xlsx';
        try {
            \DB::beginTransaction();
            $automationController = app()->make(AutomationController::class);
            $metrics = $automationController->getMetricsItems($this->filter);
            if (!\Storage::disk('local')->exists('export')) {
                \Storage::disk('local')->makeDirectory('export');
            }
            Excel::store((new MetricsExport($metrics)), $filename, 'local');
            PanelFile::query()
                ->create([
                    'user_id' => $this->user->id,
                    'path' => $filename,
                    'description' => 'گزارش وضعیت اتوماسیون - ' . str_replace('_', '/', $today),
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
