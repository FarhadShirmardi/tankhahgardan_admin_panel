<?php

namespace App\Providers;


use App\Models\PanelUser;
use App\Models\Payment;
use App\Models\Receive;
use App\Models\TicketMessage;
use App\Models\User;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
    public function boot(): void
    {
        if (str(config('app.url'))->before('://')->is('https')) {
            URL::forceScheme('https');
        }
        Filament::serving(function () {
            // Using Vite
            Filament::registerViteTheme([
                'resources/css/filament.css',
            ]);

            Filament::registerStyles([
                asset('css/fonts.css'),
            ]);
        });

        Relation::enforceMorphMap([
            'ticketMessage' => TicketMessage::class,
            'user' => User::class,
            'panelUser' => PanelUser::class,
            'payment' => Payment::class,
            'receive' => Receive::class,
        ]);
    }
}
