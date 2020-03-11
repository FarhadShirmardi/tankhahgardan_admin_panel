<?php

namespace App;

use App\Helpers\Helpers;
use App\Note as Notes;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Builder as Builders;
use Illuminate\Support\Carbon;

/**
 * App\Note
 *
 * @property int $id
 * @property int $project_id
 * @property string $text
 * @property string $date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static Builders|Notes onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|Notes whereContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notes whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notes whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notes whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notes whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notes whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notes whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notes whereUpdatedAt($value)
 * @method static Builders|Notes withTrashed()
 * @method static Builders|Notes withoutTrashed()
 * @mixin Eloquent
 */
class Note extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'text',
        'project_id',
        'creator_user_id',
        'date',
        'is_done',
        'related_user_id',
        'reminder_id',
        'description',
        'reminder_id',
    ];

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = Helpers::jalaliDateStringToGregorian($value);
    }

    public function getDateAttribute($value)
    {
        return Helpers::gregorianDateStringToJalali($value);
    }

    public function reminder()
    {
        return $this->hasOne(Reminder::class, 'id', 'reminder_id');
    }
}
