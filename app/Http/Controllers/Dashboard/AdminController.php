<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\PanelUser;
use Illuminate\Http\Request;
use App\Helpers\Helpers;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function userList()
    {
        $users = PanelUser::where('id', '<>', auth()->id())->get();
        return view('dashboard.admin.user_list', [
            'users' => $users
        ]);
    }

    public function userItem($id = null)
    {
        $user = PanelUser::query()->firstWhere([
            'id' => $id
        ]);
        return view('dashboard.admin.user_item', [
            'user' => $user,
            'roles' => Role::query()->orderByDesc('id')->get()
        ]);
    }

    public function userItemUpdate(Request $request, $id = null)
    {
        $request->merge([
            'phone_number' => Helpers::formatPhoneNumber($request->phone_number)
        ]);
        if (!$id) {
            $request->merge([
                'password' => \Hash::make($request->password)
            ]);
        }
        $user = PanelUser::query()->updateOrCreate([
            'id' => $id
        ], $request->all());

        \Artisan::call('db:seed', [
            '--class' => 'RoleSeeder'
        ]);

        return redirect()->back()->with('success', 'با موفقیت انجام شد.');
    }

    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);
        $user = PanelUser::query()->findOrFail($id);
        $user->password = \Hash::make($request->password);
        $user->save();
        return redirect()->back()->with('success', 'با موفقیت انجام شد.');
    }
}
