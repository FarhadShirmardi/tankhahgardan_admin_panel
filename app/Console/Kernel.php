<?php

namespace App\Console;

use App\Jobs\UserActivationNPSSMSJob;
use App\Jobs\UserActivationProcessFirstStepDieJob;
use App\Jobs\UserActivationProcessFirstStepInactiveJob;
use App\Jobs\UserActivationProcessFirstStepSMSJob;
use App\Jobs\UserActivationProcessSecondStepDieJob;
use App\Jobs\UserActivationProcessSecondStepInactiveJob;
use App\Jobs\UserActivationProcessSecondStepSMSJob;
use App\Jobs\UserActivationProcessThirdStepDieJob;
use App\Jobs\UserActivationProcessThirdStepInactiveJob;
use App\Jobs\UserActivationProcessThirdStepSMSJob;
use App\Jobs\UserActivationReferralSMSJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        if (app()->environment() == 'production') {
            //First Step User Activation
            $schedule->job(new UserActivationProcessFirstStepSMSJob)
                ->dailyAt('00:00');
            $schedule->job(new UserActivationProcessFirstStepInactiveJob)
                ->dailyAt('00:30');
            $schedule->job(new UserActivationProcessFirstStepDieJob)
                ->dailyAt('01:00');

            //Second Step User Activation
            $schedule->job(new UserActivationProcessSecondStepSMSJob)
                ->dailyAt('01:30');
            $schedule->job(new UserActivationProcessSecondStepInactiveJob)
                ->dailyAt('02:00');
            $schedule->job(new UserActivationProcessSecondStepDieJob)
                ->dailyAt('2:30');

            //Third Step User Activation
            $schedule->job(new UserActivationProcessThirdStepSMSJob)
                ->dailyAt('03:00');
            $schedule->job(new UserActivationProcessThirdStepInactiveJob)
                ->dailyAt('03:30');
            $schedule->job(new UserActivationProcessThirdStepDieJob)
                ->dailyAt('04:00');

            //Last Step User Activation
            $schedule->job(new UserActivationNPSSMSJob)
                ->dailyAt('04:30');
            $schedule->job(new UserActivationReferralSMSJob)
                ->dailyAt('05:00');
        } else {
            //First Step User Activation
            $schedule->job(new UserActivationProcessFirstStepSMSJob)
                ->everyMinute();
            $schedule->job(new UserActivationProcessFirstStepInactiveJob)
                ->everyMinute();
            $schedule->job(new UserActivationProcessFirstStepDieJob)
                ->everyMinute();

            //Second Step User Activation
            $schedule->job(new UserActivationProcessSecondStepSMSJob)
                ->everyMinute();
            $schedule->job(new UserActivationProcessSecondStepInactiveJob)
                ->everyMinute();
            $schedule->job(new UserActivationProcessSecondStepDieJob)
                ->everyMinute();

            //Third Step User Activation
            $schedule->job(new UserActivationProcessThirdStepSMSJob)
                ->everyMinute();
            $schedule->job(new UserActivationProcessThirdStepInactiveJob)
                ->everyMinute();
            $schedule->job(new UserActivationProcessThirdStepDieJob)
                ->everyMinute();

            //Last Step User Activation
            $schedule->job(new UserActivationNPSSMSJob)
                ->everyMinute();
            $schedule->job(new UserActivationReferralSMSJob)
                ->everyMinute();
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
