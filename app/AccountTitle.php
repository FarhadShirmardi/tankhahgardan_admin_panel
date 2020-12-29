<?php

namespace App;

use App\AccountTitle as AccountTitles;
use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;

/**
 * App\AccountTitle
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $project_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read Project $project
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTitles whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTitles whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTitles whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTitles whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTitles whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTitles whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTitles whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read Collection|AccountingCode[] $accountingCodes
 * @method static bool|null forceDelete()
 * @method static Builder|AccountTitles onlyTrashed()
 * @method static bool|null restore()
 * @method static Builder|AccountTitles withTrashed()
 * @method static Builder|AccountTitles withoutTrashed()
 */
class AccountTitle extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'project_id'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function accountingCodes()
    {
        return $this->morphMany(
            AccountingCode::class,
            'hasAccountingCode',
            'model_type',
            'model_id'
        );
    }
}
