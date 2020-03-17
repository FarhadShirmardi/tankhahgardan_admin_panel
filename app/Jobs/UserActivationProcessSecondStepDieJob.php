<?php

namespace App\Jobs;

use App\Helpers\Helpers;
use App\Http\Controllers\Api\V1\Constants\UserActivationConstant;
use App\Project;
use App\User;
use App\UserActivationLog;
use App\UserActivationState;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UserActivationProcessSecondStepDieJob implements ShouldQueue
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
            'state',
            UserActivationConstant::STATE_SECOND_CALL
        )->where(
            'updated_at',
            '<',
            app()->environment() == 'production' ?
                Carbon::now()->subHours(72)->toDateTimeString() :
                Carbon::now()->subMinutes(7)->toDateTimeString()
        )->get();

        Helpers::setUserStatus(
            $userStates,
            UserActivationConstant::STATE_SECOND_ATTEMPT_DIE,
            null,
            true,
            UserActivationConstant::SMS_TEXT_FIRST_POLL
        );
    }
}
