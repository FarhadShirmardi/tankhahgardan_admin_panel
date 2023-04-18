<?php

namespace App\Services;

use App\Enums\UserStatusTypeEnum;
use App\Models\PromoCode;
use App\Models\User;
use App\Models\UserStatusLog;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class PromoCodeService
{
    public static function promoCodesQuery(User $user): EloquentBuilder|PromoCode
    {
        $usedPromoQuery = UserStatusLog::query()
            ->join('project_user', 'project_user.user_id', 'user_status_logs.user_id')
            ->where('status', '<>', UserStatusTypeEnum::FAILED)
            ->whereColumn('promo_code_id', 'promo_codes.id')
            ->where('project_user.user_id', $user->id)
            ->where('project_user.is_owner', true)
            ->selectRaw('count(*)')
            ->getQuery();

        $totalUserQuery = UserStatusLog::query()
            ->where('status', '<>', UserStatusTypeEnum::FAILED)
            ->whereColumn('promo_code_id', 'promo_codes.id')
            ->selectRaw('count(*)')
            ->getQuery();

        return PromoCode::query()
            ->where(fn ($query) => $query->where('expire_at', '>', now()->toDateTimeString())->orWhereNull('expire_at'))
            ->where(fn ($query) => $query->where('start_at', '<=', now()->toDateTimeString()))
            ->where(fn ($query) => $query->whereNull('user_id')->orWhere('user_id', $user->id))
//            ->whereRaw('max_count - reserve_count > 0')
            ->selectSub($usedPromoQuery, 'used_promo_code_count')
            ->selectSub($totalUserQuery, 'total_promo_code_count')
            ->addSelect([
                'id',
                'max_count',
                'reserve_count',
                'code',
                'discount_percent',
                'max_discount',
                'start_at',
                'expire_at',
                'text',
            ])
            ->havingRaw(' used_promo_code_count <= 0 and max_count - reserve_count - total_promo_code_count > 0');
    }

    public static function getDiscountAmount(int $amount, PromoCode $promoCode): float
    {
        if ($promoCode->discount_percent) {
            $discountAmount = $amount * $promoCode->discount_percent / 100;
            if ($promoCode->max_discount) {
                $discountAmount = min($discountAmount, $promoCode->max_discount);
            }
        } else {
            $discountAmount = $promoCode->max_discount;
        }
        return floor($discountAmount ?? 0);
    }
}