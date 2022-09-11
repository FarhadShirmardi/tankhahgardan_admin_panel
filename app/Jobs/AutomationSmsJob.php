<?php

namespace App\Jobs;

use App\Constants\PremiumDuration;
use App\Helpers\Helpers;
use App\Models\Campaign;
use App\Models\User;
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

        if (is_null($pattern)) {
            return;
        }

        if (in_array($pattern, ['active-18Day', 'active-first-discount', 'active-discount-yearly', 'active-before-automation-discount'])) {
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
            $discount = $pattern == 'active-discount-yearly' ? 15 : 10;
            $priceId = $pattern == 'active-discount-yearly' ? PremiumDuration::YEAR : null;
            $date = Helpers::gregorianDateStringToJalali(now()->addDays(5)->toDateString());
            $promoCode = $campaign->promoCodes()->create([
                'user_id' => $this->user->id,
                'max_count' => 1,
                'code' => $code,
                'discount_percent' => $discount,
                'max_discount' => null,
                'text' => 'هدیه اپلیکیشن',
                'start_at' => now()->toDateTimeString(),
                'expire_at' => now()->addDays(5)->endOfDay()->toDateTimeString(),
                'price_id' => $priceId,
            ]);
        }
        foreach ($params as $key => $param) {
            if ($param == '#PROMO_CODE_PERCENT#') {
                $tokens[$key] = $discount;
            } elseif ($param == '#PROMO_CODE#') {
                $tokens[$key] = $code;
            } elseif ($param == '#PROMO_CODE_DATE#') {
                $tokens[$key] = $date;
            } elseif ($param == '#USER_ID') {
                $tokens[$key] = $this->user->id;
            } else {
                $tokens[$key] = $param;
            }
            $text = str_replace('%token' . ($key + 1), $tokens[$key], $text);
        }

        $result = Kavenegar::VerifyLookup($receptor, $tokens[0], $tokens[1], $tokens[2], $pattern, 'sms');
        if ($result) {
            $message = $result[0]->statustext;
            Log::info('Send to kavenegar   ====>   ' . $receptor . '  ' . $message);

            $this->user->automationSms()->create([
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
                return ['active-1Day', 'کاربرگرامی %token1 گردان
برای آموزش و ارائه پیشنهاد و انتقادات، پشتیبانی تنخواه گردان همه روزه در خدمت شماست.
شماره تماس: 02162995555', ['تنخواه']];
            case 4:
                return ['active-3Day', 'کاربر گرامی
با %token1 گردان کلیه دریافت‌ها و پرداخت‌های مالی خود را به شکل ساده ثبت و تصویر آنها را پیوست نمایید و در سرفصل‌های مربوطه طبقه‌بندی کنید.

https://tankhahgardan.com/features/', ['تنخواه']];
            case 6:
                return ['active-9day', 'با حذف کاغذهای اضافی به حفظ طبیعت کمک کنیم.
حفظ طبیعت، حفظ زندگی
اپلیکیشن %token1 گردان

https://www.aparat.com/v/bUA9J', ['تنخواه']];
            case 7:
                return ['active-18Day', 'کاربر گرامی تنخواه‌گردان
به پاس همراهی شما با اپلیکیشن تنخواه‌گردان کد تخفیف %token1 درصدی به شما تعلق گرفت.

کد تخفیف:‌ %token2
مهلت استفاده:‌ %token3', ['#PROMO_CODE_PERCENT#', '#PROMO_CODE#', '#PROMO_CODE_DATE#']];
            case 9:
            case -3:
            case 10:
                return ['active-info-support', 'کاربر گرامی %token1 گردان
برای آموزش سریع استفاده از تنخواه‌گردان از لینک زیر استفاده کنید و یا با همکاران واحد پشتیبانی تماس بگیرید.
شماره تماس پشتیبانی:‌ 02162995555
لینک آموزش سریع: https://tankhahgardan.com/blog/app-education/
', ['تنخواه']];
            case 12:
            case 23:
                return ['active-first-discount', 'کاربر گرامی تنخواه‌گردان
%token1 درصد تخفیف اولین خرید اشتراک اپلیکیشن تنخواه‌گردان

کد تخفیف:‌ %token2
مهلت استفاده: %token3', ['#PROMO_CODE_PERCENT#', '#PROMO_CODE#', '#PROMO_CODE_DATE#']];
            case -2:
            case 15:
                return ['active-questionnaire', 'کاربر گرامی %token1 گردان
پرسشنامه پیش رو جهت بهبود خدمات تنخواه‌گردان تهیه شده است.
خواهشمندیم زمان کوتاهی از وقت ارزشمند خود را صرف پاسخگویی به پرسشنامه زیر کنید.

شروع پاسخگویی:‌ https://tankhahgardan.com/blog?page_id=%token2&q=%token3', ['تنخواه', '630', '#USER_ID']];
            case 17:
            case 22:
                return ['active-discount-yearly', 'کاربر گرامی تنخواه‌گردان
%token1 درصد تخفیف خرید اشتراک سالانه تنخواه‌گردان به شما تعلق گرفت.

کد تخفیف: %token2
مهلت استفاده: %token3', ['#PROMO_CODE_PERCENT#', '#PROMO_CODE#', '#PROMO_CODE_DATE#']];
            case 20:
                if ($user->automationSms()->whereIn('type', [-2, 15, 20, 26])->exists()) {
                    return ['active-info-support', 'کاربر گرامی %token1 گردان
برای آموزش سریع استفاده از تنخواه‌گردان از لینک زیر استفاده کنید و یا با همکاران واحد پشتیبانی تماس بگیرید.
شماره تماس پشتیبانی:‌ 02162995555
لینک آموزش سریع: https://tankhahgardan.com/blog/app-education/
', ['تنخواه']];
                } else {
                    return ['active-questionnaire', 'کاربر گرامی %token1 گردان
پرسشنامه پیش رو جهت بهبود خدمات تنخواه‌گردان تهیه شده است.
خواهشمندیم زمان کوتاهی از وقت ارزشمند خود را صرف پاسخگویی به پرسشنامه زیر کنید.

شروع پاسخگویی:‌ https://tankhahgardan.com/blog?page_id=%token2&q=%token3', ['تنخواه', '630', '#USER_ID']];
                }
            case 26:
                if ($user->automationSms()->whereIn('type', [-2, 15, 20, 26])->exists()) {
                    return ['active-60Day', 'کاربر گرامی %token1 گردان
جهت مشاوره و ارائه انتقادات و پیشنهادات خود می‌توانید همه روزه با شماره 02162995555 تماس حاصل فرمایید.

حذف کاغذهای اضافی، حفظ طبیعت، حفظ زندگی', ['تنخواه']];
                } else {
                    return ['active-questionnaire', 'کاربر گرامی %token1 گردان
پرسشنامه پیش رو جهت بهبود خدمات تنخواه‌گردان تهیه شده است.
خواهشمندیم زمان کوتاهی از وقت ارزشمند خود را صرف پاسخگویی به پرسشنامه زیر کنید.

شروع پاسخگویی:‌ https://tankhahgardan.com/blog?page_id=%token2&q=%token3', ['تنخواه', '630', '#USER_ID']];
                }
            case -7:
                return ['active-before-automation-discount', 'کاربر گرامی تنخواه‌گردان
%token1 درصد تخفیف خرید اشتراک اپلیکیشن تنخواه‌گردان

کد تخفیف:‌ %token2
مهلت استفاده: %token3', ['#PROMO_CODE_PERCENT#', '#PROMO_CODE#', '#PROMO_CODE_DATE#']];
            default:
                return [null, null, null];
        }
    }
}
