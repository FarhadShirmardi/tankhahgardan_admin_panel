<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use App\Jobs\UserActivationProcessFirstStepDieJob;
use App\Jobs\UserActivationProcessFirstStepInactiveJob;
use App\Jobs\UserActivationProcessFirstStepSMSJob;
use App\Jobs\UserActivationProcessSecondStepDieJob;
use App\Jobs\UserActivationProcessSecondStepInactiveJob;
use App\Jobs\UserActivationProcessSecondStepSMSJob;
use App\Jobs\UserActivationProcessThirdStepDieJob;
use App\Jobs\UserActivationProcessThirdStepInactiveJob;
use App\Jobs\UserActivationProcessThirdStepSMSJob;
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
            $this->dispatch(new UserActivationProcessSecondStepSMSJob());
            $this->dispatch(new UserActivationProcessSecondStepInactiveJob());
            $this->dispatch(new UserActivationProcessSecondStepDieJob());
        } elseif ($step == 3) {
            $this->dispatch(new UserActivationProcessThirdStepSMSJob());
            $this->dispatch(new UserActivationProcessThirdStepInactiveJob());
            $this->dispatch(new UserActivationProcessThirdStepDieJob());
        }
    }
}
