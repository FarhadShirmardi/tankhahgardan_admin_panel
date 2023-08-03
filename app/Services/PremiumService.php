<?php

namespace App\Services;

use App\Data\EndPremiumPlanData;
use App\Data\PayInvoiceData;
use App\Enums\EndPremiumPlanReturnTypeEnum;
use App\Enums\LogType;
use App\Enums\PremiumDurationEnum;
use App\Models\Invoice;
use App\Models\PremiumPlan;
use App\Models\User;
use App\Models\UserStatus;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;

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

    public static function endPlan(UserStatus $userStatus, EndPremiumPlanData $data): void
    {
        $userStatus->update(['end_date' => now()->toDateTimeString()]);
        if ($data->type == EndPremiumPlanReturnTypeEnum::WALLET) {
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

    public static function payInvoice(Invoice $invoice, PayInvoiceData $data): bool
    {
        $response = Http::get(config('app.app_direct_url')."/invoice/$invoice->id/pay");
        if (!$response->successful()) {
            Notification::make()
                ->danger()
                ->title(__('message.invoice payed failed'))
                ->send();
            return false;
        }

        $panelUser = auth()->user();
        $invoice['reason'] = $data->text;
        $logType = LogType::CLOSE_PLAN;
        $panelUser->logs()->create([
            'user_id' => $invoice->user_id,
            'type' => $logType,
            'date_time' => now()->toDateTimeString(),
            'description' => $logType->description($panelUser),
            'old_json' => $invoice->toJson(),
            'new_json' => json_encode([]),
        ]);

        return true;
    }
}
