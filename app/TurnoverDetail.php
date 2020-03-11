<?php

namespace App;

use App\Helpers\Helpers;
use App\TurnoverDetail as TurnoverDetails;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\TurnoverDetail
 *
 * @property int $id
 * @property string $amount
 * @property string $description
 * @property int $account_title_id
 * @property int|null $payment_id
 * @property int|null $receive_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read AccountTitle $accountTitle
 * @property-read Project $project
 * @method static Builder|TurnoverDetails whereAccountTitleId($value)
 * @method static Builder|TurnoverDetails whereAmount($value)
 * @method static Builder|TurnoverDetails whereCreatedAt($value)
 * @method static Builder|TurnoverDetails whereDeletedAt($value)
 * @method static Builder|TurnoverDetails whereDescription($value)
 * @method static Builder|TurnoverDetails whereId($value)
 * @method static Builder|TurnoverDetails wherePaymentId($value)
 * @method static Builder|TurnoverDetails whereReceiveId($value)
 * @method static Builder|TurnoverDetails whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|TurnoverDetails onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|TurnoverDetails withTrashed()
 * @method static \Illuminate\Database\Query\Builder|TurnoverDetails withoutTrashed()
 */
class TurnoverDetail extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'amount',
        'description',
        'account_title_id'
    ];

    protected $casts = [
        'amount' => 'double'
    ];

    protected $dates = ['deleted_at'];

    public function accountTitle()
    {
        return $this->hasOne(AccountTitle::class, 'id', 'account_title_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function payments()
    {
        return $this->belongsToMany(Payment::class);
    }

    public function receives()
    {
        return $this->belongsToMany(Receive::class);
    }

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = Helpers::jalaliDateStringToGregorian($value);
    }

    public function getDateAttribute($value)
    {
        return Helpers::gregorianDateStringToJalali($value);
    }
}
