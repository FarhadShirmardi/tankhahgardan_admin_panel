<?php

namespace App\Http\Controllers\Dashboard;

use App\Banner;
use App\Campaign;
use App\Constants\BannerStatus;
use App\Constants\BannerType;
use App\Constants\LogType;
use App\Constants\NotificationType;
use App\Constants\PremiumBanks;
use App\Constants\PremiumDuration;
use App\Constants\PremiumPrices;
use App\Constants\PremiumReportType;
use App\Constants\PurchaseType;
use App\Constants\UserStatusType;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Jobs\SendFirebaseNotificationJob;
use App\Jobs\PromoCodeSmsJob;
use App\PanelLogCenter;
use App\PanelUser;
use App\PromoCode;
use App\SmsLog;
use App\Transaction;
use App\User;
use App\UserStatusLog;
use Artisan;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Storage;
use Validator;

class ManagementController extends Controller
{
    private $reportController;

    public function __construct()
    {
        $this->reportController = app()->make(ReportController::class);
    }

    public function campaigns()
    {
        $campaigns = Campaign::query()
            ->leftJoin('panel_users', 'panel_users.id', 'panel_user_id')
            ->get([
                'campaigns.*',
                'panel_users.name as panel_user_name',
            ]);
        $campaigns = $campaigns->map(function ($item) {
            $item['start_date'] = $item['start_date'] ? Helpers::convertDateTimeToJalali($item['start_date']) : ' - ';
            $item['end_date'] = $item['end_date'] ? Helpers::convertDateTimeToJalali($item['end_date']) : ' - ';
            return $item;
        });
        return view('dashboard.management.campaigns', [
            'campaigns' => $campaigns,
        ]);
    }

    public function campaignUser(Request $request)
    {
        $userIds = explode(',', $request->userIds);
        foreach ($userIds as $userId) {
            User::query()->findOrFail($userId);
        }
        $campaigns = Campaign::query()
            ->leftJoin('panel_users', 'panel_users.id', 'panel_user_id')
            ->get([
                'campaigns.*',
                'panel_users.name as panel_user_name',
            ]);
        $campaigns = $campaigns->map(function ($item) {
            $item['start_date'] = $item['start_date'] ? Helpers::convertDateTimeToJalali($item['start_date']) : ' - ';
            $item['end_date'] = $item['end_date'] ? Helpers::convertDateTimeToJalali($item['end_date']) : ' - ';
            return $item;
        });
        return view('dashboard.management.campaigns', [
            'campaigns' => $campaigns,
            'userIds' => implode(',', $userIds),
        ]);
    }

    public function campaignItem($id)
    {
        /** @var Campaign $campaign */
        $campaign = collect();
        $promoCodes = collect();
        if ($id) {
            $campaign = Campaign::query()->findOrFail($id);
            $promoCodes = $campaign->promoCodes()
                ->leftJoin('panel_users', 'panel_users.id', 'panel_user_id')
                ->leftJoin('users', 'promo_codes.user_id', 'users.id')
                ->selectRaw("CONCAT_WS(' ', IFNULL(users.name, ''), IFNULL(users.family, '')) as name")
                ->selectSub($this->usedPromoCodeQuery(), 'used_promo_code_count')
                ->addSelect([
                    'promo_codes.*',
                    'panel_users.name as panel_user_name',
                    'users.phone_number',
                ])
                ->orderBy('updated_at', 'desc')
                ->get();
            $promoCodes = $promoCodes->map(function ($item) {
                $item['start_at'] = $item['start_at'] ? Helpers::convertDateTimeToJalali($item['start_at']) : ' - ';
                $item['expire_at'] = $item['expire_at'] ? Helpers::convertDateTimeToJalali($item['expire_at']) : ' - ';
                return $item;
            });
        }
        $promoCodes = Helpers::paginateCollection($promoCodes);

        return view('dashboard.management.campaign_item', [
            'campaign' => $campaign,
            'promoCodes' => $promoCodes,
            'prices' => $this->getPrices(),
            'id' => $id,
        ]);
    }

    private function usedPromoCodeQuery()
    {
        return UserStatusLog::query()
            ->where('status', UserStatusType::SUCCEED)
            ->whereColumn('promo_code_id', 'promo_codes.id')
            ->selectRaw('count(*)')
            ->getQuery();
    }

    public function campaignStore(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'symbol' => 'required',
            'count' => 'nullable|numeric',
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $campaign = Campaign::query()
            ->where('symbol', $request->symbol)
            ->first();
        if ($campaign and $campaign->id != $id) {
            $validator = Validator::make([], []);
            $validator->errors()->add('error', 'نماد کمپین تکراری است.');
            return redirect()->back()->withErrors($validator);
        }

        $request->merge([
            'start_date' => Helpers::convertDateTimeToGregorian(Helpers::getEnglishString($request->start_date)),
            'end_date' => Helpers::convertDateTimeToGregorian(Helpers::getEnglishString($request->end_date)),
            'panel_user_id' => auth()->id(),
        ]);

        Campaign::query()->updateOrCreate([
            'id' => $id,
        ], $request->all());

        return redirect()->route('dashboard.campaigns')->with('success', 'با موفقیت انجام شد');
    }

    public function campaignDelete($id)
    {
        $campaign = Campaign::query()->findOrFail($id);
        PromoCode::query()->where('campaign_id', $id)
            ->update([
                'expire_at' => now()->toDateTimeString(),
            ]);

        return redirect()->route('dashboard.campaigns')->with('success', 'با موفقیت انجام شد');
    }

    public function promoCodes(Request $request)
    {
        $userId = $request->user_id;
        $user = null;
        if ($userId) {
            $user = User::query()->findOrFail($userId);
        }

        $promoCodes = PromoCode::query()
            ->leftJoin('panel_users', 'panel_users.id', 'panel_user_id')
            ->leftJoin('users', 'promo_codes.user_id', 'users.id')
            ->selectRaw("CONCAT_WS(' ', IFNULL(users.name, ''), IFNULL(users.family, '')) as name")
            ->selectSub($this->usedPromoCodeQuery(), 'used_promo_code_count')
            ->where(function ($query) use ($userId) {
                if ($userId) {
                    $query->where('user_id', $userId)->orWhereNull('user_id');
                }
            })
            ->orderBy('created_at', 'desc')
            ->addSelect([
                'promo_codes.*',
                'panel_users.name as panel_user_name',
                'users.phone_number',
            ])
            ->get();

        $promoCodes = $promoCodes->map(function ($item) {
            $item['start_at'] = $item['start_at'] ? Helpers::convertDateTimeToJalali($item['start_at']) : ' - ';
            $item['expire_at'] = $item['expire_at'] ? Helpers::convertDateTimeToJalali($item['expire_at']) : ' - ';
            return $item;
        });

        $promoCodes = Helpers::paginateCollection($promoCodes, 10);

        return view('dashboard.management.promoCodes', [
            'promoCodes' => $promoCodes,
            'user' => $user,
            'prices' => $this->getPrices(),
        ]);
    }

    private function getPrices()
    {
        return [
            null => 'بدون طرح',
            PremiumDuration::MONTH => PremiumDuration::getTitle(PremiumDuration::MONTH),
            PremiumDuration::YEAR => PremiumDuration::getTitle(PremiumDuration::YEAR),
        ];
    }

    public function promoCodeItem(Request $request, $campaignId, $id)
    {
        $userIds = $request->userIds;
        /** @var Campaign $campaign */
        $campaign = Campaign::query()->findOrFail($campaignId);
        $promoCode = collect();
        $promoCode['code'] = Helpers::generatePromoCode();
        $user = null;
        if ($userIds) {
            $userIds = explode(',', $userIds);
            if (count($userIds) == 1) {
                $user = User::query()->findOrFail($userIds[0]);
            } else {
                foreach ($userIds as $userId) {
                    User::query()->findOrFail($userId);
                }
            }
            $userIds = implode(',', $userIds);
        }
        $transactions = [];
        if ($id) {
            $promoCode = $campaign->promoCodes()->findOrFail($id);
            $filter = [
                'promo_code_id' => $id,
                'sort_field' => 'date',
                'sort_type' => 'DESC',
            ];
            $transactions = $this->fetchTransactions($filter);
        }

        return view('dashboard.management.promoCode_item', [
            'campaign' => $campaign,
            'promoCode' => $promoCode,
            'user' => $user,
            'campaignId' => $campaignId,
            'id' => $id,
            'userIds' => $userIds,
            'transactions' => $transactions,
            'prices' => $this->getPrices(),
        ]);
    }

    public function fetchTransactions($filter)
    {
        $transactions = UserStatusLog::query()
            ->leftJoin('transactions', 'transactions.id', 'user_status_logs.transaction_id')
            ->join('users', 'users.id', 'user_status_logs.user_id')
            ->where(function ($query) use ($filter) {
                if (isset($filter['bank_ids']) and $filter['bank_ids'] != []) {
                    $query->whereIn('transactions.bank_id', $filter['bank_ids']);
                }
            })
            ->where(function ($query) use ($filter) {
                if (isset($filter['types']) and $filter['types'] != []) {
                    $query->whereIn('user_status_logs.type', $filter['types']);
                }
            })
            ->where(function ($query) use ($filter) {
                if (isset($filter['states']) and $filter['states'] != []) {
                    $query->whereIn('user_status_logs.status', $filter['states']);
                }
            })
            ->where(function ($query) use ($filter) {
                if (isset($filter['plan_ids']) and $filter['plan_ids'] != []) {
                    $query->whereIn('user_status_logs.price_id', $filter['plan_ids']);
                }
            })
            ->where(function ($query) use ($filter) {
                if (isset($filter['promo_code_id'])) {
                    $query->where('user_status_logs.promo_code_id', $filter['promo_code_id']);
                }
            })
            ->when(!empty($filter['phone_number']), function ($query) use ($filter) {
                $phoneNumber = ltrim(Helpers::getEnglishString($filter['phone_number']), '0');
                $phoneNumber = '%' . $phoneNumber . '%';
                return $query->having('phone_number', 'like', $phoneNumber);
            })
            ->where(function ($query) use ($filter) {
                if (isset($filter['start_date'])) {
                    $query->whereDate('user_status_logs.created_at', '>=', $filter['start_date']);
                }
                if (isset($filter['end_date'])) {
                    $query->whereDate('user_status_logs.created_at', '<=', $filter['end_date']);
                }
            })
            ->where(function ($query) use ($filter) {
                if (isset($filter['user_id'])) {
                    $query->where('users.id', $filter['user_id']);
                }
            })
            ->orderBy($filter['sort_field'], $filter['sort_type'])
            ->selectRaw("IFNULL(transactions.created_at, user_status_logs.created_at) as date")
            ->selectRaw("IF(users.name is null and users.family is null, users.phone_number, CONCAT_WS(' ', IFNULL(users.name, ''), IFNULL(users.family, ''))) as full_name")
            ->addSelect([
                'user_status_logs.user_id',
                'users.name as user_name',
                'users.family as user_family',
                'users.phone_number',
                'user_status_logs.price_id as plan_id',
                'user_status_logs.start_date as start_date',
                'user_status_logs.end_date as end_date',
                'user_status_logs.trace_number as mapsa_ref',
                'transactions.trace_no as bank_ref',
                'user_status_logs.type',
                'user_status_logs.user_count',
                'user_status_logs.volume_size',
                'user_status_logs.status as state',
                'transactions.bank_id as bank',
                'user_status_logs.wallet_amount',
                'user_status_logs.total_amount',
                'user_status_logs.discount_amount',
                'user_status_logs.added_value_amount',
            ])->get();

        $transactions = $transactions->map(function ($item) {
//            $item->full_name = ($item->user_name or $item->user_family) ?
//                $item->user_name . ' ' . $item->user_family : ' - ';
            $item->date = $item->date ? Helpers::convertDateTimeToJalali($item->date) : '-';
            $item->start_date = Helpers::convertDateTimeToJalali($item->start_date);
            $item->end_date = Helpers::convertDateTimeToJalali($item->end_date);
            $item->state = UserStatusType::getEnum($item->state);
            $item->bank = $item->bank ? PremiumBanks::getBank($item->bank)['name'] : '';
            $item->type = PurchaseType::getEnum($item->type);

            return $item;
        });

        return $transactions;
    }

    public function promoCodeStore(Request $request, $campaignId, $id)
    {
        if (empty($request->expire_at)) {
            $validator = Validator::make([], []);
            $validator->errors()->add('error', 'تاریخ پایان نمی‌تواند خالی باشد.');
            return redirect()->back()->withErrors($validator);
        }
        $oldPromoCode = PromoCode::find($id);
        $userIds = $request->userIds;
        $users = collect();
        if (!$userIds) {
            $request->merge([
                'user_id' => null,
            ]);
        } else {
            $userIds = explode(',', $userIds);
            foreach ($userIds as $userId) {
                $users->push(User::query()->findOrFail($userId));
            }
        }
        if (!$request->discount_percent) {
            if (!$request->max_discount) {
                $validator = Validator::make([], []);
                $validator->errors()->add('error', 'درصد نمیتواند صفر باشد.');
                return redirect()->back()->withErrors($validator);
            }
            $request->merge([
                'discount_percent' => 100,
            ]);
        }
        if (!$request->max_discount) {
            $request->merge([
                'max_discount' => null,
            ]);
        } else {
            $request->merge([
                'max_discount' => (int)($request->max_discount),
            ]);
        }

        /** @var Campaign $campaign */
        $campaign = Campaign::query()->findOrFail($campaignId);

        $startAt = Helpers::convertDateTimeToGregorian(Helpers::getEnglishString($request->start_at));
        $expireAt =
            $request->expire_at ? Helpers::convertDateTimeToGregorian(Helpers::getEnglishString($request->expire_at)) :
                null;

        $isHidden = isset($request->is_hidden) and $request->is_hidden == 'on';
        $isUnlimited = isset($request->is_unlimited) and ($request->is_unlimited == 'on');
        $priceId = $request->price_id == 0 ? null : $request->price_id;

        $request->merge([
            'start_at' => str_replace('/', '-', $startAt),
            'expire_at' => str_replace('/', '-', $expireAt),
            'panel_user_id' => auth()->id(),
            'is_hidden' => $isHidden,
            'is_unlimited' => $isUnlimited and $isHidden,
            'price_id' => $priceId,
        ]);

        if (!$userIds) {
            if (!isset($request->code)) {
                $request->merge([
                    'code' => Helpers::generatePromoCode(),
                ]);
            } else {
                $promoCode = PromoCode::query()->where('id', '<>', $id)->where('code', $request->code)->first();
                if ($promoCode) {
                    $validator = Validator::make([], []);
                    $validator->errors()->add('error', 'کد تخفیف تکراری است.');
                    return redirect()->back()->withErrors($validator);
                }
            }
            $promoCode = $campaign->promoCodes()->updateOrCreate([
                'id' => $id,
            ], $request->all());

            if ($id == 0 and !$isHidden) {
                dispatch(
                    (new SendFirebaseNotificationJob([
                        'type' => NotificationType::PROMO_CODE,
                        'promo_code_id' => $promoCode->id,
                    ]))->onQueue('activationSms')
                );
            }
        } else {
            foreach ($users as $user) {
                $code = Helpers::generatePromoCode();
                $request->merge([
                    'code' => $code,
                    'user_id' => $user->id,
                    'max_count' => 1,
                ]);
                $promoCode = $campaign->promoCodes()->updateOrCreate(['id' => $id], $request->all());

                if (!$id and isset($request->template) and $request->template != '') {
                    $this->dispatch((new PromoCodeSmsJob($user->phone_number, $request->template, $code))->onQueue('activationSms'));
                }
                if ($id == 0 and !$isHidden) {
                    dispatch(
                        (new SendFirebaseNotificationJob([
                            'type' => NotificationType::PROMO_CODE,
                            'promo_code_id' => $promoCode->id,
                        ]))->onQueue('activationSms')
                    );
                }
            }
        }

        $campaign->count = $campaign->promoCodes()->count();
        $campaign->save();

        /** @var PanelUser $panelUser */
        $panelUser = auth()->user();
        $type = $id ? LogType::EDIT_PROMO_CODE : LogType::NEW_PROMO_CODE;
        $panelUser->logs()->create([
            'user_id' => ($userIds and sizeof($userIds) == 1) ? $userIds[0] : null,
            'type' => $type,
            'date_time' => now()->toDateTimeString(),
            'description' => LogType::getDescription($type, $panelUser),
            'old_json' => $oldPromoCode,
            'new_json' => ($userIds and sizeof($userIds) > 1) ? json_encode([]) :
                ($id ? PromoCode::find($id) : $promoCode),
        ]);

        return redirect()->route('dashboard.campaignItem', ['id' => $campaignId])->with('success', 'با موفقیت انجام شد');
    }

    public function transactions(Request $request)
    {
        [$startDate, $endDate] = $this->reportController->normalizeDate($request, true);
        if (!$startDate) {
            $startDate = Transaction::query()->selectRaw('min(Date(created_at)) as date')->first()->date;
        }

        $filter = [
            'bank_ids' => $request->input('bank_ids', []),
            'plan_ids' => $request->input('plan_ids', []),
            'types' => $request->input('types', []),
            'states' => $request->input('states', []),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'sort_field' => $request->input('sort_field', 'date'),
            'sort_type' => $request->input('sort_type', 'DESC'),
            'user_id' => $request->input('user_id', null),
            'phone_number' => Helpers::getEnglishString($request->input('phone_number', '')),
        ];

        $transactions = $this->fetchTransactions($filter);

        $transactions = Helpers::paginateCollection($transactions, 100);

        $banks = PremiumBanks::getBanks();
        foreach ($banks as $id => $bank) {
            $banks[$id]['is_selected'] = in_array($bank['id'], $filter['bank_ids']);
        }

        $plans = collect();
        foreach (PremiumPrices::getPrices() as $price) {
            $plans->push([
                'id' => $price['id'],
                'title' => $price['title2'],
                'is_selected' => in_array($price['id'], $filter['plan_ids']),
            ]);
        }

        $states = collect();
        foreach (UserStatusType::toArray() as $item) {
            $states->push([
                'id' => $item,
                'title' => UserStatusType::getEnum($item),
                'is_selected' => in_array($item, $filter['states']),
            ]);
        }

        $types = collect();
        foreach (PurchaseType::toArray() as $item) {
            $types->push([
                'id' => $item,
                'name' => PurchaseType::getEnum($item),
                'is_selected' => in_array($item, $filter['types']),
            ]);
        }

        [$sortableFields, $sortableTypes] = $this->getTransactionSortFields();

        return view('dashboard.management.transactions', [
            'transactions' => $transactions,
            'filter' => $filter,
            'banks' => $banks,
            'plans' => $plans,
            'states' => $states,
            'types' => $types,
            'sortable_fields' => $sortableFields,
            'sortable_types' => $sortableTypes,
        ]);
    }

    private function getTransactionSortFields()
    {
        $sortableFields = [
            'full_name' => 'نام کاربر',
            'phone_number' => 'شماره کاربر',
            'project_name' => 'نام پروژه',
            'date' => 'تاریخ پرداخت',
            'bank_ref' => 'شماره تراکنش بانک',
            'mapsa_ref' => 'شماره تراکنش مپسا',
            'start_date' => 'تاریخ شروع طرح',
            'end_date' => 'تاریخ پایان طرح',
            'user_count' => 'تعداد کاربر',
            'volume_size' => 'مقدار حجم',
            'type' => 'نوع تراکنش',
            'total_amount' => 'مبلغ کل',
            'discount_amount' => 'مقدار تخفیف',
            'added_value_amount' => 'ارزش افزوده',
            'wallet_amount' => 'کیف پول',
            'state' => 'وضعیت',
            'bank' => 'بانک',
        ];

        $sortableTypes = [
            'ASC' => 'صعودی',
            'DESC' => 'نزولی',
        ];

        return [$sortableFields, $sortableTypes];
    }

    public function generateReport()
    {
        Artisan::call('generate:report --user');
        Artisan::call('generate:report --project');

        return redirect()->back()->with('success', 'با موقفیت انجام شد');
    }

    public function promoCodeDelete($id)
    {
        $promoCode = PromoCode::query()->findOrFail($id);
        $promoCode->expire_at = now()->toDateTimeString();
        $promoCode->save();

        $campaignId = $promoCode->campaign_id;

        return redirect()->route('dashboard.campaignItem', ['id' => $campaignId])->with('success', 'با موفقیت انجام شد');
    }

    public function banners()
    {
        $now = "'" . now()->toDateTimeString() . "'";
        $banners = Banner::query()
            ->join('panel_users', 'panel_user_id', '=', 'panel_users.id')
            ->addSelect([
                'panel_users.name as panel_user_name',
                'banners.*',
                \DB::raw("IF(
                                    banners.expire_at <= {$now},
                                    " . BannerStatus::EXPIRED . ",
                                    IF(
                                        banners.start_at > {$now},
                                        " . BannerStatus::NOT_STARTED . ",
                                        " . BannerStatus::ACTIVE . "
                                    )
                                ) as status"
                ),
            ])
            ->orderBy('status')
            ->orderByDesc('banners.updated_at')
            ->get();

        return view('dashboard.management.banners', [
            'banners' => $banners,
        ]);
    }

    public function bannerItem(Request $request, $id)
    {
        $userIds = $request->userIds;
        $user = null;
        if ($userIds) {
            $userIds = explode(',', $userIds);
            if (count($userIds) == 1) {
                $user = User::query()->findOrFail($userIds[0]);
            } else {
                foreach ($userIds as $userId) {
                    User::query()->findOrFail($userId);
                }
            }
            $userIds = implode(',', $userIds);
        }
        if ($id) {
            /** @var Banner $banner */
            $banner = Banner::query()->findOrFail($id);
            $userIds = $banner->user()->pluck('user_id')->toArray();
            if (count($userIds) == 1) {
                $user = User::query()->findOrFail($userIds[0]);
            }
            $userIds = implode(',', $userIds);
        } else {
            $banner = new Banner([
                'expire_at' => now()->addWeek()->endOfDay(),
            ]);
        }

        return view('dashboard.management.bannerItem', [
            'id' => $id,
            'banner' => $banner,
            'user' => $user,
            'userIds' => $userIds,
        ]);
    }

    public function storeBanner(Request $request, $id)
    {
        $userIds = $request->input('userIds', []);
        $users = collect();
        if (!$userIds) {
            $request->merge([
                'user_id' => null,
            ]);
        } else {
            $userIds = explode(',', $userIds);
            foreach ($userIds as $userId) {
                $users->push(User::query()->findOrFail($userId));
            }
        }
        $validator = Validator::make($request->all(), [
            'image' => 'nullable|dimensions:ratio=1/1',
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        $startAt = Helpers::convertDateTimeToGregorian(Helpers::getEnglishString($request->start_at));
        $expireAt =
            $request->expire_at ? Helpers::convertDateTimeToGregorian(Helpers::getEnglishString($request->expire_at)) :
                null;
        $request->merge([
            'panel_user_id' => auth()->id(),
            'start_at' => $startAt,
            'expire_at' => $expireAt,
            'type' => $userIds == [] ? BannerType::PUBLIC : BannerType::PRIVATE,
        ]);

        $oldBanner = Banner::find($id);

        try {
            /** @var Banner $banner */
            $banner = Banner::query()->updateOrCreate([
                'id' => $id,
            ], $request->all());

            if ($userIds) {
                $banner->user()->delete();
            }
            /** @var User $user */
            foreach ($users as $user) {
                $user->banner()->create([
                    'banner_id' => $banner->id,
                ]);
            }

            $image = $request->file('image');
            if ($image) {
                $banner->update([
                    'image_path' => null,
                ]);

                $image->storeAs('/', $path = 'Banner_image' . '.' . $image->getClientOriginalExtension());
                $http = new Client;
                $response = $http->post(
                    env('TANKHAH_URL') . '/panel/' . env('TANKHAH_TOKEN') . '/banner/' . $banner->id . '/image',
                    [
                        'headers' => [
                            'Accept' => 'application/json',
                        ],
                        'multipart' => [
                            [
                                'name' => 'image',
                                'filename' => $image->getClientOriginalName(),
                                'contents' => file_get_contents(storage_path() . '/app/' . $path),
                            ],
                        ],
                    ]
                );
                $response = json_decode($response->getBody());
                $banner->image_path = $response->image_path;
                $banner->save();
                Storage::delete('/' . $path);
            }
        } catch (Exception $exception) {
            dd($exception);
        }

        /** @var PanelUser $panelUser */
        $panelUser = auth()->user();
        $type = $id ? LogType::EDIT_BANNER : LogType::NEW_BANNER;
        $panelUser->logs()->create([
            'user_id' => ($userIds and sizeof($userIds) == 1) ? $userIds[0] : null,
            'type' => $type,
            'date_time' => now()->toDateTimeString(),
            'description' => LogType::getDescription($type, $panelUser),
            'old_json' => $oldBanner,
            'new_json' => $banner,
        ]);

        return redirect()->route('dashboard.banners')->with('success', 'با موفقیت انجام شد');
    }

    public function deleteBanner($id)
    {
        $banner = Banner::query()->findOrFail($id);
        $banner->update([
            'expire_at' => now()->toDateTimeString(),
        ]);

        /** @var PanelUser $panelUser */
        $panelUser = auth()->user();
        $type = LogType::DELETE_BANNER;
        $panelUser->logs()->create([
            'user_id' => null,
            'type' => $type,
            'date_time' => now()->toDateTimeString(),
            'description' => LogType::getDescription($type, $panelUser),
            'old_json' => $banner,
            'new_json' => Banner::query()->findOrFail($id),
        ]);

        return redirect()->route('dashboard.banners')->with('success', 'با موفقیت انجام شد');
    }

    public function premiumReport(Request $request)
    {
        $type = $request->input('type', PremiumReportType::DAILY);

        $items = UserStatusLog::query()
            ->without('transaction')
            ->orderBy('date', 'desc')
            ->groupBy('date')
            ->select([
                \DB::raw('date(updated_at) as date'),
                \DB::raw('sum(IF(status = 1, 1, 0)) as successful_count'),
                \DB::raw('sum(IF(status = 0, 1, 0)) as unsuccessful_count'),
                \DB::raw('sum(IF(status = 1, 1, 0) * (total_amount + added_value_amount)) as successful_amount_pure'),
                \DB::raw('sum(IF(status = 0, 1, 0) * (total_amount + added_value_amount)) as unsuccessful_amount'),
                \DB::raw('sum(IF(status = 1, 1, 0) * (total_amount + added_value_amount - wallet_amount - discount_amount)) as successful_amount'),
                \DB::raw('sum(IF(status = 1, 1, 0) * IF(price_id = ' . PremiumDuration::YEAR . ', 1, 0)) as successful_year_count'),
                \DB::raw('sum(IF(status = 1, 1, 0) * IF(price_id = ' . PremiumDuration::MONTH . ', 1, 0)) as successful_month_count'),
                \DB::raw('sum(IF(status = 1, 1, 0) * IF(price_id = ' . PremiumDuration::HALF_MONTH . ', 1, 0)) as successful_half_month_count'),
                \DB::raw('sum(IF(status = 0, 1, 0) * IF(price_id = ' . PremiumDuration::YEAR . ', 1, 0)) as unsuccessful_year_count'),
                \DB::raw('sum(IF(status = 0, 1, 0) * IF(price_id = ' . PremiumDuration::MONTH . ', 1, 0)) as unsuccessful_month_count'),
                \DB::raw('sum(IF(status = 0, 1, 0) * IF(price_id = ' . PremiumDuration::HALF_MONTH . ', 1, 0)) as unsuccessful_half_month_count'),
                \DB::raw('sum(IF(status = 1, 1, 0) * IF(type = ' . PurchaseType::NEW . ', 1, 0)) as successful_new_count'),
                \DB::raw('sum(IF(status = 1, 1, 0) * IF(type = ' . PurchaseType::UPGRADE . ', 1, 0)) as successful_upgrade_count'),
                \DB::raw('sum(IF(status = 1, 1, 0) * IF(type = ' . PurchaseType::EXTEND . ', 1, 0)) as successful_extend_count'),
                \DB::raw('sum(IF(status = 0, 1, 0) * IF(type = ' . PurchaseType::NEW . ', 1, 0)) as unsuccessful_new_count'),
                \DB::raw('sum(IF(status = 0, 1, 0) * IF(type = ' . PurchaseType::UPGRADE . ', 1, 0)) as unsuccessful_upgrade_count'),
                \DB::raw('sum(IF(status = 0, 1, 0) * IF(type = ' . PurchaseType::EXTEND . ', 1, 0)) as unsuccessful_extend_count'),
            ]);

        $items = $items->get();

        if ($type == PremiumReportType::FULL) {
            $item = [];
            foreach ($items->toArray()[0] as $key => $value) {
                if (is_numeric($value)) {
                    $item[$key] = $items->sum($key);
                }
            }
            $items = [$item];
        } elseif ($type == PremiumReportType::MONTHLY) {
            $gItems = $items->groupBy(function ($item) {
                [$year, $month, $day] = explode('/', Helpers::gregorianDateStringToJalali($item['date']));
                return $year . '-' . $month;
            });
            $oldItem = $items->toArray()[0];
            $items = [];
            foreach ($gItems as $date => $gItem) {
                [$year, $month] = explode('-', $date);
                $item = [
                    'date' => Helpers::getMonthName($month) . ' ' . $year,
                ];
                foreach ($oldItem as $key => $value) {
                    if (is_numeric($value)) {
                        $item[$key] = $gItem->sum($key);
                    }
                }
                $items[] = $item;
            }
        } elseif ($type == PremiumReportType::DAILY) {
            $items = $items->map(function ($item) {
                $item['date'] = Helpers::gregorianDateStringToJalali($item['date']);
                return $item;
            });
            $items = Helpers::paginateCollection($items, 100);
        }

        return view('dashboard.management.premiumReport', [
            'type' => $type,
            'items' => $items,
        ]);
    }

    public function logCenters(Request $request)
    {
        $filter = [
            'user_id' => $request->input('user_id', null),
            'panel_user_ids' => $request->input('panel_user_ids', []),
            'types' => $request->input('types', []),
        ];

        $logs = PanelLogCenter::query()
            ->leftJoin('user_reports', 'user_reports.id', 'log_centers.user_id')
            ->leftJoin('users', 'users.id', 'log_centers.panel_user_id')
            ->where(function ($query) use ($filter) {
                if (!empty($filter['panel_user_ids'])) {
                    $query->whereIn('user_id', $filter['panel_user_ids']);
                }
            })->where(function ($query) use ($filter) {
                if (!empty($filter['types'])) {
                    $query->whereIn('log_centers.type', $filter['types']);
                }
            })
            ->where(function ($query) use ($filter) {
                if ($filter['user_id']) {
                    $query->where('user_id', $filter['user_id'])
                        ->orWhereNull('user_id');
                }
            })
            ->orderBy('date_time', 'desc')
            ->get([
                'user_reports.phone_number',
                'user_reports.name',
                'users.name as panel_username',
                'log_centers.*',
            ]);

        $logs->transform(function ($log) {
            $log['username'] = $log['user_id'] ?
                (!empty(trim($log['name'])) ? $log['name'] : $log['phone_number']) :
                'عمومی';
            return $log;
        });

        $users = PanelUser::query()->get()->map(function ($user) use ($filter) {
            $user['is_selected'] = in_array($user->id, $filter['panel_user_ids']);
            return $user;
        });

        $types = Collect(LogType::toArray())->map(function ($item) use ($filter) {
            $type = [
                'id' => $item,
                'title' => LogType::getTitle($item),
                'is_selected' => in_array($item, $filter['types']),
            ];
            return $type;
        });

        return view('dashboard.management.logCenters', [
            'logs' => $logs,
            'users' => $users,
            'types' => $types,
        ]);
    }

    public function logCenterItem($id)
    {
        $log = PanelLogCenter::query()
            ->leftJoin('user_reports', 'user_reports.id', 'log_centers.user_id')
            ->leftJoin('users', 'users.id', 'log_centers.panel_user_id')
            ->select([
                'user_reports.phone_number',
                'user_reports.name',
                'users.name as panel_username',
                'log_centers.*',
            ])->findOrFail($id);

        $log['username'] = $log['user_id'] ?
            (!empty(trim($log['name'])) ? $log['name'] : $log['phone_number']) :
            'عمومی';

        return view('dashboard.management.logCenterItem', [
            'log' => $log,
        ]);
    }
}
