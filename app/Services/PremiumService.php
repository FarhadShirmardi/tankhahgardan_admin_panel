<?php

namespace App\Services;

use App\Data\EndPlanData;
use App\Enums\EndPlanReturnTypeEnum;
use App\Enums\LogType;
use App\Enums\PremiumDurationEnum;
use App\Models\PremiumPlan;
use App\Models\User;
use App\Models\UserStatus;
use Carbon\Carbon;

class PremiumService
{
    public static function getCancelPlanCreditAmount(UserStatus $userStatus): float|int
    {
        $remainPercent = PremiumService::calculatePercent($userStatus);
        /** @var PremiumPlan $previousPlan */
        $previousPlan = PremiumPlan::query()->find($userStatus->premium_plan_id);
        return roundDown(
            $remainPercent *
            PremiumDurationEnum::getItem(
                $userStatus->duration_id->value,
                $previousPlan->price,
                $previousPlan->yearly_discount
            )->price,
            0
        );
    }

    public static function endPlan(UserStatus $userStatus, EndPlanData $data): void
    {
        $userStatus->update(['end_date' => now()->toDateTimeString()]);
        if ($data->type == EndPlanReturnTypeEnum::WALLET) {
            $user = User::findOrFail($userStatus->user_id);
            $user->wallet += self::getCancelPlanCreditAmount($userStatus);
            $user->save();
        }

        $panelUser = auth()->user();
        $userStatus['close_type'] = $data->type;
        $userStatus['reason'] = $data->text;
        $logType = LogType::CLOSE_PLAN;
        $panelUser->logs()->create([
            'user_id' => $userStatus->user_id,
            'type' => $logType,
            'date_time' => now()->toDateTimeString(),
            'description' => $logType->description($panelUser),
            'old_json' => $userStatus->toJson(),
            'new_json' => json_encode([]),
        ]);

    }

    public static function calculatePercent(?UserStatus $userStatus): float|int
    {
        $percent = 1;
        if ($userStatus) {
            $carbon = new Carbon();
            $startDate = $carbon->parse($userStatus->start_date);
            $endDate = $carbon->parse($userStatus->end_date);
            $total = $startDate->diffInDays($endDate);
            $remain = $endDate->diffInDays(now());
            $percent = $total == 0 ? 0 : $remain / $total;
        }

        return $percent;
    }
}
