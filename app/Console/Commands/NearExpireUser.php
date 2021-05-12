<?php

namespace App\Console\Commands;

use App\Constants\UserPremiumState;
use App\User;
use App\UserStatus;
use Illuminate\Console\Command;
use Kavenegar;
use Log;

class NearExpireUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:nearExpire {--silent}';

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
        $this->info('start sending sms');

        $userStatuses = UserStatus::query()
            ->where('end_date', '>=', now()->toDateTimeString())
            ->where('end_date', '<=', now()->addDay()->toDateTimeString())
            ->get();
        /** @var UserStatus $userStatus */
        foreach ($userStatuses as $userStatus) {
            /** @var User $user */
            $user = $userStatus->user()->first();
            if ($user->premium_state != UserPremiumState::NEAR_ENDING_PREMIUM) {
                continue;
            }
            $this->info("send sms to {$user->phone_number}");
            if (!$this->option('silent')) {
                $receptor = $user->phone_number;
                $token1 = str_replace('‌', '_', str_replace(' ', '_', $project->name));
                $token2 = 'https://web.tankhahgardan.com/user-account-setting';
                $token3 = '';
                $type = "sms";//sms | call
                $result = Kavenegar::VerifyLookup($receptor, $token1, $token2, $token3, 'Mapsa-ChargNotif3', $type);
                if ($result) {
                    $message = $result[0]->statustext;
                    Log::info('Send to kavenegar   ====>   ' . $receptor . '  ' . $message);
                }
            }
        }

        $this->info(PHP_EOL . 'end of sending sms');
    }
}
