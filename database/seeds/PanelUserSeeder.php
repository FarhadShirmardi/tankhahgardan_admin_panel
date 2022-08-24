<?php

use App\Constants\PanelUserType;
use App\Models\PanelUser;
use Illuminate\Database\Seeder;

class PanelUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PanelUser::query()->updateOrInsert([
            'name' => 'مجتبی اسدی',
        ], [
            'phone_number' => '9122707923',
            'password' => Hash::make('pP12345678'),
            'type' => PanelUserType::ADMIN,
        ]);
        PanelUser::query()->updateOrInsert([
            'name' => 'فرهاد شیرمردی',
        ], [
            'phone_number' => '9382204247',
            'password' => Hash::make('pP12345678'),
            'type' => PanelUserType::ADMIN,
        ]);
    }
}
