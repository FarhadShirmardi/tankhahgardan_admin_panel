<?php

namespace App\Console\Commands;

use App\Constants\Platform;
use App\Constants\PremiumDuration;
use App\Helpers\Helpers;
use App\MonthlyReport;
use App\User;
use App\UserReport;
use Illuminate\Console\Command;

class GenerateMonthlyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:monthly_report';

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

        $date = explode('/', Helpers::gregorianDateStringToJalali(now()->toDateString()));
        $year = $date[0];
        $month = $date[1];
        $startDate = Helpers::normalizeDate($date[0], $date[1], $date[2]);
        $startDate[-2] = '0';
        $startDate[-1] = '1';
        $startDate = explode('-', str_replace('/', '-', Helpers::jalaliDateStringToGregorian($startDate)));
        $startDate = Helpers::normalizeDate($startDate[0], $startDate[1], $startDate[2], '-');
        $endDate = now()->toDateString();

        $report = MonthlyReport::query()->firstOrCreate([
            'year' => $year,
            'month' => $month,
        ]);

//        $report->old_user_data = json_encode($this->getOldUserData($startDate));
        $report->new_user_data = json_encode($this->getNewUserData($startDate, $endDate));

        $report->save();

        $end = now();
        $this->info($end);
        $this->info($end->diffForHumans($start));
    }

    private function getNewUserData($startDate, $endDate)
    {
        $monthUsers = UserReport::query()
            ->whereBetween('registered_at', [$startDate, $endDate])
            ->orderBy('id')
            ->get();

        $monthUsersReal = User::query()
            ->with(['devices', 'userStatus'])
            ->whereIn('id', $monthUsers->pluck('id')->toArray())
            ->orderBy('id')
            ->get();

        $result = collect();
        foreach ($monthUsers as $key => $monthUser) {
            /** @var User $user */
            $user = $monthUsersReal->skip($key)->first();
            $userStatus = $user->userStatus[0] ?? null;
            $result->push([
                'id' => $user->id,
                'platform' => $user->devices->first()->platform,
                'is_premium' => $userStatus and $userStatus->end_date > now()->toDateTimeString(),
                'price_id' => ($userStatus and $userStatus->end_date > now()->toDateTimeString()) ?
                    $userStatus->price_id : 0,
                'transaction_10+' => ($monthUser->payment_count + $monthUser->receive_count) > 10 ? 1 : 0,
            ]);
        }
        $finalResult = collect();
        $prices = PremiumDuration::toArray();
        $prices[] = 0;
        foreach (Platform::toArray() as $platform) {
            foreach ([1, 0] as $isPremium) {
                foreach ($prices as $price) {
                    foreach ([1, 0] as $transactionState) {
                        $count = $result->where('price_id', $price)
                            ->where('platform', $platform)
                            ->where('is_premium', $isPremium)
                            ->where('transaction_10+', $transactionState)
                            ->count();
                        $finalResult->push([
                            'platform' => $platform,
                            'is_premium' => $isPremium,
                            'price' => $price,
                            'transaction_state' => $transactionState,
                            'key' => $platform . $isPremium . $price . $transactionState,
                            'count' => $count,
                        ]);
                    }
                }
            }
        }
        return $finalResult->toArray();
    }

    private function getOldUserData($endDate)
    {
        $monthUsers = UserReport::query()
            ->where('registered_at', '<', $endDate)
            ->orderBy('id')
            ->get();

        $monthUsersReal = User::query()
            ->with(['devices', 'userStatus'])
            ->whereIn('id', $monthUsers->pluck('id')->toArray())
            ->orderBy('id')
            ->get();

        $result = collect();
        /** @var UserReport $monthUser */
        $pb = $this->output->createProgressBar($monthUsers->count());
        foreach ($monthUsers as $key => $monthUser) {
            /** @var User $user */
            $user = $monthUsersReal->skip($key)->first();
            $userStatus = $user->userStatus[0] ?? null;
            $result->push([
                'id' => $user->id,
                'platform' => $user->devices->first()->platform ?? '0',
                'is_premium' => $userStatus and $userStatus->end_date > now()->toDateTimeString(),
                'price_id' => ($userStatus and $userStatus->end_date > now()->toDateTimeString()) ?
                    $userStatus->price_id : 0,
                'transaction_50+' => ($monthUser->payment_count + $monthUser->receive_count) > 50 ? 1 : 0,
                'image_size' => ($monthUser->image_size < 100) ? 1 : ($monthUser->image_size < 200 ? 2 : 3),
                'image_count' => $monthUser->image_count,
            ]);
            $pb->advance();
        }
        $finalResult = collect();
        $prices = PremiumDuration::toArray();
        $prices[] = 0;
        foreach (Platform::toArray() as $platform) {
            foreach ([1, 0] as $isPremium) {
                foreach ($prices as $price) {
                    foreach ([1, 0] as $transactionState) {
                        foreach ([1, 2, 3] as $imageSize) {
                            $filter = $result->where('price_id', $price)
                                ->where('platform', $platform)
                                ->where('is_premium', $isPremium)
                                ->where('image_size', $imageSize)
                                ->where('transaction_50+', $transactionState);
                            $finalResult->push([
                                'platform' => $platform,
                                'is_premium' => $isPremium,
                                'price' => $price,
                                'transaction_state' => $transactionState,
                                'count' => $filter->count(),
                                'image_size' => $imageSize,
                                'image_count' => round($filter->avg('image_count'), 2),
                            ]);
                        }
                    }
                }
            }
        }

        return $finalResult->toArray();
    }
}
