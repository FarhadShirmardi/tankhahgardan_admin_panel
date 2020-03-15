<?php

namespace App\Jobs;

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

class UserActivationProcessFirstStepSMSJob implements ShouldQueue
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
     */
    public function handle()
    {
        $users = User::with('projects')->select([
            'users.id',
            'users.name',
            'users.family',
            'users.phone_number',
            'users.created_at',
            'panel_user_activation_states.id as user_state_id',
        ])->leftJoin(
            'panel_user_activation_states',
            'users.id',
            '=',
            'panel_user_activation_states.user_id'
        )->where(
            'users.created_at',
            '>',
            env('USER_ACTIVATION_START_DATE')
        )->where(
            'users.created_at',
            '<',
            Carbon::now()->subHours(24)->toDateTimeString()
        )->where(
            'users.state',
            '1'
        )->whereNull(
            'panel_user_activation_states.id'
        )->orderByDesc('users.created_at')
            ->get();

        \Log::debug("User counts = {$users->count()}");

        foreach ($users as $user) {
            $projects = $user->projects()->get();

            $dataCounter = 0;

            /** @var Project $project */
            foreach ($projects as $project) {
                $dataCounter += $project->notes()->count();
                $dataCounter += $project->payments()->count();
                $dataCounter += $project->receives()->count();
            }

            //Create user activation state
            $userActivationState = new UserActivationState();
            $userActivationState->user_id = $user->id;
            $userActivationState->save();

            if ($dataCounter  == 0) {
                if (app()->environment() != 'production') {
                    $delayTime = now()->addMinutes(5);
                } else {
                    $delayTime = now()->addHours(12);
                }

                \Log::debug("Sending sms to user->id = {$user->id}");
                dispatch(new UserActivationSmsJob(
                    $user,
                    UserActivationConstant::SMS_TEXT_FIRST,
                    UserActivationConstant::STATE_FIRST_SMS
                ))->delay($delayTime);

            } else {
                \Log::debug("User was active in 24 hour => {$user->id}");

                //Update user activation state
                $userActivationState->state = UserActivationConstant::STATE_ACTIVE_USER;
                $userActivationState->save();
            }
        }
    }
}
