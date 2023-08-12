<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\UserReportService;
use DB;
use Illuminate\Console\Command;
use Schema;

class GenerateNewUserReport extends Command
{
    protected $signature = 'generate:new-user-report';

    protected $description = 'Command description';

    public function handle()
    {
        $columnList = Schema::getColumnListing('user_reports');
        $query = User::query()
            ->leftJoin('panel_user_reports', 'panel_user_reports.id', 'users.id')
            ->whereNull('panel_user_reports.id');
        $bar = $this->output->createProgressBar(($query->clone())->count());
        $bar->start();
        $query->clone()
            ->withoutEagerLoads()
            ->select(['users.id'])
            ->chunk(1000, function ($ids) use ($bar, $columnList) {
                $ids = $ids->pluck('id')->toArray();
                $selectQuery = DB::query()->fromSub(UserReportService::getUsersQuery($ids)->getQuery(), 'users_query')->select($columnList);
                DB::connection('mysql_panel')->table('user_reports')->insertUsing($columnList, $selectQuery);
                $bar->advance(count($ids));
            });
        UserReportService::updateImageCount();
    }
}
