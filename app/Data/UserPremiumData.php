<?php

namespace App\Data;

use App\Enums\PremiumPlanEnum;
use App\Enums\ProjectUserStateEnum;
use App\Models\File;
use App\Models\Image;
use App\Models\Imprest;
use App\Models\Payment;
use App\Models\PremiumPlan;
use App\Models\ProjectUser;
use App\Models\Receive;
use App\Models\User;
use App\Models\UserStatus;
use DB;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Spatie\LaravelData\Data;

class UserPremiumData extends Data
{
    public int $user_id;

    public int $transaction_count_limit;

    public int $transaction_count_remain;

    public int $image_count_limit;

    public int $image_count_remain;

    public int $project_count_limit;

    public int $project_count_remain;

    public int $imprest_count_limit;

    public int $imprest_count_remain;

    public int $transaction_image_count_limit;

    public int $user_count_limit;

    public int $user_count_remain;

    public int $pdf_count_limit;

    public int $pdf_count_remain;

    public int $monthly_report_limit;

    public int $hashtag_report_limit;

    public int $read_sms_limit;

    public int $account_title_import_limit;

    public int $transaction_print_limit;

    public int $excel_count_limit;

    public int $memo_count_limit;

    public int $reminder_count_limit;

    public int $task_count_limit;

    public int $transaction_duplicate_limit;

    public int $contact_report_limit;

    public int $offline_transaction_limit;

    public int $transaction_import_limit;

    public int $accountant_report_limit;

    public int $monthly_budget_limit;

    public int $transaction_copy_limit;

    public int $admin_transaction_limit;

    public int $team_limit;

    public int $admin_panel_limit;

    public int $imprest_budget_limit;

    public int $team_level_limit;

    public function __construct(private readonly User $user)
    {
        $this->user_id = $user->id;

        $limits = $this->getUserCurrentLimits();
        $projects = $user->ownedProjects()->get();
        $projectIds = $projects->pluck('id')->toArray();
        $projectUserIds = ProjectUser::query()
            ->whereIn('project_id', $projectIds)
            ->pluck('id')
            ->toArray();

        $remains = $this->getRemains($projectIds, $projectUserIds);

        foreach ($limits as $limitKey => $limit) {
            if (property_exists(self::class, $limitKey)) {
                $this->{$limitKey} = $limit;
            }
        }

        foreach ($remains as $key => $used) {
            $limitKey = $key.'_limit';
            $remainKey = $key.'_remain';
            if (property_exists(self::class, $remainKey)) {
                $this->{$remainKey} = $this->{$limitKey} - $used;
            }
        }
        $this->transaction_count_remain = $this->transaction_count_limit -
            ($remains->payment_count + $remains->receive_count);
        $this->project_count_remain = $this->project_count_limit - count($projectIds);
    }

    private function getUserCurrentLimits(): array
    {
        /** @var ?UserStatus $userStatus */
        $userStatus = $this->user->current_user_status;
        $limits = PremiumPlan::query()
            ->active()
            ->byType(PremiumPlanEnum::FREE)
            ->value('limits');
        if ($userStatus and ! is_null($userStatus->premium_plan_id)) {
            $limits = PremiumPlan::query()
                          ->findOrFail($userStatus->premium_plan_id)['limits'];
        }

        return $limits;
    }

    private function getImageQuery(array $projectUserIds): Builder
    {
        return Image::query()
            ->whereHasMorph('hasImage', [Payment::class, Receive::class], function (EloquentBuilder $query) use ($projectUserIds) {
                $query->whereIn('project_user_id', $projectUserIds);
            })
            ->withoutTrashed()
            ->selectRaw('count(*)')
            ->getQuery();
    }

    private function getProjectUserQuery(array $projectIds): Builder
    {
        return ProjectUser::query()
            ->withoutTrashed()
            ->whereIn('project_id', $projectIds)
            ->where('user_id', '<>', $this->user_id)
            ->where('project_user.state', '<>', ProjectUserStateEnum::INACTIVE)
            ->selectRaw('count(DISTINCT(user_id))')
            ->getQuery();
    }

    private function getPdfQuery(array $projectIds): Builder
    {
        return File::query()
            ->whereIn('project_id', $projectIds)
            ->where('type', 'like', '%PDF')
            ->whereDate('created_at', now()->toDateString())
            ->selectRaw('count(*)')
            ->getQuery();
    }

    private function getImprestQuery(array $projectUserIds): Builder
    {
        return Imprest::query()
            ->withoutTrashed()
            ->whereIn('project_user_id', $projectUserIds)
            ->selectRaw('count(*)')
            ->getQuery();
    }

    private function getPaymentQuery(array $projectUserIds): Builder
    {
        return Payment::query()
            ->withoutTrashed()
            ->whereIn('project_user_id', $projectUserIds)
            ->selectRaw('count(*)')
            ->getQuery();
    }

    private function getReceiveQuery(array $projectUserIds): Builder
    {
        return Receive::query()
            ->withoutTrashed()
            ->whereIn('project_user_id', $projectUserIds)
            ->selectRaw('count(*)')
            ->getQuery();
    }

    private function getRemains(array $projectIds, array $projectUserIds): object
    {
        $imageCount = $this->getImageQuery($projectUserIds);
        $userCount = $this->getProjectUserQuery($projectIds);
        $pdfCount = $this->getPdfQuery($projectIds);
        $imprestCount = $this->getImprestQuery($projectUserIds);
        $paymentCount = $this->getPaymentQuery($projectUserIds);
        $receiveCount = $this->getReceiveQuery($projectUserIds);
        $query = DB::query()
            ->selectSub($imageCount, 'image_count')
            ->selectSub($userCount, 'user_count')
            ->selectSub($pdfCount, 'pdf_count')
            ->selectSub($imprestCount, 'imprest_count')
            ->selectSub($paymentCount, 'payment_count')
            ->selectSub($receiveCount, 'receive_count');
        /** @var object $results */
        return $query->first();
    }
}
