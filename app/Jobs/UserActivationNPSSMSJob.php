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

class UserActivationNPSSMSJob implements ShouldQueue
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
        $userStates = UserActivationState::where(function ($q) {
            $q->where(
                'updated_at',
                '<',
                app()->environment() == 'production' ?
                    Carbon::now()->subHours(24)->toDateTimeString() :
                    Carbon::now()->subMinutes(2)->toDateTimeString()
            )->where(
                'state',
                UserActivationConstant::STATE_THIRD_SMS
            );
        })->orWhere(function ($q) {
            $q->where(
                'updated_at',
                '<',
                app()->environment() == 'production' ?
                    Carbon::now()->subHours(72)->toDateTimeString() :
                    Carbon::now()->subMinutes(7)->toDateTimeString()
            )->where(
                'state',
                UserActivationConstant::STATE_THIRD_CALL
            );
        })->orWhere(function ($q) {
            $q->where(
                'updated_at',
                '<',
                app()->environment() == 'production' ?
                    Carbon::now()->subMonth()->toDateTimeString() :
                    Carbon::now()->subMinutes(20)->toDateTimeString()
            )->where(
                'state',
                UserActivationConstant::STATE_ACTIVE_USER
            );
        })->get();

        Helpers::setUserStatus(
            $userStates,
            UserActivationConstant::STATE_NPS_SMS,
            null,
            true,
            UserActivationConstant::SMS_TEXT_NPS
        );
    }
}
