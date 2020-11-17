<?php

namespace App;

use App\Helpers\UtilHelpers;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Helpers;

class ProjectStatusLog extends Model
{
    protected $connection = 'mysql';
    protected $fillable = [
        'transaction_id',
        'status',
        'start_date',
        'end_date',
        'volume_size',
        'user_count',
        'total_amount',
        'discount_amount',
        'added_value_amount',
        'price_id',
        'promo_code_id',
        'campaign_id',
        'type'
    ];
    protected $with = ['transaction'];

    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'id', 'transaction_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function promoCode()
    {
        return $this->hasOne(PromoCode::class, 'id', 'promo_code_id');
    }

    public function getPayableAmountAttribute($value)
    {
        return Helpers::calculatePayableAmount($this);
    }
}
