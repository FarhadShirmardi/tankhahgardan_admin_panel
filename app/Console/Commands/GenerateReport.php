<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\UserReport;
use App\Http\Controllers\Dashboard\ReportController;

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
        $start = now();
        $this->info($start->toDateTimeString());
        DB::transaction(function () {
            $reportController = app()->make(ReportController::class);
            if ($this->option('user')) {
                UserReport::truncate();
                $users = $reportController->getUserQuery()->get();
                $bar = $this->output->createProgressBar($users->count());
                foreach ($users as $user) {
                    UserReport::query()->insert($user->toArray());
                    $bar->advance();
                }

                $reportController->getUserTypeCounts();
            } elseif ($this->option('project')) {
                $this->fetchHistory();
            }
        }, 3);
        $end = now();
        $this->info($end);
        $this->info($end->diffForHumans($start));
    }
}
