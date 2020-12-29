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

class UserActivationProcessSecondStepSMSJob implements ShouldQueue
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
        $time = app()->environment() == 'production' ?
            Carbon::now()->subWeek()->toDateTimeString() :
            Carbon::now()->subHours(48)->toDateTimeString();
        $userStates = UserActivationState::where(function ($q) use ($time) {
           $q->where(
               'updated_at',
               '<',
               $time
           )->where(
               'state',
               UserActivationConstant::STATE_FIRST_SMS
           );
        })->orWhere(function ($q) use ($time) {
            $q->where(
                'updated_at',
                '<',
                $time
            )->where(
                'state',
                UserActivationConstant::STATE_FIRST_CALL
            );
        })->orWhere(function ($q) use ($time) {
            $q->where(
                'updated_at',
                '<',
                $time
            )->where(
                'updated_at',
                '>',
                app()->environment() == 'production' ?
                    Carbon::now()->subWeek()->subDay()->toDateTimeString() :
                    Carbon::now()->subHours(48)->toDateTimeString()
            )->where(
                'state',
                UserActivationConstant::STATE_ACTIVE_USER
            );
        })->get();

        Helpers::setUserStatus(
            $userStates,
            UserActivationConstant::STATE_SECOND_SMS,
            null,
            true,
            UserActivationConstant::SMS_TEXT_SECOND
        );
    }
}
