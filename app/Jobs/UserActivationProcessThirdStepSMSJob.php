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

class UserActivationProcessThirdStepSMSJob implements ShouldQueue
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
        $time = Carbon::now()->subMonth()->toDateTimeString();
        $userStates = UserActivationState::where(function ($q) use ($time) {
            $q->where(
                'updated_at',
                '<',
                $time
            )->where(
                'state',
                UserActivationConstant::STATE_SECOND_SMS
            );
        })->orWhere(function ($q) use ($time) {
            $q->where(
                'updated_at',
                '<',
                $time
            )->where(
                'state',
                UserActivationConstant::STATE_SECOND_CALL
            );
        })->get();

        Helpers::setUserStatus(
            $userStates,
            UserActivationConstant::STATE_THIRD_SMS,
            null,
            true,
            UserActivationConstant::SMS_TEXT_THIRD
        );
    }
}
