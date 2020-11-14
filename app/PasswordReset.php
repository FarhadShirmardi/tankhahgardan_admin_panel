<?php

namespace App;

use App\Helpers\Helpers;
use App\PasswordReset as Password;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Builder as Builders;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\PasswordReset
 *
 * @property int $id
 * @property string $phone_number
 * @property string $token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builders|Password whereCreatedAt($value)
 * @method static Builders|Password whereId($value)
 * @method static Builders|Password wherePhoneNumber($value)
 * @method static Builders|Password whereToken($value)
 * @method static Builders|Password whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PasswordReset extends Model
{
    protected $fillable = [
        'phone_number', 'token', 'reset_token'
    ];


    public function setPhoneNumberAttribute($value)
    {
        return $this->attributes['phone_number'] = Helpers::formatPhoneNumber($value);
    }
}
