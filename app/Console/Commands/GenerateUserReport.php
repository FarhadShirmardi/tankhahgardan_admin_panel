<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserReport;
use App\Services\UserReportService;
use DB;
use Illuminate\Console\Command;
use Schema;

class GenerateUserReport extends Command
{
    protected $signature = 'generate:user-report';

    protected $description = 'Command description';

    public function handle(): void
    {
        UserReport::query()->truncate();
        $columnList = Schema::getColumnListing('user_reports');
        $bar = $this->output->createProgressBar(User::query()->count());
        User::query()
            ->withoutEagerLoads()
            ->select(['id'])
            ->chunk(1000, function ($ids) use ($bar, $columnList) {
                $ids = $ids->pluck('id')->toArray();
                $selectQuery = DB::query()->fromSub(UserReportService::getUsersQuery($ids)->getQuery(), 'users_query')->select($columnList);
                DB::connection('mysql_panel')->table('user_reports')->insertUsing($columnList, $selectQuery);
                $bar->advance(count($ids));
            });
        UserReportService::updateImageCount();
    }
}
