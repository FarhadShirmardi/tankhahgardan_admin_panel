<?php

namespace App\Console\Commands;

use App\Http\Controllers\Dashboard\ReportController;
use App\Models\ProjectReport;
use App\Models\User;
use App\Models\UserReport;
use DB;
use Illuminate\Console\Command;
use Illuminate\Contracts\Container\BindingResolutionException;

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
     * @return void
     * @throws BindingResolutionException
     */
    public function handle(): void
    {
        \Log::debug('start generate report');
        $start = now();
        $this->info($start->toDateTimeString());
        $reportController = app()->make(ReportController::class);
        if ($this->option('user')) {
            UserReport::query()->truncate();
            $columnList = \Schema::getColumnListing('user_reports');
            $bar = $this->output->createProgressBar(User::query()->count());
            User::query()
                ->withoutEagerLoads()
                ->select(['id'])
                ->chunk(1000, function ($ids) use ($bar, $reportController, $columnList) {
                    $ids = $ids->pluck('id')->toArray();
                    $selectQuery = DB::query()->fromSub($reportController->getUserQuery($ids)->getQuery(), 'users_query')->select($columnList);
                    DB::table('user_reports')->insertUsing($columnList, $selectQuery);
                    $bar->advance(count($ids));
                });
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
