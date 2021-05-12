<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\PanelUser;

class FileController extends Controller
{
    public function files()
    {
        /** @var PanelUser $user */
        $user = auth()->user();
        $files = $user->files()->get();

        dd($files);
    }
}
