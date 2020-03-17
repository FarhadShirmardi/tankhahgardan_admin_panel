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

class UserActivationProcessFirstStepDieJob implements ShouldQueue
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
            UserActivationConstant::STATE_FIRST_CALL
        )->where(
            'updated_at',
            '<',
            app()->environment() == 'production' ?
                Carbon::now()->subHours(48)->toDateTimeString() :
                Carbon::now()->subMinutes(4)->toDateTimeString()
        )->get();

        Helpers::setUserStatus($userStates, UserActivationConstant::STATE_FIRST_ATTEMPT_DIE);
    }
}
