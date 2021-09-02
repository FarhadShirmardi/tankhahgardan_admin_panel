<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Announcement;
use App\FirebaseToken;
use App\AnnouncementUser;
use Kreait\Firebase\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Log;
use App\Constants\NotificationType;

class PromoCodeSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $phoneNumber;
    private $template;
    private $code;

    /**
     * Create a new job instance.
     *
     */
    public function __construct($phoneNumber, $template, $code)
    {
        $this->phoneNumber = $phoneNumber;
        $this->template = $template;
        $this->code = $code;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $result = Kavenegar::VerifyLookup($this->phoneNumber, $this->code, '', '', $this->template, 'sms');
        if ($result) {
            $message = $result[0]->statustext;
            Log::info('Send to kavenegar   ====>   ' . $this->phoneNumber . '  ' . $message);
        }
    }
}
