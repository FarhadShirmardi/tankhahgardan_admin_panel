<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Helpers;
use App\Campaign;
use Validator;
use App\PromoCode;
use App\User;
use App\ProjectStatusLog;
use App\Constants\ProjectStatusType;
use App\Constants\PremiumBanks;
use App\Constants\PremiumPrices;
use App\Transaction;
use App\Constants\PurchaseType;
use App\Helpers\UtilHelpers;
use App\Jobs\PromoCodeSmsJob;
use App\SmsLog;
use App\Banner;
use GuzzleHttp\Client;
use Storage;
use Exception;
use Artisan;

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
                'panel_users.name as panel_user_name'
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
                'panel_users.name as panel_user_name'
            ]);
        $campaigns = $campaigns->map(function ($item) {
            $item['start_date'] = $item['start_date'] ? Helpers::convertDateTimeToJalali($item['start_date']) : ' - ';
            $item['end_date'] = $item['end_date'] ? Helpers::convertDateTimeToJalali($item['end_date']) : ' - ';
            return $item;
        });
        return view('dashboard.management.campaigns', [
            'campaigns' => $campaigns,
            'userIds' => implode(',', $userIds)
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
                    'users.phone_number'
                ])
                ->get();
            $promoCodes = $promoCodes->map(function ($item) {
                $item['start_at'] = $item['start_at'] ? Helpers::convertDateTimeToJalali($item['start_at']) : ' - ';
                $item['expire_at'] = $item['expire_at'] ? Helpers::convertDateTimeToJalali($item['expire_at']) : ' - ';
                return $item;
            });
        }

        return view('dashboard.management.campaign_item', [
            'campaign' => $campaign,
            'promoCodes' => $promoCodes,
            'id' => $id
        ]);
    }

    private function usedPromoCodeQuery()
    {
        return ProjectStatusLog::query()
            ->where('status', ProjectStatusType::SUCCEED)
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
            'panel_user_id' => auth()->id()
        ]);

        Campaign::query()->updateOrCreate([
            'id' => $id
        ], $request->all());

        return redirect()->route('dashboard.campaigns')->with('success', 'با موفقیت انجام شد');
    }

    public function campaignDelete($id)
    {
        $campaign = Campaign::query()->findOrFail($id);
        PromoCode::query()->where('campaign_id', $id)
            ->update([
                'expire_at' => now()->toDateTimeString()
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
            ->addSelect([
                'promo_codes.*',
                'panel_users.name as panel_user_name',
                'users.phone_number'
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
            'user' => $user
        ]);
    }

    public function promoCodeItem(Request $request, $campaignId, $id)
    {
        $userIds = $request->userIds;
        /** @var Campaign $campaign */
        $campaign = Campaign::query()->findOrFail($campaignId);
        $promoCode = collect();
        $promoCode['code'] = UtilHelpers::generatePromoCode();
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
            'transactions' => $transactions
        ]);
    }

    public function fetchTransactions($filter)
    {
        $transactions = ProjectStatusLog::query()
            ->leftJoin('transactions', 'transactions.id', 'project_status_logs.transaction_id')
            ->join('project_user', function ($join) {
                $join->on('project_status_logs.project_id', 'project_user.project_id')
                    ->whereNull('project_user.deleted_at')
                    ->where('project_user.is_owner', true);
            })
            ->join('users', 'users.id', 'project_user.user_id')
            ->join('projects', 'projects.id', 'project_user.project_id')
            ->where(function ($query) use ($filter) {
                if (isset($filter['bank_ids']) and $filter['bank_ids'] != []) {
                    $query->whereIn('transactions.bank_id', $filter['bank_ids']);
                }
            })
            ->where(function ($query) use ($filter) {
                if (isset($filter['types']) and $filter['types'] != []) {
                    $query->whereIn('project_status_logs.type', $filter['types']);
                }
            })
            ->where(function ($query) use ($filter) {
                if (isset($filter['states']) and $filter['states'] != []) {
                    $query->whereIn('project_status_logs.status', $filter['states']);
                }
            })
            ->where(function ($query) use ($filter) {
                if (isset($filter['plan_ids']) and $filter['plan_ids'] != []) {
                    $query->whereIn('project_status_logs.price_id', $filter['plan_ids']);
                }
            })
            ->where(function ($query) use ($filter) {
                if (isset($filter['promo_code_id'])) {
                    $query->where('project_status_logs.promo_code_id', $filter['promo_code_id']);
                }
            })
            ->when(!empty($filter['phone_number']), function ($query) use ($filter) {
                $phoneNumber = ltrim(Helpers::getEnglishString($filter['phone_number']), '0');
                $phoneNumber = '%' . $phoneNumber . '%';
                return $query->having('phone_number', 'like', $phoneNumber);
            })
            ->where(function ($query) use ($filter) {
                if (isset($filter['start_date'])) {
                    $query->whereDate('transactions.created_at', '>=', $filter['start_date']);
                }
                if (isset($filter['end_date'])) {
                    $query->whereDate('transactions.created_at', '<=', $filter['end_date']);
                }
            })
            ->where(function ($query) use ($filter) {
                if (isset($filter['user_id'])) {
                    $query->where('users.id', $filter['user_id']);
                }
            })
            ->orderBy($filter['sort_field'], $filter['sort_type'])
            ->selectRaw("IFNULL(transactions.created_at, project_status_logs.created_at) as date")
            ->addSelect([
                'project_status_logs.project_id',
                'projects.name as project_name',
                'users.name as user_name',
                'users.family as user_family',
                'users.phone_number',
                'project_status_logs.price_id as plan_id',
                'project_status_logs.start_date as start_date',
                'project_status_logs.end_date as end_date',
                'project_status_logs.trace_number as mapsa_ref',
                'transactions.trace_no as bank_ref',
                'project_status_logs.type',
                'project_status_logs.user_count',
                'project_status_logs.volume_size',
                'project_status_logs.status as state',
                'transactions.bank_id as bank',
                'project_status_logs.wallet_amount',
                'project_status_logs.total_amount',
                'project_status_logs.discount_amount',
                'project_status_logs.added_value_amount',
            ])->get();

        $transactions = $transactions->map(function ($item) {
            $item->full_name = ($item->user_name or $item->user_family) ?
                $item->user_name . ' ' . $item->user_family : ' - ';
            $item->date = $item->date ? Helpers::convertDateTimeToJalali($item->date) : '-';
            $item->start_date = Helpers::convertDateTimeToJalali($item->start_date);
            $item->end_date = Helpers::convertDateTimeToJalali($item->end_date);
            $item->state = ProjectStatusType::getEnum($item->state);
            $item->bank = PremiumBanks::getBank($item->bank)['name'];
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
        $userIds = $request->userIds;
        $users = collect();
        if (!$userIds) {
            $request->merge([
                'user_id' => null
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
                'discount_percent' => 100
            ]);
        }
        if (!$request->max_discount) {
            $request->merge([
                'max_discount' => null
            ]);
        } else {
            $request->merge([
                'max_discount' => (int)($request->max_discount)
            ]);
        }

        /** @var Campaign $campaign */
        $campaign = Campaign::query()->findOrFail($campaignId);

        $startAt = Helpers::convertDateTimeToGregorian(Helpers::getEnglishString($request->start_at));
        $expireAt = $request->expire_at ? Helpers::convertDateTimeToGregorian(Helpers::getEnglishString($request->expire_at)) : null;

        $request->merge([
            'start_at' => str_replace('/', '-', $startAt),
            'expire_at' => str_replace('/', '-', $expireAt),
            'panel_user_id' => auth()->id()
        ]);

        if (!$userIds) {
            if (!isset($request->code)) {
                $request->merge([
                    'code' => UtilHelpers::generatePromoCode()
                ]);
            } else {
                $promoCode = PromoCode::query()->where('id', '<>', $id)->where('code', $request->code)->first();
                if ($promoCode) {
                    $validator = Validator::make([], []);
                    $validator->errors()->add('error', 'کد تخفیف تکراری است.');
                    return redirect()->back()->withErrors($validator);
                }
            }
            $campaign->promoCodes()->updateOrCreate([
                'id' => $id
            ], $request->all());
        } else {
            foreach ($users as $user) {
                $code = UtilHelpers::generatePromoCode();
                $request->merge([
                    'code' => $code,
                    'user_id' => $user->id,
                    'max_count' => 1
                ]);
                $campaign->promoCodes()->firstOrCreate(['id' => $id], $request->all());

                if (!$id and isset($request->template) and $request->template != '') {
                    $this->dispatch((new PromoCodeSmsJob($user->full_phone_number, $request->template, $code))->onQueue('activationSms'));
                    SmsLog::query()->create([
                        'user_id' => $user->id,
                        'phone_number' => $user->full_phone_number,
                        'type' => PromoCode::class,
                        'text' => $request->template . ' - ' . $code
                    ]);
                }
            }
        }

        $campaign->count = $campaign->promoCodes()->count();
        $campaign->save();

        return redirect()->route('dashboard.campaignItem', ['id' => $campaignId])->with('success', 'با موفقیت انجام شد');
    }

    public function transactions(Request $request)
    {
        list($startDate, $endDate) = $this->reportController->normalizeDate($request, true);
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
                'is_selected' => in_array($price['id'], $filter['plan_ids'])
            ]);
        }

        $states = collect();
        foreach (ProjectStatusType::toArray() as $item) {
            $states->push([
                'id' => $item,
                'title' => ProjectStatusType::getEnum($item),
                'is_selected' => in_array($item, $filter['states'])
            ]);
        }

        $types = collect();
        foreach (PurchaseType::toArray() as $item) {
            $types->push([
                'id' => $item,
                'name' => PurchaseType::getEnum($item),
                'is_selected' => in_array($item, $filter['types'])
            ]);
        }

        list($sortableFields, $sortableTypes) = $this->getTransactionSortFields();

        return view('dashboard.management.transactions', [
            'transactions' => $transactions,
            'filter' => $filter,
            'banks' => $banks,
            'plans' => $plans,
            'states' => $states,
            'types' => $types,
            'sortable_fields' => $sortableFields,
            'sortable_types' => $sortableTypes
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
            'payable_amount' => 'مبلغ قابل پرداخت',
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
        $banners = Banner::query()
            ->join('panel_users', 'panel_user_id', '=', 'panel_users.id')
            ->addSelect([
                'panel_users.name as panel_user_name',
                'banners.*'
            ])
            ->orderByDesc('banners.updated_at')
            ->get();

        return view('dashboard.management.banners', [
            'banners' => $banners
        ]);
    }

    public function bannerItem($id)
    {
        if ($id) {
            /** @var Banner $banner */
            $banner = Banner::query()->findOrFail($id);
        } else {
            $banner = new Banner([
                'is_active' => true
            ]);
        }

        return view('dashboard.management.bannerItem', [
            'id' => $id,
            'banner' => $banner,
        ]);
    }

    public function storeBanner(Request $request, $id)
    {
        $request->merge([
            'panel_user_id' => auth()->id(),
            'is_active' => isset($request->is_active) && $request->is_active == 'on'
        ]);

        try {
            /** @var Banner $banner */
            $banner = Banner::query()->updateOrCreate([
                'id' => $id
            ], $request->all());

            $id = $banner->id;

            $image = $request->file('image');
            if ($image) {
                $banner->update([
                    'image_path' => null,
                ]);

                $image->storeAs('/', $path = 'Banner_image' . '.' . $image->getClientOriginalExtension());
                $http = new Client;
                $response = $http->post(
                    env('MAPSA_URL') . '/panel/' . env('MAPSA_TOKEN') . '/banner/' . $id . '/image',
                    [
                        'headers' => [
                            'Accept' => 'application/json',
                        ],
                        'multipart' => [
                            [
                                'name' => 'image',
                                'filename' => $image->getClientOriginalName(),
                                'contents' => file_get_contents(storage_path() . '/app/' . $path)
                            ]
                        ]
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

        return redirect()->route('dashboard.banners')->with('success', 'با موفقیت انجام شد');
    }
}
