<?php

namespace App;

use App\AccountingSoftware as AccountingSoftwares;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\AccountingSoftware
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|AccountingSoftwares whereCreatedAt($value)
 * @method static Builder|AccountingSoftwares whereId($value)
 * @method static Builder|AccountingSoftwares whereName($value)
 * @method static Builder|AccountingSoftwares whereUpdatedAt($value)
 * @mixin Eloquent
 */
class AccountingSoftware extends Model
{
    //
}
