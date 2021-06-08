<?php

namespace App\Console\Commands;

use App\AutomationData;
use App\AutomationDate;
use App\Constants\PremiumDuration;
use App\Constants\UserPremiumState;
use App\Payment;
use App\ProjectUser;
use App\Receive;
use App\User;
use App\UserStatusLog;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;
use Illuminate\Contracts\Container\BindingResolutionException;

class RegistrationAutomationUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'automation:update';

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
     * @throws BindingResolutionException
     */
    public function handle()
    {
        $start = now();
        $this->info('start');

        $premiumStates = [UserPremiumState::PREMIUM, UserPremiumState::NEAR_ENDING_PREMIUM];

        $maxTimes = AutomationData::query()
            ->first([
                DB::raw("max(registered_at) as max_reg_time"),
                DB::raw("max(max_time) as max_time"),
            ])->toArray();

        $users = User::query()
            ->whereNotNull('verification_time')
            ->where('verification_time', '>=', $maxTimes['max_reg_time'] ?? '')->get();

        $data = collect();
        $bar = $this->output->createProgressBar($users->count());
        foreach ($users as $user) {
            $data->push([
                'id' => $user->id,
                'name' => $user->full_name,
                'phone_number' => $user->phone_number,
                'registered_at' => $user->verification_time,
                'transaction_count' => 0,
                'max_time' => null,
                'automation_state' => $user->state == 0 ? 0 : 1,
                'premium_state' => $user->premium_state,
            ]);
            $bar->advance();
        }
        $chunkedResults = $data->chunk(5000);
        foreach ($chunkedResults as $chunkedResult) {
            AutomationData::query()->insertOrIgnore($chunkedResult->toArray());
        }

        $beforeTime = now()->subMinutes(5)->toDateTimeString();

        $items = Payment::query()
            ->where('created_at', '>', $maxTimes['max_time'] ?? '')
            ->where('created_at', '<=', $beforeTime)
            ->unionAll(
                Receive::query()
                    ->where('created_at', '>', $maxTimes['max_time'] ?? '')
                    ->where('created_at', '<=', $beforeTime)
                    ->getQuery()
            )
            ->orderBy('created_at')
            ->get();

        $bar = $this->output->createProgressBar($items->count());
        foreach ($items as $item) {
            /** @var Payment $item */
            AutomationData::query()
                ->where('id', $item->creator_user_id)
                ->update([
                    'transaction_count' => DB::raw('transaction_count + 1'),
                    'max_time' => $item->created_at,
                ]);
            /** @var ProjectUser $owner */
            $owner = $item->projectOwnerUser()->first();
            if ($owner and $item->creator_user_id != $owner->user_id) {
                AutomationData::query()
                    ->where('id', $owner->user_id)
                    ->update([
                        'transaction_count' => DB::raw('transaction_count + 1'),
                        'max_time' => $item->created_at,
                    ]);
            }
            $bar->advance();
        }

        $statusLogs = UserStatusLog::query()
            ->where('status', true)
            ->where(function ($query) {
                $query->whereDate('start_date', '>=', now()->toDateString())
                    ->orWhereDate('end_date', '<=', now()->toDateString());
            })
            ->get();

        $automationRegister = app()->make(RegistrationAutomationCalculate::class);

        foreach ($statusLogs as $statusLog) {
            $user = User::query()->with('ownedProjects')->find($statusLog->user()->first()->id);
            $data = $automationRegister->getRegistrationAutomationQuery($user);
            unset($data['id'], $data['automation_state']);
            AutomationData::query()
                ->where('id', $statusLog->user_id)
                ->update(
                    $data
                );
        }

        $lastAutomationDate = AutomationDate::query()->max('date_time');
        $updateFlag = false;
        if ($lastAutomationDate) {
            if (now()->diffInDays($lastAutomationDate = Carbon::parse($lastAutomationDate), true) >= 20) {
                $updateFlag = true;
                $lastAutomationDate = $lastAutomationDate->subDays(10)->toDateTimeString();
            }
        } else {
            $updateFlag = true;
            $lastAutomationDate = Carbon::parse('2021-04-10 00:00:00');
        }

        if ($updateFlag) {
            AutomationData::query()
                ->where('automation_state', '<', 0)
                ->update([
                    'automation_state' => -1,
                ]);

            AutomationData::query()
                ->where('automation_state', '<', 0)
                ->where('max_time', '>=', $lastAutomationDate)
                ->where('max_time', '<', now()->subDays(10)->toDateTimeString())
                ->where('transaction_count', '>=', 20)
                ->update([
                    'automation_state' => -2,
                ]);

            AutomationData::query()
                ->where('automation_state', '<', 0)
                ->where('max_time', '>=', $lastAutomationDate)
                ->where('max_time', '<', now()->subDays(10)->toDateTimeString())
                ->where('transaction_count', '<', 20)
                ->where('transaction_count', '>=', 5)
                ->update([
                    'automation_state' => -3,
                ]);

            AutomationData::query()
                ->where('automation_state', '<', 0)
                ->where('max_time', '>=', $lastAutomationDate)
                ->where('max_time', '<', now()->subDays(10)->toDateTimeString())
                ->where('transaction_count', '<', 5)
                ->update([
                    'automation_state' => -4,
                ]);

            AutomationData::query()
                ->where('automation_state', '<', 0)
                ->where('max_time', '>=', now()->subDays(10)->toDateTimeString())
                ->whereIn('premium_state', $premiumStates)
                ->update([
                    'automation_state' => -5,
                ]);

            AutomationData::query()
                ->where('automation_state', '<', 0)
                ->where('max_time', '>=', now()->subDays(10)->toDateTimeString())
                ->where('transaction_count', '<', 20)
                ->whereNotIn('premium_state', $premiumStates)
                ->update([
                    'automation_state' => -6,
                ]);

            AutomationData::query()
                ->where('automation_state', '<', 0)
                ->where('max_time', '>=', now()->subDays(10)->toDateTimeString())
                ->where('transaction_count', '>=', 20)
                ->whereNotIn('premium_state', $premiumStates)
                ->update([
                    'automation_state' => -7,
                ]);

            AutomationDate::query()->create([
                'date_time' => now()->toDateTimeString(),
            ]);
        }

        AutomationData::query()
            ->where('registered_at', '<=', now()->subDay()->toDateTimeString())
            ->where('registered_at', '>', now()->subDays(3)->toDateTimeString())
            ->where('automation_state', '>=', 0)
            ->update([
                'automation_state' => 2,
            ]);

        AutomationData::query()
            ->where('automation_state', '>=', 0)
            ->where('registered_at', '<=', now()->subDays(3)->toDateTimeString())
            ->where('registered_at', '>', now()->subDays(9)->toDateTimeString())
            ->where('transaction_count', '>', 0)
            ->update([
                'automation_state' => 3,
            ]);

        AutomationData::query()
            ->where('automation_state', '>=', 0)
            ->where('registered_at', '<=', now()->subDays(3)->toDateTimeString())
            ->where('registered_at', '>', now()->subDays(9)->toDateTimeString())
            ->where('transaction_count', 0)
            ->update([
                'automation_state' => 4,
            ]);

        AutomationData::query()
            ->where('automation_state', '>=', 0)
            ->where('registered_at', '<=', now()->subDays(9)->toDateTimeString())
            ->where('registered_at', '>', now()->subDays(18)->toDateTimeString())
            ->where('transaction_count', '>=', 5)
            ->update([
                'automation_state' => 5,
            ]);

        AutomationData::query()
            ->where('automation_state', '>=', 0)
            ->where('registered_at', '<=', now()->subDays(9)->toDateTimeString())
            ->where('registered_at', '>', now()->subDays(18)->toDateTimeString())
            ->where('transaction_count', '<', 5)
            ->update([
                'automation_state' => 6,
            ]);

        AutomationData::query()
            ->where('automation_state', '>=', 0)
            ->where('registered_at', '<=', now()->subDays(18)->toDateTimeString())
            ->where('registered_at', '>', now()->subDays(30)->toDateTimeString())
            ->where('transaction_count', '>=', 10)
            ->whereNotIn('premium_state', $premiumStates)
            ->update([
                'automation_state' => 7,
            ]);

        AutomationData::query()
            ->where('automation_state', '>=', 0)
            ->where('registered_at', '<=', now()->subDays(18)->toDateTimeString())
            ->where('registered_at', '>', now()->subDays(30)->toDateTimeString())
            ->where('transaction_count', '>=', 10)
            ->whereIn('premium_state', $premiumStates)
            ->update([
                'automation_state' => 8,
            ]);

        AutomationData::query()
            ->where('automation_state', '>=', 0)
            ->where('registered_at', '<=', now()->subDays(18)->toDateTimeString())
            ->where('registered_at', '>', now()->subDays(30)->toDateTimeString())
            ->where('transaction_count', '<=', 9)
            ->where('transaction_count', '>=', 5)
            ->update([
                'automation_state' => 9,
            ]);

        AutomationData::query()
            ->where('automation_state', '>=', 0)
            ->where('registered_at', '<=', now()->subDays(18)->toDateTimeString())
            ->where('registered_at', '>', now()->subDays(30)->toDateTimeString())
            ->where('transaction_count', '<=', 4)
            ->where('transaction_count', '>=', 1)
            ->update([
                'automation_state' => 10,
            ]);

        AutomationData::query()
            ->where('automation_state', '>=', 0)
            ->where('registered_at', '<=', now()->subDays(18)->toDateTimeString())
            ->where('registered_at', '>', now()->subDays(30)->toDateTimeString())
            ->where('transaction_count', 0)
            ->update([
                'automation_state' => 11,
            ]);

        AutomationData::query()
            ->where('automation_state', '>=', 0)
            ->where('registered_at', '<=', now()->subDays(30)->toDateTimeString())
            ->where('registered_at', '>', now()->subDays(45)->toDateTimeString())
            ->where('max_time', '>=', now()->subDays(10)->toDateTimeString())
            ->where('transaction_count', '>=', 25)
            ->whereNotIn('premium_state', $premiumStates)
            ->update([
                'automation_state' => 12,
            ]);

        AutomationData::query()
            ->where('automation_state', '>=', 0)
            ->where('registered_at', '<=', now()->subDays(30)->toDateTimeString())
            ->where('registered_at', '>', now()->subDays(45)->toDateTimeString())
            ->where('max_time', '>=', now()->subDays(10)->toDateTimeString())
            ->where('transaction_count', '>=', 25)
            ->whereIn('premium_state', $premiumStates)
            ->update([
                'automation_state' => 13,
            ]);

        AutomationData::query()
            ->where('automation_state', '>=', 0)
            ->where('registered_at', '<=', now()->subDays(30)->toDateTimeString())
            ->where('registered_at', '>', now()->subDays(45)->toDateTimeString())
            ->where('max_time', '>=', now()->subDays(10)->toDateTimeString())
            ->where('transaction_count', '<', 25)
            ->update([
                'automation_state' => 14,
            ]);

        AutomationData::query()
            ->where('automation_state', '>=', 0)
            ->where('registered_at', '<=', now()->subDays(30)->toDateTimeString())
            ->where('registered_at', '>', now()->subDays(45)->toDateTimeString())
            ->where(function ($query) {
                $query->where('max_time', '<', now()->subDays(10)->toDateTimeString())
                    ->orWhereNull('max_time');
            })
            ->where('transaction_count', '>=', 10)
            ->update([
                'automation_state' => 15,
            ]);

        AutomationData::query()
            ->where('automation_state', '>=', 0)
            ->where('registered_at', '<=', now()->subDays(30)->toDateTimeString())
            ->where('registered_at', '>', now()->subDays(45)->toDateTimeString())
            ->where(function ($query) {
                $query->where('max_time', '<', now()->subDays(10)->toDateTimeString())
                    ->orWhereNull('max_time');
            })
            ->where('transaction_count', '<', 10)
            ->update([
                'automation_state' => 16,
            ]);

        AutomationData::query()
            ->where('automation_state', '>=', 0)
            ->where('registered_at', '<=', now()->subDays(45)->toDateTimeString())
            ->where('registered_at', '>', now()->subDays(60)->toDateTimeString())
            ->where('max_time', '>=', now()->subDays(10)->toDateTimeString())
            ->where('transaction_count', '>=', 35)
            ->whereNotIn('premium_state', $premiumStates)
            ->update([
                'automation_state' => 17,
            ]);

        AutomationData::query()
            ->where('automation_state', '>=', 0)
            ->where('registered_at', '<=', now()->subDays(45)->toDateTimeString())
            ->where('registered_at', '>', now()->subDays(60)->toDateTimeString())
            ->where('max_time', '>=', now()->subDays(10)->toDateTimeString())
            ->where('transaction_count', '>=', 35)
            ->whereIn('premium_state', $premiumStates)
            ->update([
                'automation_state' => 18,
            ]);

        AutomationData::query()
            ->where('automation_state', '>=', 0)
            ->where('registered_at', '<=', now()->subDays(45)->toDateTimeString())
            ->where('registered_at', '>', now()->subDays(60)->toDateTimeString())
            ->where('max_time', '>=', now()->subDays(10)->toDateTimeString())
            ->where('transaction_count', '<', 35)
            ->update([
                'automation_state' => 19,
            ]);

        AutomationData::query()
            ->where('automation_state', '>=', 0)
            ->where('registered_at', '<=', now()->subDays(45)->toDateTimeString())
            ->where('registered_at', '>', now()->subDays(60)->toDateTimeString())
            ->where(function ($query) {
                $query->where('max_time', '<', now()->subDays(10)->toDateTimeString())
                    ->orWhereNull('max_time');
            })
            ->where('transaction_count', '>=', 20)
            ->update([
                'automation_state' => 20,
            ]);

        AutomationData::query()
            ->where('automation_state', '>=', 0)
            ->where('registered_at', '<=', now()->subDays(45)->toDateTimeString())
            ->where('registered_at', '>', now()->subDays(60)->toDateTimeString())
            ->where(function ($query) {
                $query->where('max_time', '<', now()->subDays(10)->toDateTimeString())
                    ->orWhereNull('max_time');
            })
            ->where('transaction_count', '<', 20)
            ->update([
                'automation_state' => 21,
            ]);

        $userStatusLog = UserStatusLog::query()
            ->where('status', true)
            ->whereNotIn('price_id', [PremiumDuration::ONE_WEEK, PremiumDuration::HALF_MONTH]);

        AutomationData::query()
            ->where('automation_state', '>=', 0)
            ->joinSub($userStatusLog->getQuery(), 'status_logs', 'status_logs.user_id', 'automation_data.id')
            ->where('registered_at', '<', now()->subDays(60)->toDateTimeString())
            ->where('max_time', '>=', now()->subDays(10)->toDateTimeString())
            ->where('premium_state', UserPremiumState::EXPIRED_PREMIUM)
            ->update([
                'automation_state' => 22,
            ]);

        AutomationData::query()
            ->where('automation_state', '>=', 0)
            ->where('registered_at', '<', now()->subDays(60)->toDateTimeString())
            ->where('max_time', '>=', now()->subDays(10)->toDateTimeString())
            ->where('premium_state', UserPremiumState::FREE)
            ->where('transaction_count', '>=', 50)
            ->update([
                'automation_state' => 23,
            ]);

        AutomationData::query()
            ->where('automation_state', '>=', 0)
            ->where('registered_at', '<', now()->subDays(60)->toDateTimeString())
            ->where('max_time', '>=', now()->subDays(10)->toDateTimeString())
            ->where('premium_state', UserPremiumState::FREE)
            ->where('transaction_count', '<', 50)
            ->update([
                'automation_state' => 24,
            ]);

        AutomationData::query()
            ->where('automation_state', '>=', 0)
            ->where('registered_at', '<', now()->subDays(60)->toDateTimeString())
            ->where('max_time', '>=', now()->subDays(10)->toDateTimeString())
            ->whereIn('premium_state', $premiumStates)
            ->update([
                'automation_state' => 25,
            ]);

        AutomationData::query()
            ->where('automation_state', '>=', 0)
            ->leftJoin('automation_burnt_users', 'automation_burnt_users.user_id', 'automation_data.id')
            ->where('registered_at', '<', now()->subDays(60)->toDateTimeString())
            ->where(function ($query) {
                $query->where('max_time', '<', now()->subDays(10)->toDateTimeString())
                    ->orWhereNull('max_time');
            })
            ->where('transaction_count', '>=', 20)
            ->whereNull('automation_burnt_users.id')
            ->update([
                'automation_state' => 26,
            ]);

        AutomationData::query()
            ->where('automation_state', '>=', 0)
            ->leftJoin('automation_burnt_users', 'automation_burnt_users.user_id', 'automation_data.id')
            ->where('registered_at', '<', now()->subDays(60)->toDateTimeString())
            ->where(function ($query) {
                $query->where('max_time', '<', now()->subDays(10)->toDateTimeString())
                    ->orWhereNull('max_time');
            })
            ->where('transaction_count', '>=', 20)
            ->whereNotNull('automation_burnt_users.id')
            ->update([
                'automation_state' => 28,
            ]);

        AutomationData::query()
            ->where('automation_state', '>=', 0)
            ->where('registered_at', '<', now()->subDays(60)->toDateTimeString())
            ->where(function ($query) {
                $query->where('max_time', '<', now()->subDays(10)->toDateTimeString())
                    ->orWhereNull('max_time');
            })
            ->where('transaction_count', '<', 20)
            ->update([
                'automation_state' => 27,
            ]);

        $this->info(now()->diff($start)->format('%i:%s.%f'));
        return null;
    }
}
