<?php

namespace App\Jobs;

use App\Constants\NotificationType;
use App\Models\Project;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Kavenegar;
use Log;

class FeedbackResponseSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var User
     */
    private $user;
    /**
     * @var Project
     */
    private $project;
    private $type;


    /**
     * Create a new job instance.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $receptor = $this->user->phone_number;
        $token1 = 'کاربر';
        $token2 = '';
        $token3 = '';
        $template = 'tankhah-feedback';
        $type = "sms";//sms | call
        $result = Kavenegar::VerifyLookup($receptor, $token1, $token2, $token3, $template, $type);
        if ($result) {
            $message = $result[0]->statustext;
            Log::info('Send to kavenegar   ====>   ' . $receptor . '  ' . $message);
        }
        dispatch(
            (new SendFirebaseNotificationJob([
                'type' => NotificationType::FEEDBACK_RESPONSE,
                'receiver_id' => $this->user->id,
            ]))->onQueue('activationSms')
        );
    }
}
