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

    /**1120357122:AAH_xc5qknc741bgGtxi2dQ48ecvTXJ72KE
     * Execute the job.
     *
     * @return void
     * @throws Exception
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
                Carbon::now()->subHours(24)->toDateTimeString()
        )->get();

        Helpers::setUserStatus($userStates, UserActivationConstant::STATE_FIRST_ATTEMPT_DIE);
    }
}
