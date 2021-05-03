<?php

namespace App\Jobs;

use App\Campaign;
use App\Helpers\Helpers;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Kavenegar;
use Log;

class AutomationSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var User
     */
    private $user;
    private $type;


    /**
     * Create a new job instance.
     *
     * @param User $user
     */
    public function __construct(User &$user, $type)
    {
        $this->user = $user;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $tokens = ['', '', ''];
        $receptor = $this->user->phone_number;
        [$pattern, $text, $params] = $this->smsText($this->user, $this->type);


        if (in_array($pattern, ['active-18Day', 'active-first-discount', 'active-discount-yearly'])) {
            /** @var Campaign $campaign */
            $campaign = Campaign::query()->firstOrCreate([
                'symbol' => 'ACTIVE_A',
            ], [
                'start_date' => now(),
                'end_date' => null,
                'count' => 0,
                'name' => 'هدیه به کاربران فعال',
            ]);

            $code = Helpers::generatePromoCode();
            $discount = $pattern == 'active-discount-yearly' ? 50 : 30;
            $date = Helpers::gregorianDateStringToJalali(now()->addDays(5)->toDateString());
            $promoCode = $campaign->promoCodes()->create([
                'user_id' => $this->user->id,
                'max_count' => 1,
                'code' => $code,
                'discount_percent' => $discount,
                'max_discount' => null,
                'text' => 'هدیه اپلیکیشن',
                'start_at' => now()->toDateTimeString(),
                'end_date' => now()->addDays(5)->endOfDay()->toDateTimeString(),
            ]);
        }

        foreach ($params as $key => $param) {
            if ($param == '#PROMO_CODE_PERCENT#') {
                $tokens[$key] = $discount;
            } elseif ($param == '#PROMO_CODE#') {
                $tokens[$key] = $code;
            } elseif ($param == '#PROMO_CODE_DATE#') {
                $tokens[$key] = $date;
            } else {
                $tokens[$key] = $param;
            }
            $text = str_replace('%token' . ($key + 1), $tokens[$key], $text);
        }

        $result = Kavenegar::VerifyLookup($receptor, $tokens[0], $tokens[1], $tokens[2], $pattern, 'sms');
        if ($result) {
            $message = $result[0]->statustext;
            Log::info('Send to kavenegar   ====>   ' . $receptor . '  ' . $message);

            $this->user->sentSms()->create([
                'type' => $this->type,
                'sms_text' => $text,
                'sent_time' => now()->toDateTimeString(),
            ]);
        }
    }


    private function smsText(User $user, $type)
    {
        switch ($type) {
            case 2:
                return ['active-1Day', 'کاربرگرامی %token گردان
برای آموزش و ارائه پیشنهاد و انتقادات، پشتیبانی تنخواه گردان همه روزه در خدمت شماست.
شماره تماس: 02162995555', ['تنخواه']];
            case 4:
                return ['active-3Day', 'کاربر گرامی
با %token گردان کلیه دریافت‌ها و پرداخت‌های مالی خود را به شکل ساده ثبت و تصویر آنها را پیوست نمایید و در سرفصل‌های مربوطه طبقه‌بندی کنید.

https://tankhahgardan.com/features/', ['تنخواه']];
            case 6:
                return ['active-9day', 'با حذف کاغذهای اضافی به حفظ طبیعت کمک کنیم.
حفظ طبیعت، حفظ زندگی
اپلیکیشن %token گردان

https://www.aparat.com/v/bUA9J', ['تنخواه']];
            case 7:
                return ['active-18Day', 'کاربر گرامی تنخواه‌گردان
به پاس همراهی شما با اپلیکیشن تنخواه‌گردان کد تخفیف %token درصدی به شما تعلق گرفت.

کد تخفیف:‌ %token2
مهلت استفاده:‌ %token3', ['#PROMO_CODE_PERCENT#', '#PROMO_CODE#', '#PROMO_CODE_DATE#']];
            case 9:
            case 10:
                return ['active-info-support', 'کاربر گرامی %token گردان
برای آموزش سریع استفاده از تنخواه‌گردان از لینک زیر استفاده کنید و یا با همکاران واحد پشتیبانی تماس بگیرید.
شماره تماس پشتیبانی:‌ 02162995555
لینک آموزش سریع: https://tankhahgardan.com/blog/app-education/
', ['تنخواه']];
            case 12:
            case 24:
                return ['active-first-discount', 'کاربر گرامی تنخواه‌گردان
%token درصد تخفیف اولین خرید اشتراک اپلیکیشن تنخواه‌گردان

کد تخفیف:‌ %token2
مهلت استفاده: %token3', ['#PROMO_CODE_PERCENT#', '#PROMO_CODE#', '#PROMO_CODE_DATE#']];
            case 15:
                return ['active-questionnaire', 'کاربر گرامی %token گردان
پرسشنامه پیش رو جهت بهبود خدمات تنخواه‌گردان تهیه شده است.
خواهشمندیم زمان کوتاهی از وقت ارزشمند خود را صرف پاسخگویی به پرسشنامه زیر کنید.

شروع پاسخگویی:‌', ['تنخواه']];
            case 17:
            case 22:
                return ['active-discount-yearly', 'کاربر گرامی تنخواه‌گردان
%token درصد تخفیف خرید اشتراک سالانه تنخواه‌گردان به شما تعلق گرفت.

کد تخفیف: %token2
مهلت استفاده: %token3', ['#PROMO_CODE_PERCENT#', '#PROMO_CODE#', '#PROMO_CODE_DATE#']];
            case 20:
                if ($user->sentSms()->whereIn('type', [15, 20, 26])->exists()) {
                    return ['active-info-support', 'کاربر گرامی %token گردان
برای آموزش سریع استفاده از تنخواه‌گردان از لینک زیر استفاده کنید و یا با همکاران واحد پشتیبانی تماس بگیرید.
شماره تماس پشتیبانی:‌ 02162995555
لینک آموزش سریع: https://tankhahgardan.com/blog/app-education/
', ['تنخواه']];
                } else {
                    ['active-questionnaire', 'کاربر گرامی %token گردان
پرسشنامه پیش رو جهت بهبود خدمات تنخواه‌گردان تهیه شده است.
خواهشمندیم زمان کوتاهی از وقت ارزشمند خود را صرف پاسخگویی به پرسشنامه زیر کنید.

شروع پاسخگویی:‌', ['تنخواه']];
                }
            case 26:
                if ($user->sentSms()->whereIn('type', [15, 20, 26])->exists()) {
                    return ['active-60Day', 'کاربر گرامی %token گردان
جهت مشاوره و ارائه انتقادات و پیشنهادات خود می‌توانید همه روزه با شماره 02162995555 تماس حاصل فرمایید.

حذف کاغذهای اضافی، حفظ طبیعت، حفظ زندگی', ['تنخواه']];
                } else {
                    ['active-questionnaire', 'کاربر گرامی %token گردان
پرسشنامه پیش رو جهت بهبود خدمات تنخواه‌گردان تهیه شده است.
خواهشمندیم زمان کوتاهی از وقت ارزشمند خود را صرف پاسخگویی به پرسشنامه زیر کنید.

شروع پاسخگویی:‌', ['تنخواه']];
                }
            default:
                return [null, null, null];
        }
    }
}
