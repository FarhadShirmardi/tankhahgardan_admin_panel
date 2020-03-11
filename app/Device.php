<?php

namespace App;

use App\Device as Devices;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Device
 *
 * @mixin Eloquent
 * @property int $id
 * @property string|null $client_id
 * @property string|null $token
 * @property string|null $token_generation_time
 * @property int $user_id
 * @property string|null $device_model
 * @property string|null $device_info
 * @property string|null $last_update_time
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static Builder|Devices whereClientId($value)
 * @method static Builder|Devices whereCreatedAt($value)
 * @method static Builder|Devices whereDeletedAt($value)
 * @method static Builder|Devices whereDeviceInfo($value)
 * @method static Builder|Devices whereDeviceModel($value)
 * @method static Builder|Devices whereId($value)
 * @method static Builder|Devices whereLastUpdateTime($value)
 * @method static Builder|Devices whereToken($value)
 * @method static Builder|Devices whereTokenGenerationTime($value)
 * @method static Builder|Devices whereUpdatedAt($value)
 * @method static Builder|Devices whereUserId($value)
 */
class Device extends Model
{
    protected $fillable = [
        'user_id',
        'serial',
        'model',
        'platform',
        'os_version'
    ];
}
