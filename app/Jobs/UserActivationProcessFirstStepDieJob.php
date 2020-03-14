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
     */
    public function handle()
    {
        $userStates = UserActivationState::where(
            'state',
            UserActivationConstant::STATE_FIRST_CALL
        )->where(
            'updated_at',
            '<',
            Carbon::now()->subHours(48)
        )->get();

        foreach ($userStates as $userState) {
            $user = User::findOrFail($userState->user_id);
            $projects = $user->projects()->get();

            $dataCounter = 0;
            /** @var Project $project */
            foreach ($projects as $project) {
                $dataCounter += $project->notes()->count();
                $dataCounter += $project->payments()->count();
                $dataCounter += $project->receives()->count();
            }

            if ($dataCounter  == 0) {
                //Update user activation state
                $userActivationState = UserActivationState::where(
                    'user_id',
                    $user->id
                )->first();
                $userActivationState->state = UserActivationConstant::STATE_FIRST_ATTEMPT_DIE;
                $userActivationState->save();
            }
        }
    }
}
