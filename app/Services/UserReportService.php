<?php

namespace App\Services;

use App\Constants\PremiumConstants;
use App\Enums\ProjectUserTypeEnum;
use App\Enums\UserPremiumStateEnum;
use App\Models\Device;
use App\Models\File;
use App\Models\Image;
use App\Models\Imprest;
use App\Models\Payment;
use App\Models\ProjectUser;
use App\Models\Receive;
use App\Models\StepByStep;
use App\Models\Ticket;
use App\Models\User;
use App\Models\UserReport;
use App\Models\UserStatus;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class UserReportService
{
    public static function getSingleUser(int $userId): UserReport|Model
    {
        return self::getUsersQuery([$userId])->first();
    }

    public static function getUsersQuery(?array $userIds = null): Builder
    {
        $countQuery = 'count(*)';
        $maxCreatedAtQuery = 'MAX(created_at)';

        $projectUserIdsQuery = ProjectUser::query()
            ->whereColumn('user_id', 'users.id')
            ->select('id')
            ->getQuery();

        $paymentCountQuery = Payment::query()
            ->withoutTrashed()
            ->whereIn('project_user_id', $projectUserIdsQuery)
            ->selectRaw($countQuery)
            ->getQuery();
        $receiveCountQuery = Receive::query()
            ->withoutTrashed()
            ->whereIn('project_user_id', $projectUserIdsQuery)
            ->selectRaw($countQuery)
            ->getQuery();
        $imprestCountQuery = Imprest::query()
            ->withoutTrashed()
            ->whereIn('project_user_id', $projectUserIdsQuery)
            ->selectRaw($countQuery)
            ->getQuery();
        $fileCountQuery = File::query()
            ->whereColumn('creator_user_id', 'users.id')
            ->selectRaw($countQuery)
            ->getQuery();
        $paymentImageCountQuery = Image::query()
            ->withoutTrashed()
            ->join('payments', 'payments.id', 'images.model_id')
            ->whereIn('payments.project_user_id', $projectUserIdsQuery)
            ->where('model_type', (new Payment())->getMorphClass())
            ->selectRaw($countQuery)
            ->getQuery();
        $receiveImageCountQuery = Image::query()
            ->withoutTrashed()
            ->join('receives', 'receives.id', 'images.model_id')
            ->whereIn('receives.project_user_id', $projectUserIdsQuery)
            ->where('model_type', (new Receive())->getMorphClass())
            ->selectRaw($countQuery)
            ->getQuery();
        $deviceCountQuery = Device::query()
            ->whereColumn('user_id', 'users.id')
            ->selectRaw($countQuery)
            ->getQuery();
        $ticketCountQuery = Ticket::query()
            ->whereColumn('user_id', 'users.id')
            ->selectRaw($countQuery)
            ->getQuery();

        $paymentMaxQuery = Payment::withoutTrashed()
            ->whereIn('project_user_id', $projectUserIdsQuery)
            ->selectRaw($maxCreatedAtQuery)
            ->toSql();
        $receiveMaxQuery = Receive::withoutTrashed()
            ->whereIn('project_user_id', $projectUserIdsQuery)
            ->selectRaw($maxCreatedAtQuery)
            ->toSql();
        $imprestMaxQuery = Imprest::withoutTrashed()
            ->whereIn('project_user_id', $projectUserIdsQuery)
            ->selectRaw($maxCreatedAtQuery)
            ->toSql();

        $projectCount = ProjectUser::withoutTrashed()
            ->whereColumn('user_id', 'users.id')
            ->selectRaw('count(distinct project_user.project_id)')
            ->getQuery();
        $ownProjectCount = ProjectUser::withoutTrashed()
            ->whereColumn('user_id', 'users.id')
            ->where('user_type', ProjectUserTypeEnum::OWNER)
            ->selectRaw($countQuery)
            ->getQuery();

        $stepByStep = StepByStep::query()
            ->whereColumn('user_id', 'users.id')
            ->selectRaw('IFNULL(step, 0)')
            ->getQuery();

        $maxTimeQuery = User::query()
            ->when(filled($userIds), fn ($q) => $q->whereIn('id', $userIds))
            ->selectRaw(
                "NULLIF(
                    GREATEST(
                        COALESCE((".$paymentMaxQuery."), 0),
                        COALESCE((".$receiveMaxQuery."), 0),
                        COALESCE((".$imprestMaxQuery."), 0)
                    ),
                    0
                ) as max_time, users.id as user_id"
            );

        $times = self::timesArray();

        $userTypeQuery = '';
        foreach ($times as $key => $time) {
            if ($key == count($times)) {
                $userTypeQuery .= $time[2].str_repeat(')', $key - 1).' as user_type';
            } else {
                $userTypeQuery .= 'IF(MaxTime.max_time '.$time[0].' \''.$time[1].'\', '.$time[2].', ';
            }
        }
        $userStateQuery = UserStatus::query()
            ->whereColumn('user_id', 'users.id')
            ->orderBy('end_date', 'DESC')
            ->limit(1)
            ->selectRaw("
                IF(
                    user_statuses.end_date < '".now()->toDateTimeString()."',
                    ".UserPremiumStateEnum::EXPIRED_PREMIUM->value.",
                    IF(
                        user_statuses.end_date < '".now()->addDays(PremiumConstants::NEAR_END_THRESHOLD)->toDateTimeString()."',
                        ".UserPremiumStateEnum::NEAR_ENDING_PREMIUM->value.",
                        ".UserPremiumStateEnum::PREMIUM->value."
                    )
                )
        ");


        return User::query()
            ->when(filled($userIds), fn ($q) => $q->whereIn('id', $userIds))
            ->joinSub($maxTimeQuery, 'MaxTime', 'MaxTime.user_id', '=', 'users.id')
            ->addSelect('users.id as id')
            ->selectRaw("CONCAT_WS(' ', IFNULL(users.name, ''), IFNULL(users.family, '')) as name")
            ->selectRaw("0 as image_count")
            ->selectSub($paymentImageCountQuery, 'payment_image_count')
            ->selectSub($receiveImageCountQuery, 'receive_image_count')
            ->addSelect('phone_number')
            ->addSelect(DB::raw('IFNULL(users.verification_time, users.created_at) as registered_at'))
            ->selectSub($paymentCountQuery, 'payment_count')
            ->selectSub($receiveCountQuery, 'receive_count')
            ->selectSub($imprestCountQuery, 'imprest_count')
            ->selectSub($fileCountQuery, 'file_count')
            ->selectSub($deviceCountQuery, 'device_count')
            ->selectSub($ticketCountQuery, 'ticket_count')
            ->selectSub($stepByStep, 'step_by_step')
            ->selectSub($projectCount, 'project_count')
            ->selectSub($ownProjectCount, 'own_project_count')
            ->selectRaw('MaxTime.max_time as max_time')
            ->selectRaw($userTypeQuery)
            ->selectRaw("IFNULL( (".$userStateQuery->toSql()." ), ".UserPremiumStateEnum::FREE->value.") as user_state");
    }

    public static function timesArray(): array
    {
        return [
            1 => ['>=', now()->subDays(7)->toDateTimeString(), 1],
            2 => ['>=', now()->subDays(14)->toDateTimeString(), 2],
            3 => ['>=', now()->subMonths()->toDateTimeString(), 3],
            4 => ['<', now()->subMonths()->toDateTimeString(), 4],
        ];
    }

    public static function updateImageCount(): void
    {
        UserReport::query()
            ->update(['image_count' => DB::raw('payment_image_count + receive_image_count')]);
    }
}
