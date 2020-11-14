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
use Telegram\Bot\Laravel\Facades\Telegram;

class UserActivationSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var User $user */
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
        if (app()->environment() == 'production') {
            //todo: Send SMS
        } else {
            $text = 'کاربر: ' . $this->user->phone_number;
            $text .= 'پیام: ' . $this->text;
            Telegram::sendMessage([
                'chat_id' => '@tankhahBackLog',
                'parse_mode' => 'HTML',
                'text' => $text,
            ]);
        }

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
