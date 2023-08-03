<?php

namespace App\Models;

use App\Enums\PurchaseTypeEnum;
use App\Enums\UserStatusTypeEnum;
use App\Helpers\UtilHelpers;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $connection = 'mysql_panel';

    protected $fillable = [
        'start_date',
        'end_date',
        'wallet_amount',
        'total_amount',
        'type',
        'status',
        'discount_amount',
        'added_value_amount',
        'premium_plan_id',
        'duration_id',
        'credit_amount',
        'campaign_id',
        'promo_code_id'
    ];

    protected $casts = [
        'type' => PurchaseTypeEnum::class,
        'status' => UserStatusTypeEnum::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function payableAmount(): Attribute
    {
        return Attribute::make(
            get: fn () => UtilHelpers::getPayableAmount(
                $this->total_amount,
                $this->added_value_amount,
                $this->discount_amount,
                $this->wallet_amount,
                $this->credit_amount
            ),
        );
    }

    public function premiumPlan(): BelongsTo
    {
        return $this->belongsTo(PremiumPlan::class);
    }
}
