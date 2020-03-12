<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use App\Jobs\UserActivationProcessFirstStepJob;
use Illuminate\Http\Request;

class UserActivationController extends Controller
{
    public function UserActivation24HDispatcher()
    {
        $this->dispatch(new UserActivationProcessFirstStepJob());
    }
}
