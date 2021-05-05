<?php

namespace App\Http\Controllers\Dashboard;

use App\AutomationData;
use App\AutomationMetric;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class AutomationController extends Controller
{
    public $typeMapping = [
        -1 => 'قبل از اتوماسیون',
        1 => '0-1 روز از تاریخ ثبت‌نام گذشته',
        2 => '1-3 روز از تاریخ ثبت‌نام گذشته',
        3 => '3-9 روز از تاریخ ثبت‌نام گذشته و کاربر تراکنش ثبت کرده است.',
        4 => '3-9 روز از تاریخ ثبت‌نام گذشته و کاربر تراکنش ثبت نکرده است.',
        5 => '9-18 روز از تاریخ ثبت‌نام گذشته و کاربر تراکنش ثبت کرده است.',
        6 => '9-18 روز از تاریخ ثبت‌نام گذشته و کاربر تراکنش ثبت نکرده است.',
        7 => '18-30 روز از تاریخ ثبت‌نام گذشته و کاربر بیشتر مساوی ۱۰ تراکنش ثبت کرده و الان پولی نیست.',
        8 => '18-30 روز از تاریخ ثبت‌نام گذشته و کاربر بیشتر مساوی ۱۰ تراکنش ثبت کرده و الان پولی است.',
        9 => '18-30 روز از تاریخ ثبت‌نام گذشته و کاربر بین ۵ تا ۹ تراکنش ثبت کرده است.',
        10 => '18-30 روز از تاریخ ثبت‌نام گذشته و کاربر بین ۱ تا ۴ تراکنش ثبت کرده است.',
        11 => '18-30 روز از تاریخ ثبت‌نام گذشته و کاربر تراکنش ثبت نکرده است.',
        12 => '30-45 روز از تاریخ ثبت‌نام گذشته و کاربر در ۱۰ روز اخیر تراکنش ثبت کرده و بالای ۲۵ تراکنش دارد و پولی نیست.',
        13 => '30-45 روز از تاریخ ثبت‌نام گذشته و کاربر در ۱۰ روز اخیر تراکنش ثبت کرده و بالای ۲۵ تراکنش دارد و پولی است.',
        14 => '30-45 روز از تاریخ ثبت‌نام گذشته و کاربر در ۱۰ روز اخیر تراکنش ثبت کرده و کمتر ۲۵ تراکنش دارد.',
        15 => '30-45 روز از تاریخ ثبت‌نام گذشته و کاربر در ۱۰ روز اخیر تراکنش ثبت نکرده و بیشتر مساوی ۱۰ تراکنش دارد.',
        16 => '30-45 روز از تاریخ ثبت‌نام گذشته و کاربر در ۱۰ روز اخیر تراکنش ثبت نکرده و کمتر از ۱۰ تراکنش دارد.',
        17 => '45-60 روز از تاریخ ثبت‌نام گذشته و کاربر در ۱۰ روز اخیر تراکنش ثبت کرده و بیشتر مساوی ۳۵ تراکنش دارد و پولی نیست.',
        18 => '45-60 روز از تاریخ ثبت‌نام گذشته و کاربر در ۱۰ روز اخیر تراکنش ثبت کرده و بیشتر مساوی ۳۵ تراکنش دارد و پولی است.',
        19 => '45-60 روز از تاریخ ثبت‌نام گذشته و کاربر در ۱۰ روز اخیر تراکنش ثبت کرده و کمتر از ۳۵ تراکنش دارد.',
        20 => '45-60 روز از تاریخ ثبت‌نام گذشته و کاربر در ۱۰ روز اخیر تراکنش ثبت نکرده و بیشتر مساوی ۲۰ تراکنش دارد.',
        21 => '45-60 روز از تاریخ ثبت‌نام گذشته و کاربر در ۱۰ روز اخیر تراکنش ثبت نکرده و کمتر از ۲۰ تراکنش دارد.',
        22 => 'بیش از ۶۰ روز از تاریخ ثبت‌نام گذشته و کاربر در ۱۰ روز اخیر تراکنش ثبت کرده و پولی نیست ولی قبلا پولی بوده است.',
        23 => 'بیش از ۶۰ روز از تاریخ ثبت‌نام گذشته و کاربر در ۱۰ روز اخیر تراکنش ثبت کرده و هیچوقت پولی نبوده و بیشتر مساوی ۵۰ تراکنش ثبت کرده است.',
        24 => 'بیش از ۶۰ روز از تاریخ ثبت‌نام گذشته و کاربر در ۱۰ روز اخیر تراکنش ثبت کرده و هیچوقت پولی نبوده و کمتر از ۵۰ تراکنش ثبت کرده است.',
        25 => 'بیش از ۶۰ روز از تاریخ ثبت‌نام گذشته و کاربر در ۱۰ روز اخیر تراکنش ثبت کرده و پولی است.',
        26 => 'بیش از ۶۰ روز از تاریخ ثبت‌نام گذشته و کاربر در ۱۰ روز اخیر تراکنش ثبت نکرده و بیشتر مساوی ۲۰ تراکنش ثبت کرده است.',
        27 => 'بیش از ۶۰ روز از تاریخ ثبت‌نام گذشته و کاربر در ۱۰ روز اخیر تراکنش ثبت نکرده و کمتر از ۲۰ تراکنش ثبت کرده است.',
        28 => 'کاربر سوخته!',
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

    public function metrics(Request $request)
    {
        $reportController = app()->make(ReportController::class);
        [$startDate, $endDate] = $reportController->normalizeDate($request, true);
        if (!$startDate) {
            $startDate = AutomationMetric::query()->min('date');
        }
//        dd($request->input('selected_dates', []));
        $filter = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'selected_dates' => $this->convertSelectedDates($request->input('selected_dates', [])),
        ];
        $selectedDates = $filter['selected_dates'];
        $metrics = AutomationMetric::query()
            ->where(function ($query) use ($startDate) {
                if ($startDate) {
                    $query->where('date', '>=', $startDate);
                }
            })
            ->where(function ($query) use ($endDate) {
                if ($endDate) {
                    $query->where('date', '<=', $endDate);
                }
            })
            ->where(function ($query) use ($selectedDates) {
                if (!empty($selectedDates)) {
                    $query->whereIn('date', $selectedDates);
                }
            })->get();

        $metrics = $metrics->map(function ($item) {
            $item['date'] = Helpers::gregorianDateStringToJalali($item['date']);
            return $item;
        });
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
        $user->automationCall()->updateOrCreate([
            'id' => $id,
        ], $data);
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
        return redirect()->back()->with('success', 'با موفقیت انجام شد.');
    }
}
