<?php

namespace Database\Seeders;

use App\Enums\PanelUserTypeEnum;
use App\Enums\PermissionEnum;
use App\Models\PanelUser;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = collect();
        foreach (PermissionEnum::cases() as $permissionEnum) {
            Permission::firstOrCreate([
                'name' => $permissionEnum->value
            ], [
                'title' => $permissionEnum->getTitle()
            ]);
            $permissions->push($permissionEnum->value);
        }

        $adminUsers = PanelUser::where('type', PanelUserTypeEnum::ADMIN)->get();
        foreach ($adminUsers as $adminUser) {
            $adminUser->syncPermissions($permissions);
        }
    }
}
