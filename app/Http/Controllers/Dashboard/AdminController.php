<?php

namespace App\Http\Controllers\Dashboard;

use App\Constants\PanelUserType;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\PanelUser;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public $permissions = [
        ['view_registration' => 'گزارش ثبت‌نام'],

        ['view_users_report' => 'نمایش گزارش کاربران',
            'export_users_report' => 'فایل خروجی گزارش کاربران',
            'refresh_users_report' => 'ساخت گزارش خروجی کاربران(رفرش)',],

        ['view_projects_report' => 'نمایش گزارش پروژه‌ها',
            'export_projects_report' => 'فایل خروجی گزارش پروژه‌ها',
            'refresh_projects_report' => 'ساخت گزارش خروجی پروژه‌ها(رفرش)',],

        ['view_feedback' => 'نمایش بازخورد',
            'response_feedback' => 'پاسخ دادن بازخورد',
            'new_feedback' => 'ثبت بازخورد جدید',],

        ['view_notification' => 'نمایش اعلان',
            'new_notification' => 'ثبت اعلان جدید',],

        ['view_banner' => 'نمایش بنر',
            'edit_banner' => 'ثبت بنر جدید',],

        ['view_promo_code' => 'نمایش کد تخفیف',
            'edit_promo_code' => 'ثبت کد تخفیف جدید',],

        ['view_transactions' => 'نمایش تراکنش',
            'edit_transactions' => 'ثبت تراکنش جدید',],

        ['edit_user_panels' => 'ویرایش کاربران پنل',],

        ['view_automation' => 'نمایش اطلاعات اتوماسیون',
            'edit_automation' => 'ثبت زنگ و تغییر استیت کاربران'],
    ];

    public function userList()
    {
        $users = PanelUser::where('id', '<>', auth()->id())->get();
        return view('dashboard.admin.user_list', [
            'users' => $users,
        ]);
    }

    public function userItem($id)
    {
        $user = PanelUser::query()->firstWhere([
            'id' => $id,
        ]);
        return view('dashboard.admin.user_item', [
            'user' => $user,
            'permissions' => $this->permissions,
            'user_permissions' => $user->permissions()->pluck('name')->toArray(),
        ]);
    }

    public function userItemUpdate(Request $request, $id)
    {
        $request->merge([
            'phone_number' => Helpers::formatPhoneNumber($request->phone_number),
            'type' => PanelUserType::SECRETARY,
        ]);
        if (!$id) {
            $request->merge([
                'password' => \Hash::make($request->password),
            ]);
        }
        $user = PanelUser::query()->updateOrCreate([
            'id' => $id,
        ], $request->all());

        $user->syncPermissions($request->permission_checkbox);

        return redirect()->route('dashboard.admin.user_list')->with('success', 'با موفقیت انجام شد.');
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
