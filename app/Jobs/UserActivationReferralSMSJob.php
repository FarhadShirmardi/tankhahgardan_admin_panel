<?php

namespace App\Jobs;

use App\Helpers\Helpers;
use App\Http\Controllers\Api\V1\Constants\UserActivationConstant;
use App\UserActivationState;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UserActivationReferralSMSJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        $userStates = UserActivationState::where(
            'updated_at',
            '<',
            Carbon::now()->subWeek()->toDateTimeString()
        )->where(
            'state',
            UserActivationConstant::STATE_NPS_SMS
        )->get();

        Helpers::setUserStatus(
            $userStates,
            UserActivationConstant::STATE_REFERRAL_SMS,
            null,
            true,
            UserActivationConstant::SMS_TEXT_REFERRAL
        );
    }
}
