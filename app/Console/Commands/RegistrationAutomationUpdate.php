<?php

namespace App\Console\Commands;

use App\AutomationData;
use App\Payment;
use App\Receive;
use App\User;
use Illuminate\Console\Command;

class RegistrationAutomationUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'automation:update';

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

        $maxTimes = AutomationData::query()
            ->first([
                \DB::raw("max(registered_at) as max_reg_time"),
                \DB::raw("max(max_time) as max_time"),
            ])->toArray();

        $users = User::query()->where('verification_time', '>=', $maxTimes['max_reg_time'])->get();

        $data = collect();
        $bar = $this->output->createProgressBar($users->count());
        foreach ($users as $user) {
            $data->push([
                'id' => $user->id,
                'name' => $user->full_name,
                'phone_number' => $user->phone_number,
                'registered_at' => $user->verification_time,
                'transaction_count' => 0,
                'max_time' => null,
                'automation_state' => $user->state == 0 ? 0 : 1,
                'premium_state' => $user->premium_state,
            ]);
            $bar->advance();
        }
        $chunkedResults = $data->chunk(5000);
        foreach ($chunkedResults as $chunkedResult) {
            AutomationData::query()->insertOrIgnore($chunkedResult->toArray());
        }

        $beforeTime = now()->subMinutes(5)->toDateTimeString();

        $items = Payment::query()
            ->where('created_at', '>', $maxTimes['max_time'])
            ->where('created_at', '<=', $beforeTime)
            ->unionAll(
                Receive::query()
                    ->where('created_at', '>', $maxTimes['max_time'])
                    ->where('created_at', '<=', $beforeTime)
                    ->getQuery()
            )
            ->orderBy('created_at')
            ->get();

        $bar = $this->output->createProgressBar($items->count());
        foreach ($items as $item) {
            /** @var Payment $item */
            AutomationData::query()
                ->where('id', $item->creator_user_id)
                ->update([
                    'transaction_count' => \DB::raw('transaction_count + 1'),
                    'max_time' => $item->created_at,
                ]);
            $owner = $item->projectOwnerUser()->first();
            if ($owner and $item->creator_user_id != $owner->user_id) {
                AutomationData::query()
                    ->where('id', $owner->user_id)
                    ->update([
                        'transaction_count' => \DB::raw('transaction_count + 1'),
                        'max_time' => $item->created_at,
                    ]);
            }
            $bar->advance();
        }

        $this->info(now()->diff($start)->format('%i:%s.%f'));
    }
}
