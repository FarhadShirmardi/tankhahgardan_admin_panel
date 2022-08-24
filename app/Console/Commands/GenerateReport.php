<?php

namespace App\Console\Commands;

use App\Http\Controllers\Dashboard\ReportController;
use App\Models\ProjectReport;
use App\Models\UserReport;
use DB;
use Illuminate\Console\Command;

class GenerateReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:report {--user} {--project}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \Log::debug('start generate report');
        $start = now();
        $this->info($start->toDateTimeString());
        $reportController = app()->make(ReportController::class);
        if ($this->option('user')) {
            UserReport::query()->truncate();
            $columnList = \Schema::getColumnListing('user_reports');
            $selectQuery = DB::query()->fromSub($reportController->getUserQuery()->getQuery(), 'users_query')->select($columnList);
            DB::table('user_reports')->insertUsing($columnList, $selectQuery);
        } elseif ($this->option('project')) {
            ProjectReport::query()->truncate();
            $columnList = \Schema::getColumnListing('project_reports');
            $selectQuery = DB::query()->fromSub($reportController->getProjectQuery()->getQuery(), 'projects_query')->select($columnList);
            DB::table('project_reports')->insertUsing($columnList, $selectQuery);
        }
        $end = now();
        $this->info($end);
        $this->info($end->diffForHumans($start));
    }
}
