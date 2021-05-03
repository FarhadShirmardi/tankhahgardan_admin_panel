<?php

namespace App\Console\Commands;

use App\AutomationData;
use App\AutomationMetric;
use App\Constants\UserPremiumState;
use App\User;
use Illuminate\Console\Command;

class RegistrationAutomationMetric extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'automation:metric';

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
        $this->info('start');

        $days10 = now()->subDays(10)->toDateTimeString();
        $days15 = now()->subDays(15)->toDateTimeString();
        $days60 = now()->subDays(60)->toDateTimeString();

        $automationMetrics = AutomationData::query()
            ->groupBy([
                'D',
                'T',
                'R',
                'P',
                'A',
            ])
            ->get([
                \DB::raw("count(*) as count"),
                \DB::raw("IF(max_time > '{$days10}', 'D10+', 'D10-') as D"),
                \DB::raw("IF(transaction_count >= 50, 'T50+', IF(transaction_count >= 10, 'T10-49', 'T10-')) as T"),
                \DB::raw("IF(registered_at > '{$days15}', 'R15-', IF(registered_at > '{$days60}', 'R15-60', 'R60+')) as R"),
                \DB::raw("IF(premium_state in (" . UserPremiumState::PREMIUM . ',' . UserPremiumState::NEAR_ENDING_PREMIUM . "), 'P+', 'P-') as P"),
                \DB::raw("IF(automation_state = -1, 'A-', 'A+') as A"),
            ]);
//        dd($automationMetrics->toSql());

        $userCounts = User::query()
            ->groupBy('state')
            ->get([
                'state as user_state',
                \DB::raw('count(*) as count'),
            ]);

//        dd($automationMetrics->toArray());

        $meta = [
            'total_count' => $userCounts->sum('count'),
            'active_user_count' => $userCounts->where('user_state', 1)->first()->count,
            'inactive_user_count' => $userCounts->where('user_state', 0)->first()->count,

            'D10+_T50+_R15+_P+_A+' => $automationMetrics
                ->where('D', 'D10+')
                ->where('T', 'T50+')
                ->where('R', '<>', 'R15-')
                ->where('P', 'P+')
                ->where('A', 'A+')
                ->sum('count'),
            'D10+_T50+_R15+_P+_A-' => $automationMetrics
                ->where('D', 'D10+')
                ->where('T', 'T50+')
                ->where('R', '<>', 'R15-')
                ->where('P', 'P+')
                ->where('A', 'A-')
                ->sum('count'),
            'D10+_T50+_R15+_P-_A+' => $automationMetrics
                ->where('D', 'D10+')
                ->where('T', 'T50+')
                ->where('R', '<>', 'R15-')
                ->where('P', 'P-')
                ->where('A', 'A+')
                ->sum('count'),
            'D10+_T50+_R15+_P-_A-' => $automationMetrics
                ->where('D', 'D10+')
                ->where('T', 'T50+')
                ->where('R', '<>', 'R15-')
                ->where('P', 'P-')
                ->where('A', 'A-')
                ->sum('count'),

            'D10+_T10-49_R15+_P+_A+' => $automationMetrics
                ->where('D', 'D10+')
                ->where('T', 'T10-49')
                ->where('R', '<>', 'R15-')
                ->where('P', 'P+')
                ->where('A', 'A+')
                ->sum('count'),
            'D10+_T10-49_R15+_P+_A-' => $automationMetrics
                ->where('D', 'D10+')
                ->where('T', 'T10-49')
                ->where('R', '<>', 'R15-')
                ->where('P', 'P+')
                ->where('A', 'A-')
                ->sum('count'),
            'D10+_T10-49_R15+_P-_A+' => $automationMetrics
                ->where('D', 'D10+')
                ->where('T', 'T10-49')
                ->where('R', '<>', 'R15-')
                ->where('P', 'P-')
                ->where('A', 'A+')
                ->sum('count'),
            'D10+_T10-49_R15+_P-_A-' => $automationMetrics
                ->where('D', 'D10+')
                ->where('T', 'T10-49')
                ->where('R', '<>', 'R15-')
                ->where('P', 'P-')
                ->where('A', 'A-')
                ->sum('count'),

            'D10+_T10-_R15+_P+_A+' => $automationMetrics
                ->where('D', 'D10+')
                ->where('T', 'T10-')
                ->where('R', '<>', 'R15-')
                ->where('P', 'P+')
                ->where('A', 'A+')
                ->sum('count'),
            'D10+_T10-_R15+_P+_A-' => $automationMetrics
                ->where('D', 'D10+')
                ->where('T', 'T10-')
                ->where('R', '<>', 'R15-')
                ->where('P', 'P+')
                ->where('A', 'A-')
                ->sum('count'),
            'D10+_T10-_R15+_P-_A+' => $automationMetrics
                ->where('D', 'D10+')
                ->where('T', 'T10-')
                ->where('R', '<>', 'R15-')
                ->where('P', 'P-')
                ->where('A', 'A+')
                ->sum('count'),
            'D10+_T10-_R15+_P-_A-' => $automationMetrics
                ->where('D', 'D10+')
                ->where('T', 'T10-')
                ->where('R', '<>', 'R15-')
                ->where('P', 'P-')
                ->where('A', 'A-')
                ->sum('count'),


            'D10-_T50+_R15-60_P*_A+' => $automationMetrics
                ->where('D', 'D10-')
                ->where('T', 'T50+')
                ->where('R', 'R15-60')
                ->where('A', 'A+')
                ->sum('count'),
            'D10-_T50+_R15-60_P*_A-' => $automationMetrics
                ->where('D', 'D10-')
                ->where('T', 'T50+')
                ->where('R', 'R15-60')
                ->where('A', 'A-')
                ->sum('count'),

            'D10-_T10-49_R15-60_P*_A+' => $automationMetrics
                ->where('D', 'D10-')
                ->where('T', 'T10-49')
                ->where('R', 'R15-60')
                ->where('A', 'A+')
                ->sum('count'),
            'D10-_T10-49_R15-60_P*_A-' => $automationMetrics
                ->where('D', 'D10-')
                ->where('T', 'T10-49')
                ->where('R', 'R15-60')
                ->where('A', 'A-')
                ->sum('count'),

            'D10-_T10-_R15-60_P*_A+' => $automationMetrics
                ->where('D', 'D10-')
                ->where('T', 'T10-')
                ->where('R', 'R15-60')
                ->where('A', 'A+')
                ->sum('count'),
            'D10-_T10-_R15-60_P*_A-' => $automationMetrics
                ->where('D', 'D10-')
                ->where('T', 'T10-')
                ->where('R', 'R15-60')
                ->where('A', 'A-')
                ->sum('count'),


            'D10-_T50+_R60+_P*_A+' => $automationMetrics
                ->where('D', 'D10-')
                ->where('T', 'T50+')
                ->where('R', 'R60+')
                ->where('A', 'A+')
                ->sum('count'),
            'D10-_T50+_R60+_P*_A-' => $automationMetrics
                ->where('D', 'D10-')
                ->where('T', 'T50+')
                ->where('R', 'R60+')
                ->where('A', 'A-')
                ->sum('count'),

            'D10-_T10-49_R60+_P*_A+' => $automationMetrics
                ->where('D', 'D10-')
                ->where('T', 'T10-49')
                ->where('R', 'R60+')
                ->where('A', 'A+')
                ->sum('count'),
            'D10-_T10-49_R60+_P*_A-' => $automationMetrics
                ->where('D', 'D10-')
                ->where('T', 'T10-49')
                ->where('R', 'R60+')
                ->where('A', 'A-')
                ->sum('count'),

            'D10-_T10-_R60+_P*_A+' => $automationMetrics
                ->where('D', 'D10-')
                ->where('T', 'T10-')
                ->where('R', 'R60+')
                ->where('A', 'A+')
                ->sum('count'),
            'D10-_T10-_R60+_P*_A-' => $automationMetrics
                ->where('D', 'D10-')
                ->where('T', 'T10-')
                ->where('R', 'R60+')
                ->where('A', 'A-')
                ->sum('count'),
        ];


        AutomationMetric::query()
            ->updateOrInsert([
                'date' => now()->toDateString(),
            ], [
                'metric' => json_encode($meta),
            ]);

        $this->info(now()->diff($start)->format('%i:%s.%f'));
    }
}
