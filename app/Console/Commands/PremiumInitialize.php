<?php

namespace App\Console\Commands;

use App\Constants\PremiumConstants;
use App\Constants\PremiumDuration;
use App\Constants\PremiumPrices;
use App\Constants\ProjectUserState;
use App\Constants\PurchaseType;
use App\Constants\UserStatusType;
use App\Exports\ReleaseSmsExport;
use App\Helpers\Helpers;
use App\Models\Campaign;
use App\Models\ProjectReport;
use App\Models\ProjectUser;
use App\Models\User;
use App\Models\UserReport;
use App\ProjectStatus;
use DB;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Kavenegar;
use Maatwebsite\Excel\Facades\Excel;

class PremiumInitialize extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'premium:init';
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
     */
    public function handle()
    {
        $this->call('generate:report', ['--project']);

//        DB::beginTransaction();

        /** @var Collection $activeUserList */
        $activeUserList = collect();
        $projects = ProjectReport::query()->where('project_type', '<>', 4)->limit(50)->pluck('id')->toArray();
        $totalProjectCount = count($projects);

        foreach ($projects as $key => $projectId) {
            $this->output->write("<info>" . ($key + 1) . '/' . $totalProjectCount . "</info>");

            /** @var User $owner */
            $owner = ProjectUser::query()
                ->where('project_id', $projectId)
                ->where('is_owner', true)
                ->where('state', ProjectUserState::ACTIVE)
                ->first()->user()->first();

            $userCounts = Helpers::getUserCounts($owner);

            $userCountMin = max($userCounts['user_count_limit'] - $userCounts['user_count_remain'], 1);
            $prices = PremiumPrices::getPrices($userCountMin);
            $price = array_shift($prices);
            $userPrice = $price['user_price'];
            $userCount = array_shift($userPrice)['value'];

            $this->output->write(" | user count : " . $userCount);

            if (!$activeUserList->contains($owner->id)) {
                $this->createUser($owner->id, $userCount);
            }

            $activeUserList->push($owner->id);
            $this->output->write(" => FINISHED!", true);
        }

        $activeUserList = $activeUserList->unique()->toArray();

        $this->info('');
        $this->info('');
        $this->info('======================================================================');
        $this->info('Sending SMS');

        $activeUsers = User::query()
            ->where('state', 1)
            ->whereIn('id', $activeUserList)
            ->get();
        $this->info('active User count : ' . count($activeUsers));

        $this->call('generate:report', ['--user']);
        $inactiveUserIds =  UserReport::query()
            ->whereRaw('payment_count + receive_count >= 10')
            ->whereNotIn('id', $activeUserList)
            ->pluck('id')->toArray();
        $inactiveUsers = User::query()
            ->where('state', 1)
            ->whereIn('id', $inactiveUserIds)
            ->get();
        $this->info('inactive User count : ' . count($inactiveUsers));

        $this->info('======================================================================');
        $this->info('Sending active sms');
        $bar = $this->output->createProgressBar(count($activeUsers));
        foreach ($activeUsers as $activeUser) {
            $receptor = $activeUser->phone_number;
            $text = 'کاربر عزیز تنخواه گردان
از امروز نرم‌افزار تنخواه گردان با هدف توسعه بیشتر امکانات و ارائه خدمات بهتر، در دو طرح استاندارد و حرفه‌ای در دسترس بوده و یک ماه اشتراک طرح حرفه‌ای به عنوان هدیه برای شما فعال شد.
اطلاعات شما در هر دو طرح بمانند گذشته در دسترس شما خواهند بود و با به‌روز رسانی مستمر نرم‌افزار خود، کیفیت خدمات بهتری را تجربه کنید.
لینک دانلود اپلیکیشن یا استفاده از وب:
https://bitn.ir/8Ziik';

            $this->sendSms($receptor, $text);

            $bar->advance();
        }

        $this->info('======================================================================');
        $this->info('Sending inactive sms');
        $bar = $this->output->createProgressBar(count($inactiveUsers));
        $campaign = Campaign::query()->firstOrCreate([
            'symbol' => 'RELEASE'
        ], [
            'start_date' => now(),
            'end_date' => null,
            'count' => 0,
            'name' => 'انتشار نسخه جدید'
        ]);
        foreach ($inactiveUsers as $inactiveUser) {
            $receptor = $inactiveUser->phone_number;

            $code = Helpers::generatePromoCode();
            $promoCode = $campaign->promoCodes()->create([
                'user_id' => $inactiveUser->id,
                'max_count' => 1,
                'code' => $code,
                'discount_percent' => 40,
                'max_discount' => null,
                'start_at' => now()->toDateTimeString(),
                'expire_at' => '2021-01-29 23:59:59',
                'text' => 'هدیه به کاربران قدیمی',
            ]);

            $campaign->count = $campaign->promoCodes()->count();
            $campaign->save();

            $text = "کاربر عزیز تنخواه گردان
۴۰% تخفیف خرید اشتراک طرح حرفه‌ای، هدیه تنخواه گردان به کاربران قدیمی.
لینک دانلود اپلیکیشن یا استفاده از وب:
https://bitn.ir/IIS6x
کد تخفیف اختصاصی شما:
{$code}
مهلت استفاده: تا ۱۰ دی";

            $this->sendSms($receptor, $text);

            $bar->advance();
        }

        Excel::store((new ReleaseSmsExport($this->file)), 'user_sms.xlsx');

        if ($this->confirm('Commit changes?')) {
            DB::commit();
        } else {
            DB::rollBack();
            $this->error('rolled backed');
        }

    }

    private function createUser($userId, int $userCount)
    {
        /** @var User $user */
        $user = User::query()->find($userId);

        $price = PremiumPrices::getPrice(PremiumDuration::MONTH);
        $user->userStatus()->create([
            'start_date' => now(),
            'end_date' => now()->addDays($price['day_count'])->endOfDay(),
            'user_count' => $userCount,
            'volume_size' => 1000,
            'price_id' => $price['id']
        ]);

        $campaign = Campaign::query()->firstOrCreate([
            'symbol' => 'RELEASE'
        ], [
            'start_date' => now(),
            'end_date' => null,
            'count' => 0,
            'name' => 'انتشار نسخه جدید'
        ]);
        $campaign->count++;
        $campaign->save();

        $code = Helpers::generatePromoCode();
        $promoCode = $campaign->promoCodes()->create([
            'user_id' => $userId,
            'max_count' => 1,
            'code' => $code,
            'discount_percent' => 100,
            'max_discount' => null,
            'start_at' => now()->toDateTimeString(),
            'expire_at' => now()->toDateTimeString(),
            'text' => 'یک ماه اشتراک رایگان به مناسبت انتشار نسخه جدید',
        ]);

        $priceData = [
            'type' => PurchaseType::NEW,
            'price_id' => $price['id'],
            'user_count' => $userCount,
            'volume_size' => 1000,
            'promo_code' => null,
            'use_wallet' => false,
        ];

        $cost = $this->getPremiumCost($user, $priceData);

        $user->userStatusLog()->create([
            'campaign_id' => $campaign->id,
            'promo_code_id' => $promoCode->id,
            'start_date' => now(),
            'end_date' => now()->addDays($price['day_count'])->endOfDay(),
            'total_amount' => $cost['total_amount'],
            'added_value_amount' => $cost['added_value_amount'],
            'discount_amount' => $cost['discount_amount'],
            'promo_code_text' => $promoCode->text,
            'volume_size' => 1000,
            'user_count' => $userCount,
            'type' => PurchaseType::NEW,
            'status' => UserStatusType::SUCCEED,
            'price_id' => $price['id'],
        ]);
    }

    public function getPremiumCost(User &$user, $data)
    {
        $percent = 1;
        $totalAmount = 0;
        $priceId = $data['price_id'];
        $price = PremiumPrices::getPrice($priceId);

        $userCount = $data['user_count'];
        $volumeSize = $data['volume_size'];
        $userPrice = collect($price['user_price'])->where('value', $userCount)->first()['price'];
        $volumePrice = collect($price['volume_price'])->where('value', $volumeSize)->first()['price'];
        $constantPrice = $price['constant_price'];

        $totalAmount += max(1000, ceil(($userPrice + $volumePrice + $constantPrice) * $percent));

        $discountAmount = $totalAmount;
        $addedValueAmount = round(($totalAmount - $discountAmount) * PremiumConstants::ADDED_VALUE_PERCENT);
        $payableAmount = Helpers::getPayableAmount($totalAmount, $addedValueAmount, $discountAmount, 0);
        $walletAmount = $data['use_wallet'] ? min($user->wallet_amount, $payableAmount) : 0;

        return [
            'total_amount' => $totalAmount,
            'payable_amount' => $payableAmount - $walletAmount,
            'added_value_amount' => $addedValueAmount,
            'discount_amount' => $discountAmount,
            'wallet_amount' => $walletAmount,
            'added_value_percent' => PremiumConstants::ADDED_VALUE_PERCENT * 100,
        ];
    }

    private function sendSms($receptor, $text)
    {
        $this->file->push([
            'phone_number' => $receptor,
            'sms' => $text
        ]);
        return;
        try {
            $result = Kavenegar::Send('10005000000550', $receptor, $text);
        } catch (Exception $exception) {
            $this->error('Error in sending sms to ' . $receptor);
            $this->error($exception->getMessage());
        }
    }
}
