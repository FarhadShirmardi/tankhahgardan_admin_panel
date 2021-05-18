<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\PanelUser;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function files()
    {
        /** @var PanelUser $user */
        $user = auth()->user();
        $files = $user->files()
            ->orderBy('date_time', 'desc')
            ->paginate();

        return view('dashboard.file.files', [
            'files' => $files,
        ]);
    }

    public function downloadFile($id)
    {
        /** @var PanelUser $user */
        $user = auth()->user();
        $file = $user->files()->findOrFail($id);

        return Storage::disk('local')->download($file->path);
    }
}
