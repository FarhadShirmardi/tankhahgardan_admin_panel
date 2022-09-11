<?php

namespace App\Jobs;

use App\FirebaseToken;
use App\Helpers\Helpers;
use App\Models\PromoCode;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Kavenegar;
use Log;

class PromoCodeSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $phoneNumber;
    /**
     * @var PromoCode
     */
    private $promoCode;

    /**
     * Create a new job instance.
     *
     */
    public function __construct($phoneNumber, PromoCode $promoCode)
    {
        $this->phoneNumber = $phoneNumber;
        $this->promoCode = $promoCode;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $percent = Helpers::getPersianString($this->promoCode->discount_percent);
        $code = $this->promoCode->code;
        $datetime = explode(' ', Helpers::convertDateTimeToJalali($this->promoCode->expire_at))[0];
        $result = Kavenegar::VerifyLookup($this->phoneNumber, $percent, $code, $datetime, 'active-18Day', 'sms');
        if ($result) {
            $message = $result[0]->statustext;
            Log::info('Send to kavenegar   ====>   ' . $this->phoneNumber . '  ' . $message);
        }
    }
}
