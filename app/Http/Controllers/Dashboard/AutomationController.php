<?php

namespace App\Http\Controllers\Dashboard;

use App\AutomationData;
use App\AutomationMetric;
use App\Constants\LogType;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Jobs\AutomationMetricExportJob;
use App\PanelUser;
use App\User;
use Illuminate\Http\Request;

class AutomationController extends Controller
{
    public $typeMapping = [
        -1 => [
            'title' => 'قبل از اتوماسیون',
            'type' => 'none',
        ],
        -2 => [
            'title' => '۱۰ روز اخیر داده نزده و بالای ۲۰ تراکنش ثبت کرده است.',
            'type' => 'call',
        ],
        -3 => [
            'title' => '۱۰ روز اخیر داده نزده و بین ۵ تا ۲۰ تراکنش ثبت کرده است.',
            'type' => 'sms',
        ],
        -4 => [
            'title' => '۱۰ روز اخیر داده نزده و زیر ۵ تراکنش ثبت کرده است.',
            'type' => 'none',
        ],
        -5 => [
            'title' => '۱۰ روز اخیر داده زده و پولی است.',
            'type' => 'none',
        ],
        -6 => [
            'title' => '۱۰ روز اخیر داده زده و پولی نیست و زیر ۲۰ تراکنش دارد.',
            'type' => 'none',
        ],
        -7 => [
            'title' => '۱۰ روز اخیر داده زده و پولی نیست و بالای ۲۰ تراکنش دارد.',
            'type' => 'sms',
        ],
        1 => [
            'title' => '0-1 روز از تاریخ ثبت‌نام گذشته',
            'type' => 'none',
        ],
        2 => [
            'title' => '1-3 روز از تاریخ ثبت‌نام گذشته',
            'type' => 'sms',
        ],
        3 => [
            'title' => '3-9 روز از تاریخ ثبت‌نام گذشته و کاربر تراکنش ثبت کرده است.',
            'type' => 'none',
        ],
        4 => [
            'title' => '3-9 روز از تاریخ ثبت‌نام گذشته و کاربر تراکنش ثبت نکرده است.',
            'type' => 'sms',
        ],
        5 => [
            'title' => '9-18 روز از تاریخ ثبت‌نام گذشته و کاربر تراکنش ثبت کرده است.',
            'type' => 'none',
        ],
        6 => [
            'title' => '9-18 روز از تاریخ ثبت‌نام گذشته و کاربر تراکنش ثبت نکرده است.',
            'type' => 'sms',
        ],
        7 => [
            'title' => '18-30 روز از تاریخ ثبت‌نام گذشته و کاربر بیشتر مساوی ۱۰ تراکنش ثبت کرده و الان پولی نیست.',
            'type' => 'sms',
        ],
        8 => [
            'title' => '18-30 روز از تاریخ ثبت‌نام گذشته و کاربر بیشتر مساوی ۱۰ تراکنش ثبت کرده و الان پولی است.',
            'type' => 'none',
        ],
        9 => [
            'title' => '18-30 روز از تاریخ ثبت‌نام گذشته و کاربر بین ۵ تا ۹ تراکنش ثبت کرده است.',
            'type' => 'call',
        ],
        10 => [
            'title' => '18-30 روز از تاریخ ثبت‌نام گذشته و کاربر بین ۱ تا ۴ تراکنش ثبت کرده است.',
            'type' => 'sms',
        ],
        11 => [
            'title' => '18-30 روز از تاریخ ثبت‌نام گذشته و کاربر تراکنش ثبت نکرده است.',
            'type' => 'none',
        ],
        12 => [
            'title' => '30-45 روز از تاریخ ثبت‌نام گذشته و کاربر در ۱۰ روز اخیر تراکنش ثبت کرده و بالای ۲۵ تراکنش دارد و پولی نیست.',
            'type' => 'sms',
        ],
        13 => [
            'title' => '30-45 روز از تاریخ ثبت‌نام گذشته و کاربر در ۱۰ روز اخیر تراکنش ثبت کرده و بالای ۲۵ تراکنش دارد و پولی است.',
            'type' => 'none',
        ],
        14 => [
            'title' => '30-45 روز از تاریخ ثبت‌نام گذشته و کاربر در ۱۰ روز اخیر تراکنش ثبت کرده و کمتر ۲۵ تراکنش دارد.',
            'type' => 'none',
        ],
        15 => [
            'title' => '30-45 روز از تاریخ ثبت‌نام گذشته و کاربر در ۱۰ روز اخیر تراکنش ثبت نکرده و بیشتر مساوی ۱۰ تراکنش دارد.',
            'type' => 'call',
        ],
        16 => [
            'title' => '30-45 روز از تاریخ ثبت‌نام گذشته و کاربر در ۱۰ روز اخیر تراکنش ثبت نکرده و کمتر از ۱۰ تراکنش دارد.',
            'type' => 'none',
        ],
        17 => [
            'title' => '45-60 روز از تاریخ ثبت‌نام گذشته و کاربر در ۱۰ روز اخیر تراکنش ثبت کرده و بیشتر مساوی ۳۵ تراکنش دارد و پولی نیست.',
            'type' => 'sms',
        ],
        18 => [
            'title' => '45-60 روز از تاریخ ثبت‌نام گذشته و کاربر در ۱۰ روز اخیر تراکنش ثبت کرده و بیشتر مساوی ۳۵ تراکنش دارد و پولی است.',
            'type' => 'none',
        ],
        19 => [
            'title' => '45-60 روز از تاریخ ثبت‌نام گذشته و کاربر در ۱۰ روز اخیر تراکنش ثبت کرده و کمتر از ۳۵ تراکنش دارد.',
            'type' => 'none',
        ],
        20 => [
            'title' => '45-60 روز از تاریخ ثبت‌نام گذشته و کاربر در ۱۰ روز اخیر تراکنش ثبت نکرده و بیشتر مساوی ۲۰ تراکنش دارد.',
            'type' => 'call',
        ],
        21 => [
            'title' => '45-60 روز از تاریخ ثبت‌نام گذشته و کاربر در ۱۰ روز اخیر تراکنش ثبت نکرده و کمتر از ۲۰ تراکنش دارد.',
            'type' => 'none',
        ],
        22 => [
            'title' => 'بیش از ۶۰ روز از تاریخ ثبت‌نام گذشته و کاربر در ۱۰ روز اخیر تراکنش ثبت کرده و پولی نیست ولی قبلا پولی بوده است.',
            'type' => 'sms',
        ],
        23 => [
            'title' => 'بیش از ۶۰ روز از تاریخ ثبت‌نام گذشته و کاربر در ۱۰ روز اخیر تراکنش ثبت کرده و هیچوقت پولی نبوده و بیشتر مساوی ۵۰ تراکنش ثبت کرده است.',
            'type' => 'sms',
        ],
        24 => [
            'title' => 'بیش از ۶۰ روز از تاریخ ثبت‌نام گذشته و کاربر در ۱۰ روز اخیر تراکنش ثبت کرده و هیچوقت پولی نبوده و کمتر از ۵۰ تراکنش ثبت کرده است.',
            'type' => 'none',
        ],
        25 => [
            'title' => 'بیش از ۶۰ روز از تاریخ ثبت‌نام گذشته و کاربر در ۱۰ روز اخیر تراکنش ثبت کرده و پولی است.',
            'type' => 'none',
        ],
        26 => [
            'title' => 'بیش از ۶۰ روز از تاریخ ثبت‌نام گذشته و کاربر در ۱۰ روز اخیر تراکنش ثبت نکرده و بیشتر مساوی ۲۰ تراکنش ثبت کرده است.',
            'type' => 'call',
        ],
        27 => [
            'title' => 'بیش از ۶۰ روز از تاریخ ثبت‌نام گذشته و کاربر در ۱۰ روز اخیر تراکنش ثبت نکرده و کمتر از ۲۰ تراکنش ثبت کرده است.',
            'type' => 'none',
        ],
        28 => [
            'title' => 'کاربر سوخته!',
            'type' => 'none',
        ],
    ];


    private function convertSelectedDates($dates)
    {
        $result = [];
        foreach ($dates as $date) {
            try {
                if ($date) {
                    $date = Helpers::jalaliDateStringToGregorian(Helpers::getEnglishString($date));
                }
            } catch (\Exception $exception) {

            }
            $date = $date ? str_replace('/', '-', $date) : null;
            if ($date) {
                $result[] = $date;
            }
        }
        return $result;
    }

    private function getMetricsFilter($request)
    {
        $reportController = app()->make(ReportController::class);
        [$startDate, $endDate] = $reportController->normalizeDate($request, true);
        if (!$startDate) {
            $startDate = AutomationMetric::query()->min('date');
        }
        $filter = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'selected_dates' => $this->convertSelectedDates($request->input('selected_dates', [])),
        ];
        return $filter;
    }

    public function getMetricsItems($filter)
    {
        $selectedDates = $filter['selected_dates'];
        $metrics = AutomationMetric::query()
            ->where(function ($query) use ($filter) {
                if ($filter['start_date']) {
                    $query->where('date', '>=', $filter['start_date']);
                }
            })
            ->where(function ($query) use ($filter) {
                if ($filter['end_date']) {
                    $query->where('date', '<=', $filter['end_date']);
                }
            })
            ->where(function ($query) use ($selectedDates) {
                if (!empty($selectedDates)) {
                    $query->whereIn('date', $selectedDates);
                }
            })
            ->orderBy('date', 'desc')
            ->get();

        $metrics = $metrics->map(function ($item) {
            $item['date'] = Helpers::gregorianDateStringToJalali($item['date']);
            return $item;
        });
        return $metrics;
    }

    public function exportMetrics(Request $request)
    {
        $filter = $this->getMetricsFilter($request);

        $this->dispatch((new AutomationMetricExportJob($filter))->onQueue('activationSms'));

        return redirect()->route('dashboard.downloadCenter');
    }

    public function metrics(Request $request)
    {
        $filter = $this->getMetricsFilter($request);

        $metrics = $this->getMetricsItems($filter);
        $metrics = Helpers::paginateCollection($metrics, 100);

        $metricKeys = [];
        if ($metrics->count()) {
            $metricKeys = array_keys($metrics[0]['metric']);
        }
        return view('dashboard.automation.metrics', [
            'metrics' => $metrics,
            'metricKeys' => $metricKeys,
            'filter' => $filter,
        ]);
    }

    public function typeList()
    {
        $types = AutomationData::groupBy('automation_state')
            ->get([
                'automation_state',
                \DB::raw('count(*) as c'),
            ]);
        return view('dashboard.automation.typeList', [
            'types' => $types,
            'mappings' => $this->typeMapping,
        ]);
    }

    public function typeItem(Request $request, $type)
    {
        $automationData = AutomationData::query()
            ->where('automation_state', $type)
            ->orderBy('transaction_count', 'desc')
            ->paginate();

        return view('dashboard.automation.typeItem', [
            'type' => $type,
            'mappings' => $this->typeMapping,
            'items' => $automationData,
        ]);
    }

    public function callLogs($id)
    {
        $user = User::query()->findOrFail($id);
        $userState = $user->automationData()->first();
        $messages = $user->automationSms()->orderBy('sent_time', 'desc')->get();
        $calls = $user->automationCall()->orderBy('call_time', 'desc')->get();
        $burn = $user->automationBurn()->first();
        return view('dashboard.automation.callLogs', [
            'user' => $user,
            'burn' => $burn,
            'userState' => $userState,
            'typeMappings' => $this->typeMapping,
            'userMessages' => $messages,
            'calls' => $calls,
        ]);
    }

    public function newCallView($userId, $id)
    {
        $user = User::query()->findOrFail($userId);
        $call = $user->automationCall()->find($id);

        return view('dashboard.automation.newCallLogs', [
            'user' => $user,
            'id' => $id,
            'call' => $call,
        ]);
    }

    public function newCall(Request $request, $userId, $id)
    {
        $user = User::query()->findOrFail($userId);
        $data = [
            'text' => $request->text,
            'type' => $user->automationData()->first()->automation_state,
        ];
        if (!$id) {
            $data['call_time'] = now()->toDateTimeString();
        }
        $oldCall = $user->automationCall()->find($id);
        $call = $user->automationCall()->updateOrCreate([
            'id' => $id,
        ], $data);

        /** @var PanelUser $panelUser */
        $panelUser = auth()->user();
        $type = $id ? LogType::EDIT_AUTOMATION_CALL : LogType::NEW_AUTOMATION_CALL;
        $panelUser->logs()->create([
            'user_id' => $userId,
            'type' => $type,
            'date_time' => now()->toDateTimeString(),
            'description' => LogType::getDescription($type, $panelUser),
            'old_json' => $oldCall,
            'new_json' => $call,
        ]);

        return redirect()->route('dashboard.automation.callLogs', ['id' => $userId]);
    }

    public function burnUser(Request $request, $id)
    {
        $user = User::query()->findOrFail($id);
        $burn = $user->automationBurn()->first();
        $user->automationBurn()->updateOrCreate([
            'id' => $burn ? $burn->id : 0,
        ], [
            'text' => $request->text,
            'date' => $burn ? $burn->date : now()->toDateTimeString(),
        ]);
        if ($user->automationData()->first()->automation_state == 26) {
            $user->automationData()->update([
                'automation_state' => 28,
            ]);
        }
        /** @var PanelUser $panelUser */
        $panelUser = auth()->user();
        $panelUser->logs()->create([
            'user_id' => $user->id,
            'type' => LogType::BURN_USER,
            'date_time' => now()->toDateTimeString(),
            'description' => LogType::getDescription(LogType::BURN_USER, $panelUser),
            'new_json' => $user->automationBurn()->first(),
        ]);
        return redirect()->back()->with('success', 'با موفقیت انجام شد.');
    }
}
