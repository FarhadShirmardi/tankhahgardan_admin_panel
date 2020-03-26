<?php

namespace App\Jobs;

use App\Helpers\Helpers;
use App\Constants\UserActivationConstant;
use App\UserActivationState;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Exception;

class UserActivationProcessSecondStepInactiveJob implements ShouldQueue
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
     * @throws Exception
     */
    public function handle()
    {
        $userStates = UserActivationState::where(
            'state',
            UserActivationConstant::STATE_SECOND_SMS
        )->where(
            'updated_at',
            '<',
            app()->environment() == 'production' ?
                Carbon::now()->subHours(24)->toDateTimeString() :
                Carbon::now()->subMinutes(2)->toDateTimeString()
        )->get();

        Helpers::setUserStatus(
            $userStates,
            UserActivationConstant::STATE_SECOND_STEP_INACTIVE
        );
    }
}
