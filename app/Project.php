<?php

namespace App;

use App\Project as Projects;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Project
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Projects whereCreatedAt($value)
 * @method static Builder|Projects whereId($value)
 * @method static Builder|Projects whereUpdatedAt($value)
 * @mixin Eloquent
 * @property string $name
 * @property string $description
 * @property int $user_id
 * @property string|null $deleted_at
 * @method static Builder|Projects whereDeletedAt($value)
 * @method static Builder|Projects whereDescription($value)
 * @method static Builder|Projects whereName($value)
 * @method static Builder|Projects whereUserId($value)
 * @property-read Collection|AccountTitle[] $accountTitles
 * @property-read User $user
 * @property-read Collection|Imprest[] $imprests
 * @property-read Collection|User[] $users
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|Projects onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|Projects withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Projects withoutTrashed()
 * @property int|null $accounting_software_id
 * @method static Builder|Projects whereAccountingSoftwareId($value)
 * @property-read AccountingSoftware|null $accountingSoftware
 * @property-read Collection|Note[] $notes
 * @property-read Collection|Payment[] $payments
 * @property-read Collection|Receive[] $receives
 */
class Project extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'state_id',
        'city_id'
    ];

    public function accountTitles()
    {
        return $this->hasMany(AccountTitle::class);
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

    public function state()
    {
        return $this->hasOne(State::class, 'id', 'state_id');
    }

    public function city()
    {
        return $this->hasOne(City::class, 'id', 'city_id');
    }

    public function accountingCodes()
    {
        return $this->morphMany(
            AccountingCode::class,
            'hasAccountingCode',
            'model_type',
            'model_id',
            $this->pivot->id
        );
    }

    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }

    public function projectUser()
    {
        return $this->hasMany(ProjectUser::class, 'project_id', 'id');
    }
}
