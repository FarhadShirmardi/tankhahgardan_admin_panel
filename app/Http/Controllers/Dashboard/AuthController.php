<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login()
    {
        return view('dashboard.login');
    }

    public function authenticate(Request $request)
    {
        //
    }
}
