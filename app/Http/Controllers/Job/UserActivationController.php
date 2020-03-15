<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use App\Jobs\UserActivationProcessFirstStepDieJob;
use App\Jobs\UserActivationProcessFirstStepInactiveJob;
use App\Jobs\UserActivationProcessFirstStepSMSJob;
use App\Jobs\UserActivationProcessSecondStepSMS;
use Illuminate\Http\Request;

class UserActivationController extends Controller
{
    public function UserActivationDispatcher($step)
    {
        if ($step == 1) {
            $this->dispatch(new UserActivationProcessFirstStepSMSJob());
            $this->dispatch(new UserActivationProcessFirstStepInactiveJob());
            $this->dispatch(new UserActivationProcessFirstStepDieJob());
        } elseif ($step == 2) {
            $this->dispatch(new UserActivationProcessSecondStepSMS());
        } elseif ($step == 3) {
            //
        }
    }
}
