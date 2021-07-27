<?php

namespace App\Jobs;

use App\Exports\MetricsExport;
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
            $automationController = app()->make(AutomationController::class);
            $metrics = $automationController->getMetricsItems($this->filter);
            if (!\Storage::disk('local')->exists('export')) {
                \Storage::disk('local')->makeDirectory('export');
            }
            Excel::store((new MetricsExport($metrics)), $this->panelFile->path, 'local');
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Storage::disk('local')->delete($this->panelFile->path);
            PanelFile::query()->find($this->panelFile)->delete();
            \Log::info($exception->getMessage());
        }
    }
}
