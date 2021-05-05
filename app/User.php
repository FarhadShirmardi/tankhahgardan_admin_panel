<?php

namespace App;

use App\Helpers\Helpers;
use App\Helpers\UtilHelpers;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $connection = 'mysql';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'family', 'email', 'phone_number',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'verification_code', 'vcode_genration_time', 'state',
    ];

    protected $dates = [
        'vcode_generation_time',
        'last_sms_time',
        'created_at',
        'updated_at',
    ];

    protected $with = ['userStatus'];

    public function getPhoneNumberAttribute($value)
    {
        return Helpers::reformatPhoneNumber($value);
    }

    public function getFullNameAttribute()
    {
        return ($this->name or $this->family) ? "{$this->name} {$this->family}" :
            Helpers::getPersianString($this->phone_number);
    }

    public function getCreatedAtDateAttribute()
    {
        return $this->created_at->toDateString();
    }

    public function findForPassport($username)
    {
        $username = Helpers::formatPhoneNumber($username);
        return User::where('phone_number', $username)->first();
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class)
            ->withPivot(['id', 'is_owner', 'state', 'expired_date', 'added_date', 'note'])
            ->withTimestamps();
    }

    public function ownedProjects()
    {
        return $this->belongsToMany(Project::class)
            ->where('is_owner', true)
            ->withPivot(['id', 'is_owner', 'state', 'expired_date', 'added_date', 'note'])
            ->withTimestamps();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'creator_user_id', 'id');
    }

    public function receives()
    {
        return $this->hasMany(Receive::class, 'creator_user_id', 'id');
    }

    public function setPhoneNumberAttribute($value)
    {
        return $this->attributes['phone_number'] = Helpers::formatPhoneNumber($value);
    }

    public function polls()
    {
        return $this->belongsToMany(Poll::class)
            ->withPivot(['state']);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function sentImages()
    {
        return $this->hasMany(SentImage::class);
    }

    public function stepByStep()
    {
        return $this->hasOne(StepByStep::class);
    }

    public function projectUser()
    {
        return $this->hasMany(ProjectUser::class, 'user_id', 'id');
    }

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function userStatus()
    {
        return $this->hasMany(UserStatus::class)->orderBy('end_date', 'DESC');
    }

    public function userStatusLog()
    {
        return $this->hasMany(UserStatusLog::class)->whereNotNull('transaction_id');
    }

    public function userStatusLogNull()
    {
        return $this->hasMany(UserStatusLog::class);
    }

    public function userStatuses()
    {
        return $this->hasMany(UserStatus::class)->orderBy('end_date', 'DESC');
    }

    public function banner()
    {
        return $this->hasMany(BannerUser::class, 'user_id', 'id');
    }

    public function getPremiumStateAttribute($value)
    {
        return Helpers::getUserStatus($this);
    }

    public function automationSms()
    {
        return $this->hasMany(AutomationSms::class, 'user_id', 'id');
    }

    public function automationCall()
    {
        return $this->hasMany(AutomationCall::class, 'user_id', 'id');
    }

    public function automationData()
    {
        return $this->hasOne(AutomationData::class, 'id', 'id');
    }

    public function automationBurn()
    {
        return $this->hasMany(AutomationBurntUser::class, 'user_id', 'id');
    }
}
