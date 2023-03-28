<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserReport;
use App\Services\UserReportService;
use DB;
use Illuminate\Console\Command;
use Illuminate\Database\Query\Builder;
use Schema;

class GenerateNewUserReport extends Command
{
    protected $signature = 'generate:new-user-report';

    protected $description = 'Command description';

    public function handle()
    {
        $columnList = Schema::getColumnListing('user_reports');
        $bar = $this->output->createProgressBar(User::query()->whereNotExists(
            fn (Builder $query) => $query
                ->from('panel_user_reports')
                ->whereColumn('users.id', 'panel_user_reports.id')
        )->count());
        User::query()
            ->withoutEagerLoads()
            ->select(['id'])
            ->whereNotExists(fn (Builder $query) => $query
                ->from('panel_user_reports')
                ->whereColumn('users.id', 'panel_user_reports.id')
            )
            ->chunk(1000, function ($ids) use ($bar, $columnList) {
                $ids = $ids->pluck('id')->toArray();
                $selectQuery = DB::query()->fromSub(UserReportService::getUsersQuery($ids)->getQuery(), 'users_query')->select($columnList);
                DB::connection('mysql_panel')->table('user_reports')->insertUsing($columnList, $selectQuery);
                $bar->advance(count($ids));
            });
    }
}
