<?php

namespace App;

use App\Helpers\Helpers;
use App\Payment as Payments;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Payment
 *
 * @property int $id
 * @property string $amount
 * @property string $description
 * @property string $date
 * @property int|null $imprest_id
 * @property int|null $payment_subject
 * @property int $creator_user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read TurnoverDetail $turnoverDetails
 * @method static Builder|Payments whereAmount($value)
 * @method static Builder|Payments whereContactId($value)
 * @method static Builder|Payments whereCreatedAt($value)
 * @method static Builder|Payments whereCreatorUserId($value)
 * @method static Builder|Payments whereDate($value)
 * @method static Builder|Payments whereDeletedAt($value)
 * @method static Builder|Payments whereDescription($value)
 * @method static Builder|Payments whereId($value)
 * @method static Builder|Payments whereImprestId($value)
 * @method static Builder|Payments whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int $project_id
 * @property-read Collection|Image[] $images
 * @method static Builder|Payments whereProjectId($value)
 */
class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'amount',
        'description',
        'date',
        'imprest_id',
        'project_id',
        'payment_subject',
        'creator_user_id'
    ];

    protected $casts = [
        'amount' => 'double'
    ];

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = Helpers::jalaliDateStringToGregorian($value);
    }

    public function getDateAttribute($value)
    {
        return Helpers::gregorianDateStringToJalali($value);
    }

    public function turnoverDetails()
    {
        return $this->hasMany(TurnoverDetail::class);
    }

    public function images()
    {
        return $this->morphMany(
            Image::class,
            'hasImage',
            'model_type',
            'model_id'
        );
    }

    public function imprest()
    {
        return $this->hasOne(Imprest::class, 'id', 'imprest_id');
    }

    public function sentPayment()
    {
        return $this->hasOne(SentPayment::class, 'source_id', 'id');
    }
}
