<?php

namespace App;

use App\Image as Images;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Image
 *
 * @property int $id
 * @property int $model_id
 * @property string $model_type
 * @property string $path
 * @property int $size
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read Model|Eloquent $hasImage
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|Images onlyTrashed()
 * @method static bool|null restore()
 * @method static Builder|Images whereCreatedAt($value)
 * @method static Builder|Images whereDeletedAt($value)
 * @method static Builder|Images whereId($value)
 * @method static Builder|Images whereModelId($value)
 * @method static Builder|Images whereModelType($value)
 * @method static Builder|Images wherePath($value)
 * @method static Builder|Images whereSize($value)
 * @method static Builder|Images whereUpdatedAt($value)
 * @method static Builder|Images whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Images withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Images withoutTrashed()
 * @mixin Eloquent
 */
class Image extends Model
{
    protected $fillable = [
        'path',
        'size',
        'user_id',
        'project_id',
    ];

    use SoftDeletes;

    public function hasImage()
    {
        return $this->morphTo(
            null,
            'model_type',
            'model_id'
        );
    }
}
