<?php

use App\Constants\PanelUserType;
use App\Http\Controllers\Dashboard\AdminController;
use App\Models\PanelUser;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminController = app()->make(AdminController::class);
        $permissions = collect();
        foreach ($adminController->permissions as $permissionGroup) {
            foreach ($permissionGroup as $permission => $key) {
                Permission::firstOrCreate(['name' => $permission]);
                $permissions->push($permission);
            }
        }

        $adminUsers = PanelUser::where('type', PanelUserType::ADMIN)->get();
        foreach ($adminUsers as $adminUser) {
            $adminUser->syncPermissions($permissions);
        }
    }
}
