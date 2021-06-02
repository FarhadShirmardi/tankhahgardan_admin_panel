<?php

namespace App\Http\Controllers\Dashboard;

use App\Constants\LogType;
use App\Constants\PremiumConstants;
use App\Constants\PremiumDuration;
use App\Constants\PremiumPrices;
use App\Constants\PurchaseType;
use App\Constants\UserStatusType;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\PanelInvoice;
use App\PanelUser;
use App\User;
use App\UserStatusLog;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Validator;

class PremiumController extends Controller
{
    public function walletView($userId)
    {
        $user = User::query()->findOrFail($userId);

        return view('dashboard.premium.wallet', [
            'user' => $user,
        ]);
    }

    public function walletStore(Request $request, $userId)
    {
        $user = User::query()->findOrFail($userId);

        $oldJson = [
            'real_value' => $user->wallet_amount,
            'total_value' => $user->wallet,
            'reserve_value' => $user->reserve_wallet,
        ];

//        dd($user->wallet, $user->wallet_amount);
        $isMinus = $request->input('minus', 'off') == 'on' ? -1 : 1;
        $charge_amount = (int)Helpers::getEnglishString($request->charge_amount);

        $charge_amount = $isMinus == -1 ? min($charge_amount, $user->wallet_amount) : $charge_amount;
        $user->wallet = max(0, $user->wallet + ($isMinus * $charge_amount));
        $user->save();

        /** @var PanelUser $panelUser */
        $panelUser = auth()->user();
        $panelUser->logs()->create([
            'user_id' => $userId,
            'type' => LogType::EDIT_WALLET,
            'date_time' => now()->toDateTimeString(),
            'description' => LogType::getDescription(LogType::EDIT_WALLET, $panelUser),
            'old_json' => json_encode($oldJson),
            'new_json' => json_encode([
                'real_value' => $user->wallet_amount,
                'total_value' => $user->wallet,
                'reserve_value' => $user->reserve_wallet,
                'text' => $request->text,
            ]),
        ]);

        return redirect()->route('dashboard.report.userActivity', ['id' => $userId])->with('success', 'با موفقیت انجام شد.');
    }

    public function purchase($userId, $type, $id)
    {
        $user = User::query()->findOrFail($userId);
        $userStates = $this->getUserStates($user);
        $selectedPlan = $userStates->where('id', $id)->first();
        $openInvoices = $user->invoices()->where('status', UserStatusType::PENDING)->exists();

        try {
            if ($openInvoices) {
                throw new \UnexpectedValueException('برای ایجاد پیش فاکتور جدید ابتدا تمام پیش فاکتورهای قبل را ببندید.');
            }
            $this->validateType($type, $userStates, $selectedPlan);
        } catch (\UnexpectedValueException $exception) {
            $validator = Validator::make([], []);
            $validator->errors()->add('error', $exception->getMessage());
            return redirect()->back()->withErrors($validator);
        } catch (\Exception $exception) {
            dd($exception);
        }

        $prices = [
            PremiumDuration::MONTH,
            PremiumDuration::YEAR,
            PremiumDuration::SPECIAL,
        ];

        $currentPlan = PurchaseType::UPGRADE ? $userStates->where('is_active', true)->first() : null;

        return view('dashboard.premium.purchase', [
            'user' => $user,
            'type' => $type,
            'selected_plan' => $selectedPlan,
            'prices' => $prices,
            'current_plan' => $currentPlan,
        ]);
    }

    public function getUserStates(User $user)
    {
        $userStates = $user->userStatus()->orderBy('end_date', 'desc')->get();
        $userStates->transform(function ($item) use ($user) {
            $item['is_active'] =
                (now()->toDateTimeString() > $item['start_date'] and now()->toDateTimeString() < $item['end_date']);
            $item['is_last_item'] = false;

            $userStatusLogs = $user->userStatusLogNull()->where('status', true)
                ->where('start_date', '>=', $item['start_date'])
                ->where('end_date', '<=', $item['end_date'])
                ->get();

            $amount = 0;
            $price = PremiumPrices::getPrice($item['price_id']);
            if ($item['is_active'] and !$price['is_gift']) {
                /** @var UserStatusLog $userStatusLog */
                foreach ($userStatusLogs as $userStatusLog) {
                    $percent = Helpers::calculatePercent($userStatusLog, PurchaseType::UPGRADE);
                    $amount += $percent * Helpers::getPayableAmount($userStatusLog->total_amount, $userStatusLog->added_value_amount, 0, 0);
                }
            }

            $item['payable_amount'] = 10 * (int)(round($amount) / 10);
            $item['start_date'] = Helpers::convertDateTimeToJalali($item['start_date']);
            $item['end_date'] = Helpers::convertDateTimeToJalali($item['end_date']);
            return $item;
        });
        $userStates->first()['is_last_item'] = true;

        return $userStates;
    }

    private function validateType($type, $userStates, $selectedPlan)
    {
        if ($type == PurchaseType::UPGRADE) {
            if (!$selectedPlan->is_active) {
                throw new \UnexpectedValueException('برای ارتقا طرح باید فعال باشد.');
            }
        } elseif ($type == PurchaseType::EXTEND) {
            if (!$selectedPlan->is_last_item or !$selectedPlan->is_active) {
                throw new \UnexpectedValueException('تنها آخرین طرح را می‌توانید تمدید کنید');
            }
        } elseif ($type == PurchaseType::NEW) {
            $activePlan = $userStates->where('is_active', true)->first();
            if ($activePlan) {
                throw new \UnexpectedValueException('برای خرید نباید طرح فعالی داشته باشید.');
            }
        } else {
            throw new \UnexpectedValueException('نوع طرح صحیح نیست.');
        }
    }

    public function previewPurchase(Request $request, $userId, $type, $id)
    {
        try {
            return $this->validatePurchase($request, $type, $userId, $id);
        } catch (\UnexpectedValueException $exception) {
            $validator = Validator::make([], []);
            $validator->errors()->add('error', $exception->getMessage());
            return redirect()->back()->withErrors($validator);
        } catch (\Exception $exception) {
            dd($exception);
        }
    }

    private function validatePurchase(Request $request, $type, $userId, $id)
    {
        $user = User::query()->findOrFail($userId);
        $userStates = $this->getUserStates($user);
        $selectedPlan = $userStates->where('id', $id)->first();
        $this->validateType($type, $userStates, $selectedPlan);

        $request = $this->convertDate($request);
        $priceId = $request->price_id;
        if ($type == PurchaseType::UPGRADE) {
            if ($request->user_count == 0 and $request->volume_size == 0) {
                throw new \UnexpectedValueException('هر ۲ فیلد حجم و کاربر نمی‌توانند صفر باشند.');
            }
            $priceId = $selectedPlan->price_id;
            $price = PremiumPrices::getPrice($priceId, $selectedPlan->user_count, $selectedPlan->volume_size, true);
        } else {
            $price = PremiumPrices::getPrice($priceId);
        }
        $data = [];
        $activePlan = $user->userStatus()->orderBy('end_date', 'desc')->first();
        if ($type == PurchaseType::NEW) {
            $startDate = now()->toDateTimeString();
            if ($priceId == PremiumDuration::SPECIAL) {
                $carbon = new Carbon();
                $startDate = $carbon->parse($request->start_date);
                $endDate = $carbon->parse($request->end_date);
                if ($endDate->lt($startDate)) {
                    throw new \UnexpectedValueException('تاریخ شروع نمیتواند بزرگتر از تاریخ پایان باشد.');
                }
            } elseif ($priceId == PremiumDuration::MONTH) {
                $endDate = now()->addDays(31)->endOfDay()->toDateTimeString();
            } elseif ($priceId == PremiumDuration::YEAR) {
                $endDate = now()->addDays(365)->endOfDay()->toDateTimeString();
            }
        } elseif ($type == PurchaseType::UPGRADE) {
            $startDate = now()->toDateTimeString();
            $endDate = $activePlan->end_date;
        } elseif ($type == PurchaseType::EXTEND) {
            $startDate = new Carbon($activePlan->end_date);
            $startDate = $startDate->addDay()->startOfDay();
            if ($priceId == PremiumDuration::SPECIAL) {
                $carbon = new Carbon();
                $endDate = $carbon->parse($request->end_date);
                if ($endDate->lt($startDate)) {
                    throw new \UnexpectedValueException('تاریخ شروع نمیتواند بزرگتر از تاریخ پایان باشد.');
                }
            } elseif ($priceId == PremiumDuration::MONTH) {
                $endDate = $startDate->addDays(31)->endOfDay()->toDateTimeString();
            } elseif ($priceId == PremiumDuration::YEAR) {
                $endDate = $startDate->addDays(365)->endOfDay()->toDateTimeString();
            }
        }

        $percent = Helpers::calculatePercent($selectedPlan, $type);
        $userPrice = collect($price['user_price'])->where('value', $request->user_count)->first()['price'];
        $volumePrice = collect($price['volume_price'])->where('value', $request->volume_size)->first()['price'];
        $constantPrice = $type == PurchaseType::UPGRADE ? 0 : $price['constant_price'];
        $totalAmount =
            $priceId == PremiumDuration::SPECIAL ? $request->total_price :
                max(1000, ceil(($userPrice + $volumePrice + $constantPrice) * $percent));
        $discountAmount = (int)Helpers::getEnglishString($request->discount_price);
        $payableAmount = Helpers::getPayableAmount($totalAmount, 0, $discountAmount, 0);
        $useWallet = $request->use_wallet == 'on';
        $walletAmount = $useWallet ? min($user->wallet_amount, $payableAmount) : 0;
        $addedValueAmount =
            round(($totalAmount - $discountAmount - $walletAmount) * PremiumConstants::ADDED_VALUE_PERCENT);

        /** @var PanelInvoice $invoice */
        $invoice = $user->invoices()->firstOrNew([
            'status' => UserStatusType::PENDING,
        ]);

        $invoice->start_date = $startDate;
        $invoice->type = $type;
        $invoice->end_date = $endDate;
        $invoice->volume_size = $request->volume_size;
        $invoice->user_count = $request->user_count;
        $invoice->wallet_amount = $walletAmount;
        $invoice->total_amount = $totalAmount;
        $invoice->discount_amount = $discountAmount;
        $invoice->added_value_amount = $addedValueAmount;
        $invoice->price_id = $priceId;
        $invoice->save();

        /** @var PanelUser $panelUser */
        $panelUser = auth()->user();
        $panelUser->logs()->create([
            'user_id' => $invoice->user_id,
            'type' => LogType::NEW_INVOICE,
            'date_time' => now()->toDateTimeString(),
            'description' => LogType::getDescription(LogType::NEW_INVOICE, $panelUser),
            'old_json' => json_encode([]),
            'new_json' => $invoice->toJson(),
        ]);

        return redirect(route('dashboard.report.userActivity', ['id' => $user->id]))->with('success', 'پیش فاکتور با موفقیت ایجاد شد');
    }

    private function convertDate($request)
    {
        try {
            $request->merge([
                'start_date' => str_replace('/', '-', Helpers::convertDateTimeToGregorian(Helpers::getEnglishString($request->start_date))),
                'end_date' => str_replace('/', '-', Helpers::convertDateTimeToGregorian(Helpers::getEnglishString($request->end_date))),
            ]);
        } catch (\Exception $exception) {

        }
        return $request;
    }

    public function deleteInvoice($userId, $id)
    {
        $user = User::query()->findOrFail($userId);
        /** @var PanelInvoice $invoice */
        $invoice = $user->invoices()->findOrFail($id);

        if ($invoice->status != UserStatusType::PENDING) {
            $validator = Validator::make([], []);
            $validator->errors()->add('error', 'وضعیت پیش‌فاکتور باید «در انتظار» باشد.');
            return redirect()->back()->withErrors($validator);
        }
        \DB::transaction(function () use ($invoice) {
            /** @var PanelUser $panelUser */
            $panelUser = auth()->user();
            $panelUser->logs()->create([
                'user_id' => $invoice->user_id,
                'type' => LogType::DELETE_INVOICE,
                'date_time' => now()->toDateTimeString(),
                'description' => LogType::getDescription(LogType::DELETE_INVOICE, $panelUser),
                'old_json' => $invoice->toJson(),
                'new_json' => json_encode([]),
            ]);

            $invoice->delete();
        });

        return redirect()->back()->with('success', 'با موفقیت انجام شد.');
    }

    public function payInvoice(Request $request, $userId, $id)
    {
        $user = User::query()->findOrFail($userId);
        /** @var PanelInvoice $invoice */
        $invoice = $user->invoices()->findOrFail($id);
        $invoice['text'] = $request->text;

        \DB::transaction(function () use ($invoice) {
            $http = new Client;
            $response = $http->get(
                env('TANKHAH_URL') . '/panel/' .
                env('TANKHAH_TOKEN') . '/invoice/' . $invoice['id'] . '/pay',
                [
                    'headers' => [
                        'Accept' => 'application/json',
                    ],
                ]
            );
            if ($response->getStatusCode() != 200) {
                throw new \Exception($response->getBody());
            }
            /** @var PanelUser $panelUser */
            $panelUser = auth()->user();
            $panelUser->logs()->create([
                'user_id' => $invoice->user_id,
                'type' => LogType::PAY_INVOICE,
                'date_time' => now()->toDateTimeString(),
                'description' => LogType::getDescription(LogType::PAY_INVOICE, $panelUser),
                'old_json' => $invoice->toJson(),
                'new_json' => json_encode([]),
            ]);
        });
        return redirect()->back()->with('success', 'با موفقیت انجام شد.');
    }

    public function closePlan(Request $request, $userId, $id)
    {
        $type = $request->type;
        $user = User::findOrFail($userId);
        $userStatus = $this->getUserStates($user)->where('id', $id)->first();
        $userStatus['close_type'] = $type;
        if ($type == 'wallet') {
            $user->wallet += $userStatus->payable_amount;
            $user->save();
        } elseif ($type == 'card') {

        }
        $user->userStatus()->where('id', $id)->update([
            'end_date' => now(),
        ]);
        /** @var PanelUser $panelUser */
        $panelUser = auth()->user();
        $panelUser->logs()->create([
            'user_id' => $userId,
            'type' => LogType::CLOSE_PLAN,
            'date_time' => now()->toDateTimeString(),
            'description' => LogType::getDescription(LogType::CLOSE_PLAN, $panelUser),
            'old_json' => $userStatus->toJson(),
            'new_json' => json_encode([]),
        ]);

        return redirect()->back()->with('success', 'با موفقیت انجام شد.');
    }
}
