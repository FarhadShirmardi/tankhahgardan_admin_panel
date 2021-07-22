<?php

namespace App\Console\Commands;

use App\Constants\Platform;
use App\Constants\PremiumDuration;
use App\Constants\UserPremiumState;
use App\Helpers\Helpers;
use App\MonthlyReport;
use App\Payment;
use App\ProjectUser;
use App\Receive;
use App\User;
use App\UserReport;
use Carbon\Carbon;
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
        $day = $date[2];
//        if ($day != 1) {
//            return;
//        }
        $startDate = Helpers::normalizeDate($date[0], $date[1], $date[2]);
        $startDate[-2] = '0';
        $startDate[-1] = '1';
        $startDate = explode('-', str_replace('/', '-', Helpers::jalaliDateStringToGregorian($startDate)));
        $startDate = Helpers::normalizeDate($startDate[0], $startDate[1], $startDate[2], '-');
        $endDate = Helpers::normalizeDate($date[0], $date[1], $date[2]);

        $report = MonthlyReport::query()->firstOrCreate([
            'year' => $year,
            'month' => $month,
        ]);

        $report->old_user_data = json_encode($this->getOldUserData($startDate));
        $report->new_user_data = json_encode($this->getNewUserData($startDate, $endDate));
        $report->user_return_data = json_encode($this->getUserReturnData($startDate));
        $report->active_user_counts = json_encode($this->getActiveUsersData());
        $report->user_assessment_data = json_encode($this->getUserAssessmentData());

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

    private function getActiveUsersData()
    {
        $daysAgo = now()->subDays(10)->toDateTimeString();
        $month5 = now()->subMonths(5)->toDateTimeString();
        $month4 = now()->subMonths(4)->toDateTimeString();

        $monthUsers = UserReport::query()
            ->orderBy('id')
            ->get();

        $monthUsersReal = User::query()
            ->with(['devices', 'userStatus'])
            ->orderBy('id')
            ->get();

        $carbon = new Carbon();
        $result = collect();
        $pb = $this->output->createProgressBar($monthUsers->count());
        /** @var UserReport $monthUser */
        foreach ($monthUsers as $key => $monthUser) {
            /** @var User $user */
            $user = $monthUsersReal->skip($key)->first();
            $userStatus = $user->userStatus[0] ?? null;
            $isPremium = $userStatus and $userStatus->end_date > $daysAgo;
            if (!$isPremium) {
                continue;
            }
            $hasYearly = $user->userStatus
                    ->where('price_id', PremiumDuration::YEAR)->first() != null;
            $isSeries = $hasYearly and $user->userStatus->where('end_date', '>=', $month5)->count() >= 4;

            $data = Payment::query()
                ->where('creator_user_id', $user->id)
                ->where('created_at', '>=', $month4)
                ->union(
                    Receive::query()
                        ->where('creator_user_id', $user->id)
                        ->where('created_at', '>=', $month4)
                        ->getQuery()
                )->orderBy('created_at')->get();
            $hasSpace = false;
            foreach ($data as $key => $item) {
                if ($key == 0) {
                    continue;
                }
                $currentDate = $carbon->parse($item['created_at']);
                $prevDate = $carbon->parse($data[$key - 1]['created_at']);

                if ($prevDate->diffInDays($currentDate) > 14) {
                    $hasSpace = true;
                    break;
                }
            }

            $has2weekSpace = true;
            if (count($user->userStatus) < 2) {
                $has2weekSpace = false;
            } else {
                $ssDate = Carbon::parse($user->userStatus[0]->start_date);
                $eeDate = Carbon::parse($user->userStatus[1]->end_date);
                if ($ssDate->diffInDays($eeDate, true) > 14) {
                    $has2weekSpace = false;
                }
            }

            $result->push([
                'id' => $user->id,
                'has_space' => $hasSpace,
                'week_space' => $has2weekSpace,
                'is_series' => $isSeries,
            ]);
            $pb->advance();
        }

        return [
            'series' => $result->where('is_series', true)->count(),
            'has_space' => $result->where('has_space', true)->count(),
            'week_space' => $result->where('week_space', true)->count(),
        ];
    }

    private function getUserReturnData($startDate)
    {
        $startDate = Carbon::parse($startDate);
        $middleDate = $startDate->copy()->addDays(10);

        $monthUsers = UserReport::query()
            ->whereIn('user_state', [UserPremiumState::PREMIUM, UserPremiumState::NEAR_ENDING_PREMIUM])
            ->orderBy('id')
            ->get();

        $monthUsersReal = User::query()
            ->with(['devices', 'userStatus'])
            ->whereIn('id', $monthUsers->pluck('id')->toArray())
            ->orderBy('id')
            ->get();

        $result = collect();
        /** @var UserReport $monthUser */
        foreach ($monthUsers as $key => $monthUser) {
            /** @var User $user */
            $user = $monthUsersReal->skip($key)->first();
            $userStatus = $user->userStatus[0] ?? null;
            $hasSpace = true;
            if (count($user->userStatus) < 2) {
                $hasSpace = false;
            } else {
                $ssDate = Carbon::parse($user->userStatus[0]->start_date);
                $eeDate = Carbon::parse($user->userStatus[1]->end_date);
                if ($ssDate->diffInDays($eeDate, true) < 5) {
                    $hasSpace = false;
                }
            }

            $beginOfMonth = true;
            $beginOfMonthStatus = $user->userStatus->where('start_date', '<=', $middleDate)
                ->where('end_date', '>=', $startDate)->first();
            if ($beginOfMonthStatus) {
                $beginOfMonth = false;
            }

            $result->push([
                'id' => $user->id,
                'platform' => $user->devices->first()->platform ?? '0',
                'is_premium' => $userStatus and $userStatus->end_date > now()->toDateTimeString(),
                'price_id' => ($userStatus and $userStatus->end_date > now()->toDateTimeString()) ?
                    $userStatus->price_id : 0,
                'has_space' => $hasSpace,
                'begin_month' => $beginOfMonth,
            ]);
        }
        $finalResult = collect();
        $prices = PremiumDuration::toArray();
        $prices[] = 0;
        foreach (Platform::toArray() as $platform) {
            foreach ([1, 0] as $isPremium) {
                foreach ($prices as $price) {
                    foreach ([1, 0] as $hasSpace) {
                        foreach ([1, 0] as $beginMonth) {
                            $filter = $result->where('price_id', $price)
                                ->where('platform', $platform)
                                ->where('is_premium', $isPremium)
                                ->where('has_space', $hasSpace)
                                ->where('begin_month', $beginMonth);
                            $finalResult->push([
                                'platform' => $platform,
                                'is_premium' => $isPremium,
                                'price' => $price,
                                'has_space' => $hasSpace,
                                'begin_month' => $beginMonth,
                                'count' => $filter->count(),
                            ]);
                        }
                    }
                }
            }
        }

        return $finalResult;
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

    private function getUserAssessmentData()
    {
        $daysAgo = now()->subDays(10)->toDateTimeString();
        $monthUsers = UserReport::query()
            ->whereIn('user_state', [UserPremiumState::PREMIUM, UserPremiumState::NEAR_ENDING_PREMIUM])
            ->orderBy('id')
            ->get();

        $monthUsersReal = User::query()
            ->with(['devices', 'userStatus', 'ownedProjects'])
            ->orderBy('id')
            ->get();

        $result = collect();
        /** @var UserReport $monthUser */
        foreach ($monthUsers as $key => $monthUser) {
            /** @var User $user */
            $user = $monthUsersReal->skip($key)->first();

            $userId = $user->id;
            $projectIds = $user->ownedProjects->pluck('id')->toArray();
            $payments = Payment::query()
                ->withoutTrashed()
                ->where(function ($query) use ($userId, $projectIds) {
                    $query->where('creator_user_id', $userId)
                        ->orWhereIn('project_id', $projectIds);
                })
                ->select(['created_at'])
                ->getQuery();

            $receives = Receive::query()
                ->withoutTrashed()
                ->where(function ($query) use ($userId, $projectIds) {
                    $query->where('creator_user_id', $userId)
                        ->orWhereIn('project_id', $projectIds);
                })
                ->select(['created_at'])
                ->getQuery();

            $maxTime = \DB::query()
                ->fromSub(
                    $payments->unionAll($receives),
                    't'
                )
                ->max('created_at');

            $isActive = $maxTime >= $daysAgo;

            $userCount = ProjectUser::query()->where('project_id', $projectIds)->where('is_owner', false)->count();

            $userStatus = $user->userStatus[0] ?? null;

            $result->push([
                'id' => $user->id,
                'is_active' => $isActive,
                'price_id' => ($userStatus and $userStatus->end_date > now()->toDateTimeString()) ?
                    $userStatus->price_id : 0,
                'user_count' => ($userCount == 0) ? 1 :
                    (($userCount == 1) ? 2 : ($userCount < 5 ? 3 : ($userCount < 8 ? 4 : 5))),
                'image_size' => ($monthUser->image_size < 100) ? 1 : ($monthUser->image_size < 200 ? 2 : 3),
            ]);
        }

        $finalResult = collect();
        $prices = PremiumDuration::toArray();
        $prices[] = 0;
        foreach ($prices as $price) {
            foreach ([1, 0] as $isActive) {
                foreach ([1, 2, 3, 4, 5] as $userCount) {
                    foreach ([1, 2, 3] as $imageSize) {
                        $filter = $result
                            ->where('price_id', $price)
                            ->where('is_active', $isActive)
                            ->where('user_count', $userCount)
                            ->where('image_size', $imageSize);
                        $finalResult->push([
                            'price' => $price,
                            'is_active' => $isActive,
                            'user_count' => $userCount,
                            'count' => $filter->count(),
                            'image_size' => $imageSize,
                        ]);
                    }
                }
            }
        }

        return $finalResult;
    }
}
