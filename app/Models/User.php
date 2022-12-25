<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /** @return Attribute<string, never> */
    protected function fullName(): Attribute
    {
        $fullName = trim("$this->name $this->family");
        return Attribute::make(
            get: fn () => $fullName == "" ? " - " : $fullName
        );
    }

    /** @return Attribute<string, never> */
    protected function username(): Attribute
    {
        return Attribute::make(
            get: fn () => trim((($this->name or $this->family) ? $this->full_name : reformatPhoneNumber($this->phone_number)) ?? '')
        );
    }
}
