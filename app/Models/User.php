<?php

namespace App\Models;

use App\Enums\ProjectUserTypeEnum;
use App\Enums\UserStateEnum;
use App\Enums\UserStatusTypeEnum;
use App\Services\UserReportService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $connection = 'mysql';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'state' => UserStateEnum::class,
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

    /** @return Attribute<string, never> */
    protected function formattedUsername(): Attribute
    {
        $phoneNumber = reformatPhoneNumber($this->phone_number);
        return Attribute::make(
            get: fn () => $this->username . " ($phoneNumber)"
        );
    }

    public function projectUsers(): HasMany
    {
        return $this->hasMany(ProjectUser::class, 'user_id', 'id');
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class)
            ->as('projectUser')
            ->using(ProjectUser::class)
            ->whereNull('project_user.deleted_at')
            ->withPivot(['id', 'user_type', 'state'])
            ->withTimestamps();
    }

    public function ownedProjects(): BelongsToMany
    {
        return $this->projects()->wherePivot('user_type', ProjectUserTypeEnum::OWNER);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
    public function userStatuses(): HasMany
    {
        return $this->hasMany(UserStatus::class)->latest('end_date');
    }

    public function userStatusLogs(): HasMany
    {
        return $this->hasMany(UserStatusLog::class);
    }

    /** @return Attribute<?UserStatus, never> */
    protected function currentUserStatus(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->userStatuses
                ->where('start_date', '<=', now()->toDateTimeString())
                ->firstWhere('end_date', '>=', now()->toDateTimeString())
        );
    }

    public function userReport(): HasOne
    {
        return $this->hasOne(UserReport::class, 'id');
    }

    public function updateUserReport(): void
    {
        $this->userReport()->update(UserReportService::getSingleUser($this->id)->toArray());
        UserReportService::updateImageCount($this->id);
    }

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /** @return Attribute<int, never> */
    protected function walletAmount(): Attribute
    {
        $usedWalletQuery = (int) (UserStatusLog::query()
            ->without('transaction')
            ->where('status', UserStatusTypeEnum::SUCCEED)
            ->where('user_id', $this->id)
            ->selectRaw('sum(wallet_amount) as wallet')
            ->value('wallet'));

        return Attribute::make(
            get: fn () => $this->wallet - $this->reserve_wallet - $usedWalletQuery
        );
    }
}
