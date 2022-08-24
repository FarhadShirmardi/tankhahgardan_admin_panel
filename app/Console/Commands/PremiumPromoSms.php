<?php

namespace App\Console\Commands;

use App\Exports\ReleaseSmsExport;
use App\Helpers\Helpers;
use App\Models\Campaign;
use App\Models\User;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class PremiumPromoSms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'premium:sms';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    private $file;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->file = collect();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function handle()
    {
        $phoneNumbers = collect();
        $file = fopen(storage_path('app/users.txt'), 'r');
        while (!feof($file)) {
            $phoneNumbers->push(
                trim(fgets($file))
            );
        }
        $users = User::query()->whereIn('phone_number', $phoneNumbers)->get();

        $bar = $this->output->createProgressBar($users->count());
        $campaign = Campaign::query()->firstOrCreate([
            'symbol' => 'ACTIVE_A',
        ], [
            'start_date' => now(),
            'end_date' => null,
            'count' => 0,
            'name' => 'هدیه به کاربران فعال',
        ]);
        foreach ($users as $user) {
            $receptor = $user->phone_number;

            $code = Helpers::generatePromoCode();
            $promoCode = $campaign->promoCodes()->create([
                'user_id' => $user->id,
                'max_count' => 1,
                'code' => $code,
                'discount_percent' => 30,
                'max_discount' => null,
                'start_at' => now()->toDateTimeString(),
                'expire_at' => '2021-04-24 23:59:59',
                'text' => 'هدیه اپلیکیشن',
            ]);

            $campaign->count = $campaign->promoCodes()->count();
            $campaign->save();

            $this->sendSms($receptor, $code);

            $bar->advance();
        }

        Excel::store((new ReleaseSmsExport($this->file)), 'user_sms.xlsx');

    }

    private function sendSms($receptor, $code)
    {
        $this->file->push([
            'phone_number' => $receptor,
            'code' => $code,
        ]);
    }
}
