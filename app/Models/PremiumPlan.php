<?php

namespace App\Models;

use App\Enums\PremiumPlanEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PremiumPlan extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $fillable = [
        'type',
        'price',
        'yearly_discount',
        'features',
        'limits',
        'is_active',
        'is_buyable',
    ];

    protected $casts = [
        'type' => PremiumPlanEnum::class,
        'features' => 'array',
        'limits' => 'array',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeBuyable(Builder $query): Builder
    {
        return $query->where('is_buyable', true);
    }

    public function scopeByType(Builder $query, PremiumPlanEnum $type): Builder
    {
        return $query->where('type', $type->value);
    }
}
