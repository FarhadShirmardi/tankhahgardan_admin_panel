<?php

use App\Constants\PanelUserType;
use App\PanelUser;
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
            'name' => 'رامین ایمان زاده',
        ], [
            'phone_number' => '9126860552',
            'password' => Hash::make('pP12345678'),
            'type' => PanelUserType::MARKETING,
        ]);
        PanelUser::query()->updateOrInsert([
            'name' => 'امیر ارسلان فرهادکوهی',
        ], [
            'phone_number' => '9126184456',
            'password' => Hash::make('pP12345678'),
            'type' => PanelUserType::MARKETING,
        ]);
        PanelUser::query()->updateOrInsert([
            'name' => 'عاطفه ترکمان',
        ], [
            'phone_number' => '9129639671',
            'password' => Hash::make('pP12345678'),
            'type' => PanelUserType::SECRETARY,
        ]);
        PanelUser::query()->updateOrInsert([
            'name' => 'مجید اوسطی',
        ], [
            'phone_number' => '9353972566',
            'password' => Hash::make('pP12345678'),
            'type' => PanelUserType::MARKETING,
        ]);
        PanelUser::query()->updateOrInsert([
            'name' => 'فرهاد شیرمردی',
        ], [
            'phone_number' => '9382204247',
            'password' => Hash::make('pP12345678'),
            'type' => PanelUserType::MARKETING,
        ]);
        PanelUser::query()->updateOrInsert([
            'name' => 'امیرحسین پیری',
        ], [
            'phone_number' => '9381745176',
            'password' => Hash::make('pP12345678'),
            'type' => PanelUserType::MARKETING,
        ]);
        PanelUser::query()->updateOrInsert([
            'name' => 'امین خان‌زاده',
        ], [
            'phone_number' => '9369407100',
            'password' => Hash::make('pP12345678'),
            'type' => PanelUserType::SECRETARY,
        ]);
    }
}
