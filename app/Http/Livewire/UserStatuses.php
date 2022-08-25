<?php

namespace App\Http\Livewire;

use App\Http\Controllers\Dashboard\PremiumController;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class UserStatuses extends Component
{
    public $readyToLoad = false;
    public User $user;

    public function loadUserStatuses()
    {
        $this->readyToLoad = true;
    }

    public function render()
    {
        return view('livewire.user-statuses', [
            'user_statuses' => $this->readyToLoad ? $this->getUserStatuses() : collect(),
        ]);
    }

    public function getUserStatuses(): Collection
    {
        $premiumController = app(PremiumController::class);

        return $premiumController->getUserStatuses($this->user);
    }
}
