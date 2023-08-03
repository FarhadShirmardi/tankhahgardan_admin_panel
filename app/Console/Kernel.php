<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('generate:user-report')
            ->dailyAt('05:17');
        $schedule->command('generate:project-report')
            ->dailyAt('05:27');

        $schedule->command('generate:new-user-report')
            ->everyFiveMinutes();
        $schedule->command('generate:new-project-report')
            ->everyFiveMinutes();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
