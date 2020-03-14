<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use App\Jobs\UserActivationProcessFirstStepJob;
use Illuminate\Http\Request;

class UserActivationController extends Controller
{
    public function UserActivationDispatcher($step)
    {
        if ($step == 1) {
            $this->dispatch(new UserActivationProcessFirstStepJob());
        } elseif ($step == 2) {
            //
        } elseif ($step == 3) {
            //
        }
    }
}
