<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\PanelUser;
use App\User;
use Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login()
    {
        if (!auth()->guest()) {
            return redirect(route('dashboard.home'));
        }
        return view('dashboard.login');
    }

    public function authenticate(Request $request)
    {
        $phoneNumber = Helpers::formatPhoneNumber($request->phone_number);
        $password = $request->input('password');
        if (Auth::guard('web')->attempt([
            'phone_number' => $phoneNumber,
            'password' => $password
        ])) {
            return redirect(route('dashboard.home'));
        }
        else {
            return redirect(route('dashboard.login'))->with('message', trans('auth.failed'));
        }
    }
}
