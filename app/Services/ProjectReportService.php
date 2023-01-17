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
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\Receive;
use App\Models\StepByStep;
use App\Models\Ticket;
use App\Models\User;
use App\Models\UserReport;
use App\Models\UserStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ProjectReportService
{
    public static function getSingleProject(int $projectId): UserReport|Model
    {
        return self::getProjectsQuery([$projectId])->first();
    }

    public static function getProjectsQuery(?array $projectIds = null): Builder
    {
        $countQuery = 'count(*)';
        $maxCreatedAtQuery = 'MAX(created_at)';

        $projectUserIdsQuery = ProjectUser::query()
            ->whereColumn('project_id', 'projects.id')
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

        $userCountQuery = ProjectUser::withoutTrashed()
            ->whereColumn('project_id', 'projects.id')
            ->selectRaw('count(distinct project_user.user_id)')
            ->getQuery();
        $activeUserCountQuery = ProjectUser::withoutTrashed()
            ->whereColumn('user_id', 'projects.id')
            ->isActive()
            ->selectRaw($countQuery)
            ->getQuery();

        $maxTimeQuery = Project::query()
            ->when(filled($projectIds), fn ($q) => $q->whereIn('id', $projectIds))
            ->selectRaw(
                "NULLIF(
                    GREATEST(
                        COALESCE((".$paymentMaxQuery."), 0),
                        COALESCE((".$receiveMaxQuery."), 0),
                        COALESCE((".$imprestMaxQuery."), 0)
                    ),
                    0
                ) as max_time, projects.id as project_id"
            );

        $times = UserReportService::timesArray();

        $projectTypeQuery = '';
        foreach ($times as $key => $time) {
            if ($key == count($times)) {
                $projectTypeQuery .= $time[2].str_repeat(')', $key - 1).' as project_type';
            } else {
                $projectTypeQuery .= 'IF(MaxTime.max_time '.$time[0].' \''.$time[1].'\', '.$time[2].', ';
            }
        }

        return Project::query()
            ->when(filled($projectIds), fn ($q) => $q->whereIn('id', $projectIds))
            ->joinSub($maxTimeQuery, 'MaxTime', 'MaxTime.project_id', '=', 'projects.id')
            ->addSelect('projects.name as name')
            ->addSelect('projects.id as id')
            ->addSelect('projects.city_id as city_id')
            ->addSelect('projects.province_id as province_id')
            ->addSelect('projects.created_at as created_at')
            ->addSelect('projects.type as type')
            ->selectSub($paymentCountQuery, 'payment_count')
            ->selectSub($receiveCountQuery, 'receive_count')
            ->selectSub($imprestCountQuery, 'imprest_count')
            ->selectSub($userCountQuery, 'user_count')
            ->selectSub($activeUserCountQuery, 'active_user_count')
            ->selectRaw('MaxTime.max_time as max_time')
            ->selectRaw($projectTypeQuery);
    }
}
