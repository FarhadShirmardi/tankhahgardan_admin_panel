<?php

namespace App;

use App\Helpers\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql';

    protected $fillable = [
        'name',
        'state_id',
        'city_id'
    ];

    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] =
            Helpers::jalaliDateStringToGregorian($value);
    }

    public function getStartDateAttribute($value)
    {
        return Helpers::gregorianDateStringToJalali($value);
    }

    public function getPremiumStateAttribute($value)
    {
        return Helpers::getProjectStatus($this);
    }

    public function state()
    {
        return $this->hasOne(State::class, 'id', 'state_id');
    }

    public function city()
    {
        return $this->hasOne(City::class, 'id', 'city_id');
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function memos()
    {
        return $this->hasMany(Memo::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function sentPayments()
    {
        return $this->hasMany(SentPayment::class);
    }

    public function receives()
    {
        return $this->hasMany(Receive::class);
    }

    public function sentReceives()
    {
        return $this->hasMany(SentReceive::class);
    }

    public function imprests()
    {
        return $this->hasMany(Imprest::class);
    }

    public function sentImprests()
    {
        return $this->hasMany(SentImprest::Class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['id', 'is_owner', 'state', 'expired_date', 'added_date', 'note'])->withTimestamps();
    }

    public function accountingSoftware()
    {
        return $this->belongsTo(AccountingSoftware::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }

    public function projectUser()
    {
        return $this->hasMany(ProjectUser::class, 'project_id', 'id');
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function projectInviteNotification()
    {
        return $this->hasMany(ProjectInviteNotification::class, 'project_id', 'id');
    }

    public function getCurrencyTextAttribute($value)
    {
        return Currencies::getCurrency($this->currency)['symbol_fa'];
    }
}
