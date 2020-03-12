<?php

namespace App\Jobs;

use App\User;
use App\UserActivationLog;
use App\UserActivationState;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UserActivationSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    private $text;
    private $notifyType;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param $text
     * @param $notifyType
     */
    public function __construct(User &$user, $text, $notifyType)
    {
        $this->user = $user;
        $this->text = $text;
        $this->notifyType = $notifyType;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //todo: Send SMS

        //Log SMS
        $userActivationLog = new UserActivationLog();
        $userActivationLog->user_id = $this->user->id;
        $userActivationLog->notify_type = $this->notifyType;
        $userActivationLog->description = $this->text;
        $userActivationLog->save();

        //Update user activation state
        $userActivationState = UserActivationState::where('user_id', $this->user->id)->first();
        $userActivationState->state = $this->notifyType;
        $userActivationState->save();
    }
}
