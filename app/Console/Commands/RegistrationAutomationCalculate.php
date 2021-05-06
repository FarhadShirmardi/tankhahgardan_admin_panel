<?php

namespace App\Console\Commands;

use App\AutomationData;
use App\Constants\PremiumConstants;
use App\Constants\UserPremiumState;
use App\Payment;
use App\Receive;
use App\User;
use App\UserStatus;
use Illuminate\Console\Command;

class RegistrationAutomationCalculate extends Command
{
    public $automationStartDate = '2021-01-21 00:00:00';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'automation:calculate';

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
        $users = User::query()->whereNotNull('verification_time')->with('ownedProjects')->get();
        $finalResults = collect();
        $bar = $this->output->createProgressBar($users->count());
        foreach ($users as $user) {
//            dd($this->getRegistrationAutomationQuery($user));
            $finalResults->push(
                $this->getRegistrationAutomationQuery($user)
            );
            $bar->advance();
        }
        AutomationData::query()->truncate();
        $chunkedResults = $finalResults->chunk(5000);
        foreach ($chunkedResults as $chunkedResult) {
            AutomationData::query()->insert($chunkedResult->toArray());
        }
        $this->info(now()->diff($start)->format('%i:%s.%f'));
    }

    public function getRegistrationAutomationQuery(User $user)
    {
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

        $transactions = \DB::query()
            ->fromSub(
                $payments->unionAll($receives),
                't'
            )
            ->get([
                'created_at',
            ]);

        $userStateQuery = UserStatus::query()
            ->whereColumn('user_id', 'users.id')
            ->orderBy('end_date', 'DESC')
            ->limit(1)
            ->selectRaw("
                IF(
                    user_statuses.end_date < '" . now()->toDateTimeString() . "',
                    " . UserPremiumState::EXPIRED_PREMIUM . ",
                    IF(
                        user_statuses.end_date < '" . now()->addDays(PremiumConstants::NEAR_END_THRESHOLD)->toDateTimeString() . "',
                        " . UserPremiumState::NEAR_ENDING_PREMIUM . ",
                        " . UserPremiumState::PREMIUM . "
                    )
                )
        ");

        $result = User::query()
            ->without('userStatus')
            ->where('users.id', $userId)
            ->select([
                'id',
                \DB::raw("CONCAT_WS(' ', IFNULL(users.name, ''), IFNULL(users.family, '')) as name"),
                \DB::raw("IFNULL( (" . $userStateQuery->toSql() . " ), " . UserPremiumState::FREE . ") as premium_state"),
                \DB::raw("IF(users.verification_time > '{$this->automationStartDate}', IF(users.state = 0, 0, 1), -1) as automation_state"),
                'users.verification_time as registered_at',
                'phone_number',
            ]);
        $result = $result->first();


        $result['max_time'] = $transactions->max('created_at');
        $result['transaction_count'] = $transactions->count();
        $result = $result->toArray();
        unset($result['user_statuses']);
        return $result;
    }
}
