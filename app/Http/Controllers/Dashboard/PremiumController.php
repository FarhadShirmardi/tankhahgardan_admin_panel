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
use App\PanelUser;
use App\User;
use Carbon\Carbon;
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
        $charge_amount = (int)$request->charge_amount;

        $charge_amount = $isMinus ? min($charge_amount, $user->wallet_amount) : $charge_amount;
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
                throw new \Exception('برای ایجاد پیش فاکتور جدید ابتدا تمام پیش فاکتورهای قبل را ببندید.');
            }
            $this->validateType($type, $userStates, $selectedPlan);
        } catch (\Exception $exception) {
            $validator = Validator::make([], []);
            $validator->errors()->add('error', $exception->getMessage());
            $validator->errors()->add('error', $exception->getFile());
            $validator->errors()->add('error', $exception->getLine());
            return redirect()->back()->withErrors($validator);
        }

        $prices = [
            PremiumDuration::MONTH,
            PremiumDuration::YEAR,
            PremiumDuration::SPECIAL,
        ];


        return view('dashboard.premium.purchase', [
            'user' => $user,
            'type' => $type,
            'selected_plan' => $selectedPlan,
            'prices' => $prices,
        ]);
    }

    public function getUserStates(User $user)
    {
        $userStates = $user->userStatus()->orderBy('end_date', 'desc')->get();
        $userStates->transform(function ($item) {
            $item['is_active'] =
                (now()->toDateTimeString() > $item['start_date'] and now()->toDateTimeString() < $item['end_date']);
            $item['is_last_item'] = false;
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
                throw new \Exception('برای ارتقا طرح باید فعال باشد.');
            }
        } elseif ($type == PurchaseType::EXTEND) {
            if (!$selectedPlan->is_last_item or !$selectedPlan->is_active) {
                throw new \Exception('تنها آخرین طرح را می‌توانید تمدید کنید');
            }
        } elseif ($type == PurchaseType::NEW) {
            $activePlan = $userStates->where('is_active', true)->first();
            if ($activePlan) {
                throw new \Exception('برای خرید نباید طرح فعالی داشته باشید.');
            }
        } else {
            throw new \Exception('نوع طرح صحیح نیست.');
        }
    }

    public function previewPurchase(Request $request, $userId, $type, $id)
    {
        try {
            $this->validatePurchase($request, $type, $userId, $id);
        } catch (\Exception $exception) {
            $validator = Validator::make([], []);
            $validator->errors()->add('error', $exception->getMessage());
            $validator->errors()->add('error', $exception->getFile());
            $validator->errors()->add('error', $exception->getLine());
            return redirect()->back()->withErrors($validator);
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
            $priceId = $selectedPlan->price_id;
            $price = PremiumPrices::getPrice($priceId, $selectedPlan->user_count, $selectedPlan->volume_size, true);
        } else {
            $price = PremiumPrices::getPrice($priceId);
        }
        $data = [];

        if ($type == PurchaseType::NEW) {
            $startDate = now()->toDateTimeString();
            if ($priceId == PremiumDuration::SPECIAL) {
                $carbon = new Carbon();
                $startDate = $carbon->parse($request->start_date);
                $endDate = $carbon->parse($request->end_date);
                if ($endDate->lt($startDate)) {
                    throw new \Exception('تاریخ شروع نمیتواند بزرگتر از تاریخ پایان باشد.');
                }
            } elseif ($priceId == PremiumDuration::MONTH) {
                $endDate = now()->addDays(31)->endOfDay()->toDateTimeString();
            } elseif ($priceId == PremiumDuration::YEAR) {
                $endDate = now()->addDays(365)->endOfDay()->toDateTimeString();
            }
        } elseif ($type == PurchaseType::UPGRADE) {

        } elseif ($type == PurchaseType::EXTEND) {

        }

        $percent = $this->calculatePercent($selectedPlan, $type);
        $userPrice = collect($price['user_price'])->where('value', $request->user_count)->first()['price'];
        $volumePrice = collect($price['volume_price'])->where('value', $request->volume_size)->first()['price'];
        $constantPrice = $type == PurchaseType::UPGRADE ? 0 : $price['constant_price'];
        $totalAmount = max(1000, ceil(($userPrice + $volumePrice + $constantPrice) * $percent));
        $discountAmount = $request->discount_amount;
        $payableAmount = Helpers::getPayableAmount($totalAmount, 0, $discountAmount, 0);
        $useWallet = $request->use_wallet == 'on';
        $walletAmount = $useWallet ? min($user->wallet_amount, $payableAmount) : 0;
        $addedValueAmount =
            round(($totalAmount - $discountAmount - $walletAmount) * PremiumConstants::ADDED_VALUE_PERCENT);
        $payableAmount = Helpers::getPayableAmount($totalAmount, $addedValueAmount, $discountAmount, 0);

        dd($payableAmount);
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

    private function calculatePercent(&$userStatus, $type)
    {
        $percent = 1;
        /** UserStatus $userStatus */
        if ($userStatus and $type == PurchaseType::UPGRADE) {
            $carbon = new Carbon();
            $startDate = $carbon->parse($userStatus->start_date);
            $endDate = $carbon->parse($userStatus->end_date);
            $total = $startDate->diffInDays($endDate);
            $remain = $endDate->diffInDays(now());
            $percent = $remain / $total;
        }
        return $percent;
    }
}
