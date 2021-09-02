<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\PanelFile;
use App\PanelUser;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function files()
    {
        /** @var PanelUser $user */
        $user = auth()->user();
        $files = $user->files()
            ->orWhere('user_id', 0)
            ->orderBy('date_time', 'desc')
            ->paginate();

        $files->transform(function ($item) {
            $item['state'] = Storage::disk('local')->exists($item->path);
            return $item;
        });

        return view('dashboard.file.files', [
            'files' => $files,
        ]);
    }

    public function downloadFile($id)
    {
        /** @var PanelUser $user */
        $user = auth()->user();
        $file = PanelFile::query()->whereIn('user_id', [0, $user->id])->findOrFail($id);

        return Storage::disk('local')->download($file->path);
    }
}
