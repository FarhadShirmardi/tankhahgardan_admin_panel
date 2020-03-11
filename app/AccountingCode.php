<?php

namespace App;

use App\AccountingCode as AccountingCodes;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\AccountingCode
 *
 * @property int $id
 * @property int $type
 * @property string $code
 * @property int $level
 * @property int $accounting_code_id
 * @property string $accounting_code_type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read Model|Eloquent $hasAccountingCode
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|AccountingCodes onlyTrashed()
 * @method static bool|null restore()
 * @method static Builder|AccountingCodes whereAccountingCodeId($value)
 * @method static Builder|AccountingCodes whereAccountingCodeType($value)
 * @method static Builder|AccountingCodes whereCode($value)
 * @method static Builder|AccountingCodes whereCreatedAt($value)
 * @method static Builder|AccountingCodes whereDeletedAt($value)
 * @method static Builder|AccountingCodes whereId($value)
 * @method static Builder|AccountingCodes whereLevel($value)
 * @method static Builder|AccountingCodes whereType($value)
 * @method static Builder|AccountingCodes whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|AccountingCodes withTrashed()
 * @method static \Illuminate\Database\Query\Builder|AccountingCodes withoutTrashed()
 * @mixin Eloquent
 * @property int $model_id
 * @property string $model_type
 */
class AccountingCode extends Model
{
    protected $fillable = [
        'type',
        'code',
        'level'
    ];

    use SoftDeletes;

    public function hasAccountingCode()
    {
        return $this->morphTo(null, 'model_type', 'model_id');
    }
}
