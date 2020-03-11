<?php

namespace App;

use App\Helpers\Helpers;
use App\Receive as Receives;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Receive
 *
 * @property int $id
 * @property string $amount
 * @property string $description
 * @property string $date
 * @property int|null $imprest_id
 * @property int $creator_user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read TurnoverDetail $turnoverDetails
 * @method static Builder|Payment whereAmount($value)
 * @method static Builder|Payment whereContactId($value)
 * @method static Builder|Payment whereCreatedAt($value)
 * @method static Builder|Payment whereCreatorUserId($value)
 * @method static Builder|Payment whereDate($value)
 * @method static Builder|Payment whereDeletedAt($value)
 * @method static Builder|Payment whereDescription($value)
 * @method static Builder|Payment whereId($value)
 * @method static Builder|Payment whereImprestId($value)
 * @method static Builder|Payment whereUpdatedAt($value)
 * @mixin Eloquent
 * @property string $receive_subject
 * @method static Builder|Receives whereReceiveSubject($value)
 * @property int $project_id
 * @property-read Collection|Image[] $images
 * @method static Builder|Receives whereProjectId($value)
 */
class Receive extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'amount',
        'description',
        'date',
        'imprest_id',
        'project_id',
        'receive_subject',
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

    public function sentTurnoverDetails()
    {
        return $this->hasMany(SentTurnoverDetail::class);
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

    public function sentReceive()
    {
        return $this->hasOne(SentReceive::class, 'source_id', 'id');
    }
}
