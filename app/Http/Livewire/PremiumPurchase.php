<?php

namespace App\Http\Livewire;

use App\Constants\PremiumDuration;
use App\Models\User;
use App\Models\UserStatus;
use Livewire\Component;

class PremiumPurchase extends Component
{
    public $readyToLoad = false;
    public User $user;
    public int $type;

    public ?UserStatus $current_plan = null;
    public array $durations = [];
    public string $start_date;

    public function load()
    {
        $this->current_plan = $this->user->userStatuses
            ->
            $this->durations = PremiumDuration::asCustomArray();

        $this->readyToLoad = true;
    }

    public function render()
    {
        return view('livewire.premium-purchase');
    }
}
