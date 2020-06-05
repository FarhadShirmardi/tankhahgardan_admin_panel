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
        DB::transaction(function () {
            $reportController = app()->make(ReportController::class);
            if ($this->option('user')) {
                UserReport::truncate();
                $projectIds = User::query()->pluck('id')->chunk(100);
                $bar = $this->output->createProgressBar(User::query()->count());
                foreach ($projectIds as $projectId) {
                    $users = $reportController->getUserQuery()->whereIn('users.id', $projectId->toArray())->get();
                    foreach ($users as $user) {
                        UserReport::query()->insert($user->toArray());
                        $bar->advance();
                    }
                }
            } elseif ($this->option('project')) {
                ProjectReport::truncate();
                $projectIds = Project::query()->pluck('id')->chunk(100);
                $bar = $this->output->createProgressBar(Project::query()->count());
                foreach ($projectIds as $projectId) {
                    $projects = $reportController->getProjectQuery()->whereIn('projects.id', $projectId->toArray())->get();
                    foreach ($projects as $project) {
                        ProjectReport::query()->insert($project->toArray());
                        $bar->advance();
                    }
                }
            }
        }, 3);
        $end = now();
        $this->info($end);
        $this->info($end->diffForHumans($start));
    }
}
