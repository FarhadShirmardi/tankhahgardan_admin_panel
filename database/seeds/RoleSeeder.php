<?php

use App\Constants\PanelUserType;
use App\PanelUser;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminName = 'Admin';
        $marketingName = 'Marketing';
        $secretaryName = 'Secretary';
        $adminRole = Role::firstOrCreate(['name' => $adminName]);
        $marketingRole = Role::firstOrCreate(['name' => $marketingName]);
        $secretaryRole = Role::firstOrCreate(['name' => $secretaryName]);

        $timeSeparationPermission = Permission::firstOrCreate(['name' => 'time_separation']);
        $timeSeparationPermission
            ->assignRole($adminName)
            ->assignRole($marketingName);
        $daySeparationPermission = Permission::firstOrCreate(['name' => 'day_separation']);
        $daySeparationPermission
            ->assignRole($adminName)
            ->assignRole($marketingName);
        $rangeSeparationPermission = Permission::firstOrCreate(['name' => 'range_separation']);
        $rangeSeparationPermission
            ->assignRole($adminName)
            ->assignRole($marketingName);
        $allUserActivityFullPermission = Permission::firstOrCreate(['name' => 'all_user_activity_full']);
        $allProjectActivityFullPermission = Permission::firstOrCreate(['name' => 'all_project_activity_full']);
        $allUserActivityPermission = Permission::firstOrCreate(['name' => 'all_user_activity']);
        $allUserActivityFullPermission->assignRole($adminName);
        $allProjectActivityFullPermission->assignRole($adminName);
        $allUserActivityPermission->assignRole($marketingName);
        $projectActivityPermission = Permission::firstOrCreate(['name' => 'project_activity']);
        $projectActivityPermission
            ->assignRole($adminName)
            ->assignRole($marketingName)
            ->assignRole($secretaryName);
        $userActivityPermission = Permission::firstOrCreate(['name' => 'user_activity']);
        $userActivityPermission
            ->assignRole($adminName)
            ->assignRole($marketingName)
            ->assignRole($secretaryName);
        $allProjectActivityPermission = Permission::firstOrCreate(['name' => 'all_project_activity']);
        $allProjectActivityPermission
            ->assignRole($adminName)
            ->assignRole($marketingName);
        $viewFeedbackPermission = Permission::firstOrCreate(['name' => 'view_feedback']);
        $viewFeedbackPermission
            ->assignRole($adminName)
            ->assignRole($marketingName)
            ->assignRole($secretaryName);
        $responseFeedbackPermission = Permission::firstOrCreate(['name' => 'response_feedback']);
        $responseFeedbackPermission->assignRole($adminName)->assignRole($secretaryName);

        $viewNotificationPermission = Permission::firstOrCreate(['name' => 'view_notifications']);
        $viewNotificationPermission
            ->assignRole($adminName)
            ->assignRole($marketingName);
        $addNotificationPermission = Permission::firstOrCreate(['name' => 'add_notification']);
        $addNotificationPermission->assignRole($adminName)->assignRole($marketingName);
        $deleteNotificationPermission = Permission::firstOrCreate(['name' => 'delete_notification']);
        $deleteNotificationPermission->assignRole($adminName)->assignRole($marketingName);

        $viewPromoCodesPermission = Permission::firstOrCreate(['name' => 'view_promo_codes']);
        $viewPromoCodesPermission->assignRole($adminName)->assignRole($marketingName)->assignRole($secretaryName);

        $viewTransactions = Permission::firstOrCreate(['name' => 'view_transactions']);
        $viewTransactions->assignRole($adminName)->assignRole($marketingName)->assignRole($secretaryName);

        $adminUsers = PanelUser::where('type', PanelUserType::ADMIN)->get();
        foreach ($adminUsers as $adminUser) {
            $adminUser->syncRoles([]);
            $adminUser->assignRole($adminRole);
        }

        $marketingUsers = PanelUser::where('type', PanelUserType::MARKETING)->get();
        foreach ($marketingUsers as $marketingUser) {
            $marketingUser->syncRoles([]);
            $marketingUser->assignRole($marketingRole);
        }

        $secretaryUsers = PanelUser::where('type', PanelUserType::SECRETARY)->get();
        foreach ($secretaryUsers as $secretaryUser) {
            $secretaryUser->syncRoles([]);
            $secretaryUser->assignRole($secretaryRole);
        }
    }
}
