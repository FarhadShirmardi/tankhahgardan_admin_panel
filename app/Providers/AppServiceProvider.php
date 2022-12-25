<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Tables\Columns\Column;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Filament::serving(function () {
            // Using Vite
            Filament::registerViteTheme([
                'resources/css/filament.css',
            ]);

            Filament::registerStyles([
                asset('css/fonts.css'),
            ]);
        });
    }
}
