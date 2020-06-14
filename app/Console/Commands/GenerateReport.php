<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\UserReport;
use App\Http\Controllers\Dashboard\ReportController;
use App\User;
use App\ProjectReport;
use App\Project;

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
            $userIds = User::query()->pluck('id')->chunk(1000);
            $bar = $this->output->createProgressBar(count($userIds));
            foreach ($userIds as $userId) {
                $users = $reportController->getUserQuery()->whereIn('users.id', $userId->toArray())->get();
                UserReport::insert($users->toArray());
                $bar->advance();
            }
        } elseif ($this->option('project')) {
            ProjectReport::query()->truncate();
            $projectIds = Project::query()->pluck('id')->chunk(1000);
            $bar = $this->output->createProgressBar(count($projectIds));
            foreach ($projectIds as $projectId) {
                $projects = $reportController->getProjectQuery()->whereIn('projects.id', $projectId->toArray())->get();
                ProjectReport::insert($projects->toArray());
                $bar->advance();
            }
        }
        $end = now();
        $this->info($end);
        $this->info($end->diffForHumans($start));
    }
}
