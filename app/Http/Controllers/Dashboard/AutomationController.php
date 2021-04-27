<?php

namespace App\Http\Controllers\Dashboard;

use App\Constants\PremiumConstants;
use App\Constants\UserPremiumState;
use App\Http\Controllers\Controller;
use App\Payment;
use App\Receive;
use App\User;
use App\UserStatus;

class AutomationController extends Controller
{
    public function getRegistrationAutomation()
    {
        $start = now();
        $users = User::query()->with('ownedProjects')
            ->where('users.verification_time', '>', now()->subDays(60))
            ->get();

        $finalResults = collect();
        foreach ($users as $user) {
            $finalResults->push(
                $this->getRegistrationAutomationQuery($user)
            );
        }
        return $finalResults->take(10);
    }

    public function getRegistrationAutomationQuery(User &$user)
    {
        $userId = $user->id;
        $projectIds = $user->ownedProjects->pluck('id')->toArray();

        $payments = Payment::query()
            ->where('creator_user_id', $userId)
            ->orWhereIn('project_id', $projectIds)
            ->select(['created_at'])
            ->getQuery();

        $receives = Receive::query()
            ->where('creator_user_id', $userId)
            ->orWhereIn('project_id', $projectIds)
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
            ->where('users.id', $userId)
            ->first([
                \DB::raw("CONCAT_WS(' ', IFNULL(users.name, ''), IFNULL(users.family, '')) as name"),
                \DB::raw("IFNULL( (" . $userStateQuery->toSql() . " ), " . UserPremiumState::FREE . ") as user_state"),
                'users.verification_time as registered_at',
                'phone_number',
            ]);

        $result['max_time'] = $transactions->max('created_at');
        $result['total_count'] = $transactions->count();
        return $result;
    }
}
