<?php

namespace App\Http\Livewire;

use App\Constants\PremiumDuration;
use App\Models\User;
use App\Models\UserStatus;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class PremiumPurchase extends Component
{
    public $readyToLoad = false;
    public User $user;
    public int $type;

    public ?UserStatus $current_plan = null;
    public array $durations = [];
    public string $start_date;
    public ?int $selected_plan;
    public Collection $plan_data;

    public function load()
    {
        $this->current_plan = $this->user->userStatuses
            ->where('start_date', '<=', now()->toDateTimeString())
            ->where('end_date', '>=', now()->toDateTimeString())
            ->first();
        $this->durations = PremiumDuration::asCustomArray();

        $response = Http::get(
            config('app.tankhah_url').'/panel/'.config('app.tankhah_token').'/premium/init',
            [
                'type' => $this->type,
                'user_id' => $this->user->id,
            ]
        );
        $this->plan_data = collect($response->json('data'));

        $this->readyToLoad = true;
    }

    public function render()
    {
        return view('livewire.premium-purchase');
    }
}
