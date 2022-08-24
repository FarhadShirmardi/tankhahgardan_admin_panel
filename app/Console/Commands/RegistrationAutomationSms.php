<?php

namespace App\Console\Commands;

use App\Jobs\AutomationSmsJob;
use App\Models\AutomationData;
use App\Models\User;
use Illuminate\Console\Command;

class RegistrationAutomationSms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'automation:sms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $start = now();
        $this->info('start');

        foreach (range(-7, 27) as $type) {
            $users = $this->filterByType($type);
            foreach ($users as $user) {
                dispatch(
                    new AutomationSmsJob(
                        $user,
                        $type
                    )
                )->onConnection('sync')->onQueue('activationSms');
            }
        }

        $this->info(now()->diff($start)->format('%i:%s.%f'));
    }

    private function filterByType($type)
    {
        return User::query()->whereIn('id', AutomationData::query()
            ->where('automation_state', $type)
            ->whereNotExists(function ($query) use ($type) {
                $query->select(\DB::raw(1))
                    ->from('automation_sms')
                    ->whereColumn('user_id', 'automation_data.id')
                    ->where('type', $type);
            })
            ->pluck('id')
            ->toArray())->get();
    }
}
