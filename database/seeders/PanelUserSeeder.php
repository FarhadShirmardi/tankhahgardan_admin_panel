<?php

namespace Database\Seeders;

use App\Enums\PanelUserTypeEnum;
use App\Models\PanelUser;
use Hash;
use Illuminate\Database\Seeder;

class PanelUserSeeder extends Seeder
{
    public function run()
    {
        PanelUser::query()->updateOrInsert([
            'name' => 'مجتبی اسدی',
        ], [
            'phone_number' => '9122707923',
            'password' => Hash::make('pP12345678'),
            'type' => PanelUserTypeEnum::ADMIN,
        ]);
        PanelUser::query()->updateOrInsert([
            'name' => 'فرهاد شیرمردی',
        ], [
            'phone_number' => '9382204247',
            'password' => Hash::make('pP12345678'),
            'type' => PanelUserTypeEnum::ADMIN,
        ]);
    }
}