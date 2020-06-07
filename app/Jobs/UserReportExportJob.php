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

class UserReportExportJob implements ShouldQueue
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
        $usersQuery = UserReport::query();
        $reportController = app()->make(ReportController::class);
        $usersQuery = $reportController->applyFilterUserQuery($usersQuery, $this->filter);
        $users = $usersQuery->get();
        $filename = 'users.xlsx';
        Excel::store((new AllUserExport($users)), $filename);

        \Mail::send('mail', [
            'name' => auth()->user()->name,
            'link' => \URL::temporarySignedRoute('dashboard.report.export.download', now()->addHour(), ['filename' => 'users.xlsx']),
        ], function ($message) use ($filename) {
            $appName = env('APP_NAME');
            $message->to('shirmardi7@gmail.com', '')
                ->subject("{$appName} user report");
            $message->from('no-reply@tankhahgardan.com', "{$appName} Panel");
        });
    }
}
