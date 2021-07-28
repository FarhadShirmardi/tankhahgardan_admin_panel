<?php

namespace App\Http\Controllers\Dashboard;

use App\Advertisement;
use App\City;
use App\Comment;
use App\Constants\FeedbackSource;
use App\Constants\FeedbackStatus;
use App\Constants\LogType;
use App\Constants\Platform;
use App\Constants\PremiumConstants;
use App\Constants\PremiumDuration;
use App\Constants\ProjectUserState;
use App\Constants\PurchaseType;
use App\Constants\UserPremiumState;
use App\Device;
use App\Feedback;
use App\FeedbackResponse;
use App\FeedbackTitle;
use App\File;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Image;
use App\Imports\ConvertToUser;
use App\Imprest;
use App\Jobs\FeedbackResponseSms;
use App\Jobs\ProjectReportExportJob;
use App\Jobs\UserReportExportJob;
use App\Note;
use App\PanelFile;
use App\PanelUser;
use App\Payment;
use App\Project;
use App\ProjectReport;
use App\ProjectUser;
use App\Receive;
use App\State;
use App\StepByStep;
use App\User;
use App\UserReport;
use App\UserStatus;
use App\UserStatusLog;
use Carbon\Carbon;
use DB;
use Exception;
use Faker\Provider\Uuid;
use GuzzleHttp\Client;
use Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Kavenegar;
use Maatwebsite\Excel\Facades\Excel;
use Notification;
use Storage;
use Validator;

class ReportController extends Controller
{
    public function timeSeparation(Request $request)
    {
        [$startDate, $endDate] = $this->normalizeDate($request);

        $usersTime =
            User::query()->whereDate('created_at', '>=', $startDate)
                ->whereDate('created_at', '<=', $endDate)
                ->selectRaw(
                    'floor(substr(created_at,12,2) / 4) as num, COUNT(floor(substr(created_at,12,2) / 4)) as c'
                )
                ->groupBy('num')->get();
        $sum = array_sum($usersTime->pluck('c')->toArray());
        return view('dashboard.report.timeSeparation', [
            'users_time' => $usersTime,
            'sum' => $sum,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);
    }

    public function normalizeDate(Request $request, $setNull = false)
    {
        $startDate = $request->input('start_date', null);
        if ($startDate) {
            $startDate = Helpers::jalaliDateStringToGregorian(Helpers::getEnglishString($startDate));
        } elseif (!$setNull) {
            $startDate = now()->subDays(6)->toDateString();
        }
        $endDate = $request->input('end_date', null);
        if ($endDate) {
            $endDate = Helpers::jalaliDateStringToGregorian(Helpers::getEnglishString($endDate));
        } elseif (!$setNull) {
            $endDate = now()->addDay()->toDateString();
        }
        $startDate = $startDate ? str_replace('/', '-', $startDate) : null;
        $endDate = $endDate ? str_replace('/', '-', $endDate) : null;
        return [$startDate, $endDate];
    }

    public function daySeparation(Request $request)
    {
        [$startDate, $endDate] = $this->normalizeDate($request);

        $days = [
            'دوشنبه',
            'سه‌شنبه',
            'چهارشنبه',
            'پنج‌شنبه',
            'جمعه',
            'شنبه',
            'یکشنبه',
        ];
        $usersDay = array_fill_keys($days, 0);
        $usersCreation = User::query()
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->get('created_at')
            ->pluck('created_at_date');
        foreach ($usersCreation as $date) {
            $dayOfWeek = Helpers::getPersianDay(date("N", strtotime($date)));
            $usersDay[$dayOfWeek]++;
        }
        $sum = array_sum($usersDay);
        return view('dashboard.report.daySeparation', [
            'users_day' => $usersDay,
            'days' => $days,
            'sum' => $sum,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);
    }

    public function rangeSeparation(Request $request)
    {
        [$startDate, $endDate] = $this->normalizeDate($request);

        $userDays = User::query()
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->selectRaw('count(substr(created_at,1,10)) as count, substr(created_at,1,10) as date')
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();
        $days = [];
        foreach ($userDays as $day) {
            $day['date'] = Helpers::gregorianDateStringToJalali($day['date']);
            $days[] = $day;
        }
        return view('dashboard.report.rangeSeparation', [
            'days' => $days,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);
    }

    public function allUsersActivity(Request $request)
    {
        $filter = $this->getAllUserActivityFilter($request);

        $usersQuery = UserReport::query();

//        $usersQuery = $this->getUserQuery();

        $usersQuery = $this->applyFilterUserQuery($usersQuery, $filter);

//        $rangeUserQuery = clone $usersQuery;


        $users = $usersQuery->paginate(100);

        $userStates = collect();
        foreach (UserPremiumState::toArray() as $item) {
            $userStates->push([
                'id' => $item,
                'name' => UserPremiumState::getEnum($item),
                'is_selected' => in_array($item, $filter['user_states']),
            ]);
        }

        [$sortableFields, $sortableTypes] = $this->getUserSortFields();

        return view('dashboard.report.allUserActivity', [
                'users' => $users,
                'colors' => $this->colors(),
                'filter' => $filter,
                'sortable_fields' => $sortableFields,
                'sortable_types' => $sortableTypes,
                'user_states' => $userStates,
            ]
        );
    }

    public function allUsersCountChart()
    {
        $counts = $this->getUserTypeCounts();
        return view('dashboard.report.userActivityCountChart', [
            'counts' => $counts,
            'colors' => $this->colors(),
        ]);
    }

    public function allUsersRangeChart()
    {
        $usersQuery = UserReport::query();
        $rangeCounts = $this->getRangeCounts($usersQuery);
        return view('dashboard.report.userActivityRangeChart', [
            'range_counts' => $rangeCounts,
        ]);
    }

    public function extractUserIdsWithFilter(Request $request)
    {
        $filter = $this->getAllUserActivityFilter($request);
        $usersQuery = UserReport::query();
        $usersQuery = $this->applyFilterUserQuery($usersQuery, $filter);
        $userIds = $usersQuery->pluck('id')->toArray();
        return redirect()->route($request->route, ['id' => 0, 'userIds' => implode(',', $userIds)]);
    }

    private function getAllUserActivityFilter(Request $request)
    {
        [$startDate, $endDate] = $this->normalizeDate($request, true);
        if (!$startDate) {
            $startDate = UserReport::query()->selectRaw('min(Date(registered_at)) as date')->first()->date;
        }

        return [
            'user_type' => $request->input('user_type', null),
            'sort_field' => $request->input('sort_field', 'registered_at'),
            'sort_type' => $request->input('sort_type', 'DESC'),
            'phone_number' => Helpers::getEnglishString($request->input('phone_number', '')),
            'name' => $request->input('name', ''),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'user_ids' => $request->input('user_ids', []) ?? [],
            'user_states' => $request->input('user_states', []),
        ];
    }

    public function applyFilterUserQuery(&$usersQuery, array $filter)
    {
        $usersQuery = $usersQuery->orderBy($filter['sort_field'], $filter['sort_type']);

        if ($filter['user_type']) {
            $usersQuery = $usersQuery->where('user_type', $filter['user_type']);
        }
        if (!empty($filter['phone_number'])) {
            $phoneNumber = ltrim(Helpers::getEnglishString($filter['phone_number']), '0');
            $phoneNumber = '%' . $phoneNumber . '%';
            $usersQuery = $usersQuery->where('phone_number', 'like', $phoneNumber);
        }
        if (!empty($filter['name'])) {
            $name = '%' . $filter['name'] . '%';
            $usersQuery = $usersQuery->where('name', 'like', $name);
        }
        if ($filter['start_date']) {
            $usersQuery = $usersQuery->whereDate('registered_at', '>=', $filter['start_date']);
        }
        if ($filter['end_date']) {
            $usersQuery = $usersQuery->whereDate('registered_at', '<=', $filter['end_date']);
        }
        if (isset($filter['user_ids']) and $filter['user_ids'] != []) {
            if (is_string($filter['user_ids'])) {
                $filter['user_ids'] = explode(',', $filter['user_ids']);
            }
            $usersQuery = $usersQuery->whereIn('id', $filter['user_ids']);
        }
        if (isset($filter['user_states']) and $filter['user_states'] != []) {
            $usersQuery = $usersQuery->whereIn('user_state', $filter['user_states']);
        }

        return $usersQuery;
    }

    public function getUserTypeCounts()
    {
        $countsCollection = UserReport::query()
            ->selectRaw('user_type, count(*) as count_user_type')
            ->groupBy('user_type')
            ->get();

        $counts = array_fill(1, 4, 0);
        foreach ($countsCollection as $item) {
            $counts[$item['user_type']] = $item['count_user_type'];
        }

        return $counts;
    }

    private function getRangeCounts($rangeUserQuery)
    {
        $rangeCounts = [];
        foreach (range(0, 5) as $time) {
            $paymentSubQuery = Payment::withoutTrashed()->whereIn('creator_user_id', $rangeUserQuery->select('id'))
                ->whereRaw('substr(created_at, 12, 2) between ' . $time * 4 . ' AND ' . ($time + 1) * 4)
                ->selectRaw('count(*)')->getQuery();
            $receiveSubQuery = Receive::withoutTrashed()->whereIn('creator_user_id', $rangeUserQuery->select('id'))
                ->whereRaw('substr(created_at, 12, 2) between ' . $time * 4 . ' AND ' . ($time + 1) * 4)
                ->selectRaw('count(*)')->getQuery();
            $noteSubQuery = Note::withoutTrashed()->whereIn('creator_user_id', $rangeUserQuery->select('id'))
                ->whereRaw('substr(created_at, 12, 2) between ' . $time * 4 . ' AND ' . ($time + 1) * 4)
                ->selectRaw('count(*)')->getQuery();
            $imprestSubQuery = Imprest::withoutTrashed()->whereIn('creator_user_id', $rangeUserQuery->select('id'))
                ->whereRaw('substr(created_at, 12, 2) between ' . $time * 4 . ' AND ' . ($time + 1) * 4)
                ->selectRaw('count(*)')->getQuery();

            $result = DB::query()
                ->selectSub($paymentSubQuery, 'payments_count')
                ->selectSub($receiveSubQuery, 'receives_count')
                ->selectSub($noteSubQuery, 'notes_count')
                ->selectSub($imprestSubQuery, 'imprests_count')
                ->get();

            $rangeCounts[($time * 4) . ' - ' . (($time + 1) * 4)] = $result->first();
        }

        return $rangeCounts;
    }

    private function getUserSortFields()
    {
        $sortableFields = [
            'name' => 'نام و نام خانوادگی',
            'phone_number' => 'شماره تلفن',
            'registered_at' => 'تاریخ و ساعت ثبت نام',
            'max_time' => 'آخرین ثبت',
            'project_count' => 'تعداد کل پروژه',
            'own_project_count' => 'تعداد پروژه مالک',
            'not_own_project_count' => 'تعداد پروژه اشتراکی',
            'payment_count' => 'تعداد پرداخت',
            'receive_count' => 'تعداد دریافت',
            'note_count' => 'تعداد یادداشت',
            'imprest_count' => 'تعداد تنخواه',
            'file_count' => 'تعداد فایل‌ها',
            'image_count' => 'تعداد عکس‌ها',
            'image_size' => 'حجم عکس‌ها',
            'feedback_count' => 'تعداد بازخورد',
            'device_count' => 'تعداد دستگاه‌ها',
            'step_by_step' => 'گام به گام',
        ];

        $sortableTypes = [
            'ASC' => 'صعودی',
            'DESC' => 'نزولی',
        ];

        return [$sortableFields, $sortableTypes];
    }

    public function colors()
    {
        return [
            1 => ['#BEDACE', 'یک هفته'],
            2 => ['#DCE1FF', 'دو هفته'],
            3 => ['#FBE9E7', 'یک ماه'],
            4 => ['#F1F3F4', 'غیرفعال'],
        ];
    }

    public function exportAllUsersActivity(Request $request)
    {
        $filter = $this->getAllUserActivityFilter($request);


        $today = str_replace('/', '_', Helpers::gregorianDateStringToJalali(now()->toDateString()));
        $filename = "export/allUserActivity - {$today} - " . Str::random('6') . '.xlsx';
        $panelFile = PanelFile::query()
            ->create([
                'user_id' => auth()->id(),
                'path' => $filename,
                'description' => 'گزارش وضعیت کاربران - ' . str_replace('_', '/', $today),
                'date_time' => now()->toDateTimeString(),
            ]);

        $this->dispatch((new UserReportExportJob($filter, $panelFile))->onQueue('activationSms'));

        return redirect()->route('dashboard.downloadCenter');
    }

    public function exportAllProjectsActivity(Request $request)
    {
        $filter = $this->getAllProjectActivityFilter($request);

        $today = str_replace('/', '_', Helpers::gregorianDateStringToJalali(now()->toDateString()));
        $filename = "export/allProjectActivity - {$today} - " . Str::random('6') . '.xlsx';
        $panelFile = PanelFile::query()
            ->create([
                'user_id' => auth()->id(),
                'path' => $filename,
                'description' => 'گزارش وضعیت پروژه - ' . str_replace('_', '/', $today),
                'date_time' => now()->toDateTimeString(),
            ]);
        $this->dispatch((new ProjectReportExportJob($filter, $panelFile))->onQueue('activationSms'));

        return redirect()->route('dashboard.downloadCenter');
    }

    public function getProjectQuery()
    {
        $paymentCountQuery =
            Payment::withoutTrashed()->whereColumn('project_id', 'projects.id')->selectRaw('count(*)')->getQuery();
        $receiveCountQuery =
            Receive::withoutTrashed()->whereColumn('project_id', 'projects.id')->selectRaw('count(*)')->getQuery();
        $noteCountQuery =
            Note::withoutTrashed()->whereColumn('project_id', 'projects.id')->selectRaw('count(*)')->getQuery();
        $imprestCountQuery =
            Imprest::withoutTrashed()->whereColumn('project_id', 'projects.id')->selectRaw('count(*)')->getQuery();

        $paymentMaxQuery =
            Payment::withoutTrashed()->whereColumn('project_id', 'projects.id')->selectRaw('MAX(created_at)')->toSql();
        $receiveMaxQuery =
            Receive::withoutTrashed()->whereColumn('project_id', 'projects.id')->selectRaw('MAX(created_at)')->toSql();
        $noteMaxQuery =
            Note::withoutTrashed()->whereColumn('project_id', 'projects.id')->selectRaw('MAX(created_at)')->toSql();
        $imprestMaxQuery =
            Imprest::withoutTrashed()->whereColumn('project_id', 'projects.id')->selectRaw('MAX(created_at)')->toSql();

        $userCount =
            ProjectUser::withoutTrashed()->whereColumn('project_id', 'projects.id')->selectRaw('count(*)')->getQuery();
        $activeUserCount =
            ProjectUser::withoutTrashed()->whereColumn('project_id', 'projects.id')->where('state', ProjectUserState::ACTIVE)->selectRaw('count(*)')->getQuery();
        $notActiveUserCount =
            ProjectUser::withoutTrashed()->whereColumn('project_id', 'projects.id')->where('state', '<>', ProjectUserState::ACTIVE)->selectRaw('count(*)')->getQuery();

        $maxTimeQuery = Project::query()
            ->selectRaw(
                "NULLIF(
                    GREATEST(
                        COALESCE((" . $paymentMaxQuery . "), 0),
                        COALESCE((" . $receiveMaxQuery . "), 0),
                        COALESCE((" . $noteMaxQuery . "), 0),
                        COALESCE((" . $imprestMaxQuery . "), 0)
                    ),
                    0
                ) as max_time, projects.id as project_id"
            );

        $times = $this->times();

        $projectTypeQuery = '';
        foreach ($times as $key => $time) {
            if ($key == count($times)) {
                $projectTypeQuery .= $time[2] . str_repeat(')', $key - 1) . ' as project_type';
            } else {
                $projectTypeQuery .= 'IF(MaxTime.max_time ' . $time[0] . ' \'' . $time[1] . '\', ' . $time[2] . ', ';
            }
        }

        return Project::query()
            ->joinSub($maxTimeQuery, 'MaxTime', 'MaxTime.project_id', '=', 'projects.id')
            ->addSelect('projects.name as name')
            ->addSelect('projects.id as id')
            ->addSelect('projects.city_id as city_id')
            ->addSelect('projects.state_id as state_id')
            ->addSelect('projects.created_at as created_at')
            ->addSelect('projects.type as type')
            ->selectSub($paymentCountQuery, 'payment_count')
            ->selectSub($receiveCountQuery, 'receive_count')
            ->selectSub($noteCountQuery, 'note_count')
            ->selectSub($imprestCountQuery, 'imprest_count')
            ->selectSub($userCount, 'user_count')
            ->selectSub($activeUserCount, 'active_user_count')
            ->selectSub($notActiveUserCount, 'not_active_user_count')
            ->selectRaw('MaxTime.max_time as max_time')
            ->selectRaw($projectTypeQuery);
    }

    private function times()
    {
        return [
            1 => ['>=', now()->subDays(7)->toDateTimeString(), 1],
            2 => ['>=', now()->subDays(14)->toDateTimeString(), 2],
            3 => ['>=', now()->subMonths(1)->toDateTimeString(), 3],
            4 => ['<', now()->subMonths(1)->toDateTimeString(), 4],
        ];
    }

    public function getUserQuery($userId = null)
    {
        $paymentCountQuery =
            Payment::withoutTrashed()->whereColumn('creator_user_id', 'users.id')->selectRaw('count(*)')->getQuery();
        $receiveCountQuery =
            Receive::withoutTrashed()->whereColumn('creator_user_id', 'users.id')->selectRaw('count(*)')->getQuery();
        $noteCountQuery =
            Note::withoutTrashed()->whereColumn('creator_user_id', 'users.id')->selectRaw('count(*)')->getQuery();
        $imprestCountQuery =
            Imprest::withoutTrashed()->whereColumn('creator_user_id', 'users.id')->selectRaw('count(*)')->getQuery();
        $fileCountQuery = File::query()->whereColumn('creator_user_id', 'users.id')->selectRaw('count(*)')->getQuery();
        $imageCountQuery =
            Image::query()->withoutTrashed()->whereColumn('user_id', 'users.id')->selectRaw('count(*)')->getQuery();
        $deviceCountQuery = Device::query()->whereColumn('user_id', 'users.id')->selectRaw('count(*)')->getQuery();
        $feedbackCountQuery = Feedback::query()->whereColumn('user_id', 'users.id')->selectRaw('count(*)')->getQuery();

        $paymentMaxQuery =
            Payment::withoutTrashed()->whereColumn('creator_user_id', 'users.id')->selectRaw('MAX(created_at)')->toSql();
        $receiveMaxQuery =
            Receive::withoutTrashed()->whereColumn('creator_user_id', 'users.id')->selectRaw('MAX(created_at)')->toSql();
        $noteMaxQuery =
            Note::withoutTrashed()->whereColumn('creator_user_id', 'users.id')->selectRaw('MAX(created_at)')->toSql();
        $imprestMaxQuery =
            Imprest::withoutTrashed()->whereColumn('creator_user_id', 'users.id')->selectRaw('MAX(created_at)')->toSql();

        $imageSizeQuery =
            Image::withoutTrashed()->whereColumn('user_id', 'users.id')->selectRaw('IFNULL(sum(size), 0) / 1024 / 1024')->getQuery();

        $projectCount =
            ProjectUser::withoutTrashed()->whereColumn('user_id', 'users.id')->selectRaw('count(*)')->getQuery();
        $ownProjectCount =
            ProjectUser::withoutTrashed()->whereColumn('user_id', 'users.id')->where('is_owner', true)->selectRaw('count(*)')->getQuery();
        $notOwnProjectCount =
            ProjectUser::withoutTrashed()->whereColumn('user_id', 'users.id')->where('is_owner', false)->selectRaw('count(*)')->getQuery();

        $stepByStep = StepByStep::query()->whereColumn('user_id', 'users.id')->selectRaw('IFNULL(step, 0)')->getQuery();

        $maxTimeQuery = User::query()
            ->where(function ($query) use ($userId) {
                if ($userId) {
                    $query->where('id', $userId);
                }
            })
            ->selectRaw(
                "NULLIF(
                    GREATEST(
                        COALESCE((" . $paymentMaxQuery . "), 0),
                        COALESCE((" . $receiveMaxQuery . "), 0),
                        COALESCE((" . $noteMaxQuery . "), 0),
                        COALESCE((" . $imprestMaxQuery . "), 0)
                    ),
                    0
                ) as max_time, users.id as user_id"
            );

        $times = $this->times();

        $userTypeQuery = '';
        foreach ($times as $key => $time) {
            if ($key == count($times)) {
                $userTypeQuery .= $time[2] . str_repeat(')', $key - 1) . ' as user_type';
            } else {
                $userTypeQuery .= 'IF(MaxTime.max_time ' . $time[0] . ' \'' . $time[1] . '\', ' . $time[2] . ', ';
            }
        }
        $userStateQuery = UserStatus::query()
            ->whereColumn('user_id', 'users.id')
            ->orderBy('end_date', 'DESC')
            ->limit(1)
            ->selectRaw("
                IF(
                    user_statuses.end_date < '" . now()->toDateTimeString() . "',
                    " . UserPremiumState::EXPIRED_PREMIUM . ",
                    IF(
                        user_statuses.end_date < '" . now()->addDays(PremiumConstants::NEAR_END_THRESHOLD)->toDateTimeString() . "',
                        " . UserPremiumState::NEAR_ENDING_PREMIUM . ",
                        " . UserPremiumState::PREMIUM . "
                    )
                )
        ");


        return User::query()
            ->where(function ($query) use ($userId) {
                if ($userId) {
                    $query->where('id', $userId);
                }
            })
            ->joinSub($maxTimeQuery, 'MaxTime', 'MaxTime.user_id', '=', 'users.id')
            ->selectRaw("CONCAT_WS(' ', IFNULL(users.name, ''), IFNULL(users.family, '')) as name")
            ->addSelect('users.id as id')
            ->addSelect('phone_number')
            ->addSelect('users.verification_time as registered_at')
            ->selectSub($paymentCountQuery, 'payment_count')
            ->selectSub($receiveCountQuery, 'receive_count')
            ->selectSub($noteCountQuery, 'note_count')
            ->selectSub($imprestCountQuery, 'imprest_count')
            ->selectSub($fileCountQuery, 'file_count')
            ->selectSub($imageCountQuery, 'image_count')
            ->selectSub($imageSizeQuery, 'image_size')
            ->selectSub($deviceCountQuery, 'device_count')
            ->selectSub($feedbackCountQuery, 'feedback_count')
            ->selectSub($stepByStep, 'step_by_step')
            ->selectSub($projectCount, 'project_count')
            ->selectSub($ownProjectCount, 'own_project_count')
            ->selectSub($notOwnProjectCount, 'not_own_project_count')
            ->selectRaw("IFNULL( (" . $userStateQuery->toSql() . " ), " . UserPremiumState::FREE . ") as user_state")
            ->selectRaw('MaxTime.max_time as max_time')
            ->selectRaw($userTypeQuery);
    }

    public function userActivity($id, PremiumController $premiumController)
    {
        /** @var User $user */
        $user = User::with('projects')->findOrFail($id);

        $paymentCountQuery =
            Payment::withoutTrashed()->whereColumn('project_id', 'projects.id')->where('creator_user_id', $id)
                ->selectRaw('count(*)')->getQuery();
        $receiveCountQuery =
            Receive::withoutTrashed()->whereColumn('project_id', 'projects.id')->where('creator_user_id', $id)
                ->selectRaw('count(*)')->getQuery();
        $noteCountQuery =
            Note::withoutTrashed()->whereColumn('project_id', 'projects.id')->where('creator_user_id', $id)
                ->selectRaw('count(*)')->getQuery();
        $imprestCountQuery =
            Imprest::withoutTrashed()->whereColumn('project_id', 'projects.id')->where('creator_user_id', $id)
                ->selectRaw('count(*)')->getQuery();
        $imageCountQuery = Image::withoutTrashed()->whereColumn('project_id', 'projects.id')->where('user_id', $id)
            ->selectRaw('count(*)')->getQuery();
        $imageSizeQuery = Image::withoutTrashed()->whereColumn('project_id', 'projects.id')->where('user_id', $id)
            ->selectRaw('IFNULL(sum(size), 0) / 1024 / 1024')->getQuery();

        $projectsQuery = Project::query()
            ->join('project_user', function ($join) use ($id) {
                $join->on('project_user.project_id', 'projects.id')
                    ->where('project_user.user_id', $id);
            })
            ->addSelect('project_user.is_owner as is_owner')
            ->addSelect('projects.name as name')
            ->addSelect('projects.id as id')
            ->addSelect('projects.is_archived')
            ->addSelect('project_user.state as status')
            ->selectSub($paymentCountQuery, 'payment_count')
            ->selectSub($receiveCountQuery, 'receive_count')
            ->selectSub($noteCountQuery, 'note_count')
            ->selectSub($imprestCountQuery, 'imprest_count')
            ->selectSub($imageCountQuery, 'image_count')
            ->selectSub($imageSizeQuery, 'image_size')
            ->orderBy('projects.created_at');

        $projects = $projectsQuery->get();

        $rangeCounts = $this->getRangeCounts(User::query()->where('id', $id));

        $paymentSubQuery =
            Payment::withoutTrashed()->where('creator_user_id', $id)->selectRaw('project_id, substr(created_at, 1, 10) as date')->getQuery();
        $receiveSubQuery =
            Receive::withoutTrashed()->where('creator_user_id', $id)->selectRaw('project_id, substr(created_at, 1, 10) as date')->getQuery();
        $noteSubQuery =
            Note::withoutTrashed()->where('creator_user_id', $id)->selectRaw('project_id, substr(created_at, 1, 10) as date')->getQuery();
        $imprestSubQuery =
            Imprest::withoutTrashed()->where('creator_user_id', $id)->selectRaw('project_id, substr(created_at, 1, 10) as date')->getQuery();

        $dateCounts = DB::connection('mysql')->query()
            ->selectRaw('count(*) as c, date, project_id')
            ->from(
                DB::query()
                    ->from($paymentSubQuery)
                    ->unionAll($receiveSubQuery)
                    ->unionAll($noteSubQuery)
                    ->unionAll($imprestSubQuery)
            )
            ->groupBy(['date', 'project_id'])
            ->orderBy('date')
            ->get();


        $dates = $dateCounts->pluck('date');

        $counts = collect();
        foreach ($user->projects->pluck('id') as $projectId) {
            $data = collect();
            foreach ($dates as $date) {
                $data->push($dateCounts->where('date', $date)->where('project_id', $projectId)->sum('c'));
            }
            $counts->push([
                'id' => $projectId,
                'name' => $user->projects->find($projectId)->name,
                'data' => $data->toJson(),
            ]);
        }
        $dateCounts = $counts;
        $dates = $dates->map(function ($item) {
            return Helpers::gregorianDateStringToJalali($item);
        });

        $devices = $user->devices()->get();

        $userItem = $this->getUserQuery($id)->get();
        $userItem = Helpers::paginateCollection($userItem);

        $userStates = $premiumController->getUserStates($user);
        $invoices = $user->invoices()->get();

        $automationState = $user->automationData()->first();
        $automationController = app()->make(AutomationController::class);

        return view('dashboard.report.userActivity', [
            'user' => $user,
            'userItem' => $userItem,
            'projects' => $projects,
            'range_counts' => $rangeCounts,
            'date_counts' => $dateCounts,
            'dates' => $dates,
            'devices' => $devices,
            'user_statuses' => $userStates,
            'invoices' => $invoices,
            'automationState' => $automationState,
            'type_mappings' => $automationController->typeMapping,
            'colors' => $this->colors(),
        ]);
    }

    public function allProjectsActivity(Request $request)
    {
        $filter = $this->getAllProjectActivityFilter($request);

        $projectsQuery = ProjectReport::query();
        $projectsQuery = $this->applyFilterProjectQuery($projectsQuery, $filter);

        $projects = $projectsQuery->paginate(100);

        $counts = $this->getProjectTypeCounts();

        [$states, $cities] = $this->getLocations();

        [$sortableFields, $sortableTypes] = $this->getProjectSortFields();

        return view('dashboard.report.allProjectActivity', [
            'projects' => $projects,
            'counts' => $counts,
            'states' => $states,
            'cities' => $cities,
            'filter' => $filter,
            'colors' => $this->colors(),
            'sortable_fields' => $sortableFields,
            'sortable_types' => $sortableTypes,
        ]);
    }

    private function getAllProjectActivityFilter(Request &$request)
    {
        return [
            'project_type' => $request->input('project_type', null),
            'sort_field' => $request->input('sort_field', 'created_at'),
            'sort_type' => $request->input('sort_type', 'DESC'),
            'state_id' => $request->input('state_id', 0),
            'city_id' => $request->input('city_id', 0),
            'name' => $request->input('name', ''),
        ];
    }

    public function applyFilterProjectQuery(&$projectsQuery, array $filter)
    {
        $projectsQuery = $projectsQuery->orderBy($filter['sort_field'], $filter['sort_type']);

        if ($filter['project_type']) {
            $projectsQuery = $projectsQuery->where('project_type', $filter['project_type']);
        }
        if (!empty($filter['name'])) {
            $name = '%' . $filter['name'] . '%';
            $projectsQuery = $projectsQuery->where('name', 'like', $name);
        }
        if ($filter['state_id']) {
            $projectsQuery = $projectsQuery->where('state_id', $filter['state_id']);
        }
        if ($filter['city_id']) {
            $projectsQuery = $projectsQuery->where('city_id', $filter['city_id']);
        }

        return $projectsQuery;
    }

    public function getProjectTypeCounts()
    {
        $countsCollection = ProjectReport::query()
            ->selectRaw('project_type, count(*) as count_project_type')
            ->groupBy('project_type')
            ->get();

        $counts = array_fill(1, 4, 0);
        foreach ($countsCollection as $item) {
            $counts[$item['project_type']] = $item['count_project_type'];
        }

        return $counts;
    }

    private function getLocations()
    {
        $states = State::orderBy('name')->get(['id', 'name']);
        $states->prepend([
            'id' => 0,
            'name' => 'همه',
        ]);
        $states = $states->sortBy('id');
        $cities = City::orderBy('name')->get(['id', 'state_id', 'name']);
        foreach ($states as $state) {
            $cities->prepend([
                'id' => 0,
                'state_id' => $state['id'],
                'name' => 'همه',
            ]);
        }
        $cities = $cities->sortBy('id');
        return [$states, $cities];
    }

    private function getProjectSortFields()
    {
        $sortableFields = [
            'name' => 'نام پروژه',
            'created_at' => 'تاریخ ایجاد پروژه',
            'max_time' => 'آخرین ثبت',
            'user_count' => 'تعداد کاربران پروژه',
            'project_state' => 'وضعیت پروژه',
            'active_user_count' => 'تعداد کاربران فعال',
            'not_active_user_count' => 'تعداد کاربران غیرفعال',
            'payment_count' => 'تعداد پرداخت',
            'receive_count' => 'تعداد دریافت',
            'note_count' => 'تعداد یادداشت',
            'imprest_count' => 'تعداد تنخواه',
            'type' => 'نوع پروژه',
        ];

        $sortableTypes = [
            'ASC' => 'صعودی',
            'DESC' => 'نزولی',
        ];

        return [$sortableFields, $sortableTypes];
    }

    public function projectActivity(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);

        $paymentCountQuery = Payment::whereColumn('creator_user_id', 'users.id')->where('project_id', $projectId)
            ->withoutTrashed()->selectRaw('count(*)')->getQuery();
        $receiveCountQuery = Receive::whereColumn('creator_user_id', 'users.id')->where('project_id', $projectId)
            ->withoutTrashed()->selectRaw('count(*)')->getQuery();
        $noteCountQuery = Note::whereColumn('creator_user_id', 'users.id')->where('project_id', $projectId)
            ->withoutTrashed()->selectRaw('count(*)')->getQuery();
        $imprestCountQuery = Imprest::whereColumn('creator_user_id', 'users.id')->where('project_id', $projectId)
            ->withoutTrashed()->selectRaw('count(*)')->getQuery();
        $imageCountQuery = Image::whereColumn('user_id', 'users.id')->where('project_id', $projectId)
            ->withoutTrashed()->selectRaw('count(*)')->getQuery();
        $imageSizeQuery = Image::whereColumn('user_id', 'users.id')->where('project_id', $projectId)
            ->withoutTrashed()->selectRaw('IFNULL(sum(size), 0) / 1024 / 1024')->getQuery();

        $usersQuery = User::query()
            ->join('project_user', function ($join) use ($projectId) {
                $join->on('project_user.user_id', 'users.id')
                    ->where('project_user.project_id', $projectId);
            })
            ->addSelect('project_user.state as user_state')
            ->addSelect('project_user.is_owner as is_owner')
            ->addSelect('users.name as name')
            ->addSelect('users.family as family')
            ->addSelect('users.phone_number as phone_number')
            ->addSelect('users.id as id')
            ->selectSub($paymentCountQuery, 'payment_count')
            ->selectSub($receiveCountQuery, 'receive_count')
            ->selectSub($noteCountQuery, 'note_count')
            ->selectSub($imprestCountQuery, 'imprest_count')
            ->selectSub($imageCountQuery, 'image_count')
            ->selectSub($imageSizeQuery, 'image_size')
            ->orderBy('users.created_at');

        $users = $usersQuery->get();

        return view('dashboard.report.projectActivity', [
            'project' => $project,
            'users' => $users,
        ]);
    }

    public function viewFeedback(Request $request)
    {
        [$startDate, $endDate] = $this->normalizeDate($request, true);
        if (!$startDate) {
            $startDate = Feedback::query()->selectRaw('min(Date(created_at)) as date')->first()->date;
        }

        $filter = [
            'source_type' => $request->input('source_type', []),
            'titles' => $request->input('titles', []),
            'user_id' => $request->input('user_id', null),
            'panel_user_ids' => $request->input('panel_user_ids', []),
            'sort_field_1' => $request->input('sort_field_1', 'state'),
            'sort_type_1' => $request->input('sort_type_1', 'ASC'),
            'sort_field_2' => $request->input('sort_field_2', 'date'),
            'sort_type_2' => $request->input('sort_type_2', 'DESC'),
            'scores' => $request->input('scores', []),
            'platforms' => $request->input('platforms', []),
            'states' => $request->input('states', []),
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];


        $feedbacks = $this->fetchFeedbacks($filter);

        $sourceTypes = collect();
        foreach (FeedbackSource::toArray() as $source) {
            $item = [];
            $item['value'] = $source;
            $item['text'] = FeedbackSource::getEnum($source);
            $item['is_selected'] = in_array($source, $filter['source_type']);
            $sourceTypes->push($item);
        }
        $sourceTypes = $sourceTypes->toArray();

        $titles = FeedbackTitle::get()->map(function ($item) use ($filter) {
            $item['is_selected'] = in_array($item['id'], $filter['titles']);
            return $item;
        });

        $users = User::query()
            ->join('feedback', 'feedback.user_id', '=', 'users.id')
            ->distinct()
            ->select([
                'users.id',
                'users.name',
                'users.family',
                'users.phone_number',
                DB::raw("false as is_selected"),
            ])
            ->get();

        if (isset($filter['user_id'])) {
            $users->where('id', $filter['user_id'])->first()['is_selected'] = true;
        }

        $panelUsers = PanelUser::query()->get(['id', 'name']);

        if (isset($filter['panel_user_ids']) and $filter['panel_user_ids'] != []) {
            $panelUsers = $panelUsers->map(function ($item) use ($filter) {
                $item->is_selected = in_array($item['id'], $filter['panel_user_ids']);
                return $item;
            });
        }

        $scores = collect();
        foreach (range(0, 5) as $item) {
            $scores->push([
                'id' => $item,
                'name' => $item == 0 ? 'بدون امتیاز' : $item,
                'is_selected' => in_array($item, $filter['scores']),
            ]);
        }

        $platforms = collect();
        foreach (Platform::toArray() as $item) {
            $platforms->push([
                'id' => $item,
                'name' => Platform::getEnum($item),
                'is_selected' => in_array($item, $filter['platforms']),
            ]);
        }

        $states = collect();
        foreach (FeedbackStatus::toArray() as $item) {
            $states->push([
                'id' => $item,
                'name' => FeedbackStatus::getEnum($item),
                'is_selected' => in_array($item, $filter['states']),
            ]);
        }


        $feedbacks = Helpers::paginateCollection($feedbacks, 100);

        $sortableFields = [
            'date' => 'تاریخ',
            'title' => 'عنوان',
            'source' => 'منبع',
            'platform' => 'پلتفرم',
            'text' => 'متن',
            'user_phone_number' => 'شماره کاربر',
            'panel_user_name' => 'کارشناس',
            'response_text_update_time' => 'تاریخ به‌روزرسانی پاسخ',
            'response_text' => 'پاسخ بازخورد',
            'response_score' => 'امتیاز',
            'state' => 'وضعیت',
        ];

        $sortableTypes = [
            'ASC' => 'صعودی',
            'DESC' => 'نزولی',
        ];

        return view('dashboard.report.feedback', [
            'feedbacks' => $feedbacks,
            'filter' => $filter,
            'source_type' => $sourceTypes,
            'titles' => $titles,
            'users' => $users,
            'sortable_fields' => $sortableFields,
            'sortable_types' => $sortableTypes,
            'panel_users' => $panelUsers,
            'scores' => $scores,
            'platforms' => $platforms,
            'states' => $states,
        ]);
    }

    private function fetchFeedbacks($filter)
    {
        $feedbackSubQuery = Feedback::query()
            ->join('feedback_titles', 'feedback_titles.id', '=', 'feedback_title_id')
            ->join('users', 'users.id', '=', 'feedback.user_id')
            ->leftJoin('feedback_responses', 'feedback_responses.id', '=', 'feedback_response_id')
            ->leftJoin('panel_users', 'feedback_responses.panel_user_id', '=', 'panel_users.id')
            ->leftJoin('devices', 'devices.id', '=', 'feedback.device_id')
            ->where(function ($query) use ($filter) {
                if (isset($filter['titles']) and $filter['titles'] != []) {
                    $query->whereIn('feedback_titles.id', $filter['titles']);
                }
            })
            ->where(function ($query) use ($filter) {
                if (isset($filter['platforms']) and $filter['platforms'] != []) {
                    $query->whereIn('devices.platform', $filter['platforms']);
                }
            })
            ->select([
                'feedback.created_at as date',
                'feedback.id as feedback_id',
                DB::raw('0 as comment_id'),
                'feedback.state',
                'feedback_titles.title as title',
                'feedback_responses.text as response_text',
                'feedback_responses.response_updated_at as response_text_update_time',
                'feedback_responses.score as response_score',
                'panel_users.name as panel_user_name',
                'panel_users.id as panel_user_id',
                'feedback.text as text',
                'users.id as user_id',
                'users.name as user_name',
                'users.family as user_family',
                'users.phone_number as user_phone_number',
                DB::raw("'" . FeedbackSource::APPLICATION . "'" . ' as source'),
                'devices.platform',
                'devices.model',
                'devices.os_version',
            ])->getQuery();

        $commentSubQuery = Comment::query()
            ->leftJoin('feedback_titles', 'feedback_titles.id', '=', 'feedback_title_id')
            ->leftJoin('panel_users', 'comments.panel_user_id', '=', 'panel_users.id')
            ->select([
                'comments.date',
                DB::raw('0 as feedback_id'),
                'comments.id as comment_id',
                'comments.state',
                'feedback_titles.title as title',
                'comments.response as response_text',
                'comments.response_date as response_text_update_time',
                DB::raw('0 as response_score'),
                'panel_users.name as panel_user_name',
                'panel_users.id as panel_user_id',
                'comments.text as text',
                'comments.user_id as user_id',
                DB::raw('"" as user_family'),
                'comments.name as user_name',
                'comments.phone_number as user_phone_number',
                'comments.source',
                DB::raw('0 as platform'),
                DB::raw("'-' as model"),
                DB::raw("'-' as os_version"),
            ])->getQuery();

        $feedbacks = DB::connection('mysql')->query()
            ->fromSub($feedbackSubQuery->unionAll($commentSubQuery), 'comment')
            ->where(function ($query) use ($filter) {
                if (isset($filter['source_type']) and $filter['source_type'] != []) {
                    $query->whereIn('source', $filter['source_type']);
                }
                if (isset($filter['start_date'])) {
                    $query->whereDate('date', '>=', $filter['start_date']);
                }
                if (isset($filter['end_date'])) {
                    $query->whereDate('date', '<=', $filter['end_date']);
                }
            })
            ->where(function ($query) use ($filter) {
                if (isset($filter['user_id'])) {
                    $query->where('user_id', $filter['user_id']);
                }
            })
            ->where(function ($query) use ($filter) {
                if (isset($filter['panel_user_ids']) and $filter['panel_user_ids'] != []) {
                    $query->whereIn('panel_user_id', $filter['panel_user_ids']);
                }
            })
            ->where(function ($query) use ($filter) {
                if (isset($filter['scores']) and $filter['scores'] != []) {
                    $query->whereIn('response_score', $filter['scores']);
                }
            })
            ->where(function ($query) use ($filter) {
                if (isset($filter['states']) and $filter['states'] != []) {
                    $query->whereIn('state', $filter['states']);
                }
            })
            ->orderBy($filter['sort_field_1'], $filter['sort_type_1'])
            ->orderBy($filter['sort_field_2'], $filter['sort_type_2'])
            ->get();

        $feedbacks = $feedbacks->map(function ($item) {
            $item->full_name = ($item->user_name or $item->user_family) ?
                $item->user_name . ' ' . $item->user_family : ' - ';
            $item->date = Helpers::convertDateTimeToJalali($item->date);
            $item->source = FeedbackSource::getEnum($item->source);
            $item->response_text_update_time =
                $item->response_text_update_time ?
                    Helpers::convertDateTimeToJalali($item->response_text_update_time) :
                    null;
            $item->state = FeedbackStatus::getEnum($item->state);
            $item->platform = Platform::getEnum($item->platform);
            return $item;
        });

        return $feedbacks;
    }

    public function commentView(Request $request, $id = null)
    {
        $filter = [
            'phone_number' => Helpers::getEnglishString($request->input('phone_number', null)),
            'user_id' => $request->input('user_id', null),
            'sort_field_1' => $request->input('sort_field_1', 'date'),
            'sort_type_1' => $request->input('sort_type_1', 'desc'),
            'sort_field_2' => $request->input('sort_field_2', 'date'),
            'sort_type_2' => $request->input('sort_type_2', 'desc'),
        ];

        $selectedUser = null;
        $selectUsers = collect();
        if ($filter['phone_number']) {
            $phoneNumber = ltrim(Helpers::getEnglishString($filter['phone_number']), '0');
            $selectUsers = User::query()
                ->where('phone_number', 'like', '%' . $phoneNumber . '%')
                ->where('state', 1)
                ->selectRaw('false as is_selected, id, name, family, phone_number')->get();

            if ($selectUsers->count() == 1 or $filter['user_id']) {
                if ($filter['user_id']) {
                    $selectedUser = $selectUsers->where('id', $filter['user_id'])->first();
                } else {
                    $selectedUser = $selectUsers->first();
                }
                $filter['user_id'] = $selectedUser->id;
            }

            $selectUsers->where('id', $filter['user_id'])->first()['is_selected'] = true;
        }

        $feedbacks = $filter['user_id'] ? $this->fetchFeedbacks($filter) : [];

        $users = $filter['user_id'] ? UserReport::query()->where('id', $filter['user_id'])->paginate() : [];

        $comment = Comment::find($id);
        $comment = $comment == null ? new Comment() : $comment;

        $sourceTypes = collect();
        foreach (FeedbackSource::toArray() as $source) {
            if ($source == FeedbackSource::APPLICATION) {
                continue;
            }
            $item = [];
            $item['value'] = $source;
            $item['text'] = FeedbackSource::getEnum($source);
            $sourceTypes->push($item);
        }
        $sourceTypes = $sourceTypes->toArray();

        $feedbackTitles = FeedbackTitle::orderBy('title')->get();

        $panelUsers = PanelUser::all();


        return view('dashboard.report.newComment', [
            'id' => $id,
            'feedbacks' => $feedbacks,
            'comment' => $comment,
            'panel_users' => $panelUsers,
            'select_users' => $selectUsers,
            'source_types' => $sourceTypes,
            'feedback_titles' => $feedbackTitles,
            'filter' => $filter,
            'selected_user' => $selectedUser,
            'users' => $users,
            'colors' => $this->colors(),
        ]);
    }

    public function addComment(Request $request, $id = null)
    {
        $feedbackTitle = $request->feedback_title_id;
        $isSpam = $request->state == FeedbackStatus::SPAM;
        if (!$isSpam and !$feedbackTitle) {
            return redirect()->back()->withErrors('موضوع بازخورد باید انتخاب شود.');
        }
        $request['date'] =
            Helpers::convertDateTimeToGregorian(Helpers::getEnglishString($request->date));
        $request['response_date'] = $request->response_date ?
            Helpers::convertDateTimeToGregorian(Helpers::getEnglishString($request->response_date)) : null;
        if (!$id) {
            $comment = Comment::create($request->all());
        } else {
            $comment = Comment::findOrFail($id);
            $comment->update($request->all());
        }

        /** @var PanelUser $panelUser */
        $panelUser = auth()->user();
        $type = $id ? LogType::EDIT_FEEDBACK : LogType::NEW_COMMENT;
        $panelUser->logs()->create([
            'user_id' => $comment->user_id,
            'type' => $type,
            'date_time' => now()->toDateTimeString(),
            'description' => LogType::getDescription($type, $panelUser),
            'old_json' => $id ? $comment : null,
            'new_json' => Comment::findOrFail($comment->id),
        ]);

        return redirect()->route('dashboard.commentView')->with('success', 'با موفقیت انجام شد.');
    }

    public function responseFeedbackView($id)
    {
        $feedback = Feedback::query()->findOrFail($id);
        $images = $feedback->images()->get()->toArray();
        $responseImages = [];
        /** @var FeedbackResponse $feedbackResponse */
        $feedbackResponse = $feedback->feedbackResponse()->first();
        if ($feedbackResponse) {
            $responseImages = $feedbackResponse->images()->get()->toArray();
        }
        $filter = [
            'user_id' => $feedback->user_id,
            'sort_field_1' => 'date',
            'sort_type_1' => 'desc',
            'sort_field_2' => 'date',
            'sort_type_2' => 'desc',
        ];

        $feedbacks = $this->fetchFeedbacks($filter);

        $users = UserReport::query()->where('id', $feedback->user_id)->paginate();

        $feedback = $feedbacks->where('feedback_id', $id)->first();
        $feedback->images = $images;
        $feedback->responseImages = $responseImages;

        $feedbacks->where('feedback_id', $id)->first()->is_selected = true;

        return view('dashboard.report.responseFeedback', [
            'feedback_item' => $feedback,
            'feedbacks' => $feedbacks,
            'users' => $users,
            'colors' => $this->colors(),
        ]);
    }

    public function responseFeedback($id, Request $request)
    {
        $feedback = Feedback::query()->findOrFail($id);
        $responseText = $request->input('response', '');
        $oldResponse = $feedback->feedbackResponse()->first();
        $isSpam = $request->state == FeedbackStatus::SPAM;
        if (trim($responseText) == '' and !$isSpam) {
            return redirect()->back()->withErrors('پاسخ بازخورد نباید خالی باشد.');
        }
        $smsFlag = true;
        if ($feedback->feedback_response_id or $isSpam) {
            $smsFlag = false;
        }
        if (trim($responseText) != '') {
            /** @var FeedbackResponse $feedbackResponse */
            $feedbackResponse = FeedbackResponse::updateOrCreate([
                'id' => $feedback->feedback_response_id,
            ], [
                'panel_user_id' => auth()->id(),
                'text' => trim($responseText),
                'response_updated_at' => now(),
                'read_at' => null,
            ]);
            $feedback->feedback_response_id = $feedbackResponse->id;
            $feedback->save();

            if ($request->delete_image) {
                $feedbackResponse->images()->delete();
            }
            if ($request->hasFile('response_images')) {
                foreach ($request->file('response_images') as $image) {
                    $image->storeAs('/', $image->getClientOriginalName());
                    $http = new Client;
                    $response = $http->post(
                        env('TANKHAH_URL') . '/panel/' . env('TANKHAH_TOKEN') . '/feedback/' . $id . '/image',
                        [
                            'headers' => [
                                'Accept' => 'application/json',
                            ],
                            'multipart' => [
                                [
                                    'name' => 'image',
                                    'filename' => $image->getClientOriginalName(),
                                    'contents' => file_get_contents(storage_path() . '/app/' . $image->getClientOriginalName()),
                                ],
                            ],
                        ]
                    );
                    Storage::delete('/' . $image->getClientOriginalName());
                }
            }
        }
        $feedback->state = $request->state;
        $feedback->save();

        if ($smsFlag) {
            $user = $feedback->user()->first();
            $this->dispatch((new FeedbackResponseSms($user))->onQueue('activationSms'));
        }

        /** @var PanelUser $panelUser */
        $panelUser = auth()->user();
        $type = $id ? LogType::EDIT_FEEDBACK : LogType::RESPONSE_FEEDBACK;
        $panelUser->logs()->create([
            'user_id' => $feedback->user_id,
            'type' => $type,
            'date_time' => now()->toDateTimeString(),
            'description' => LogType::getDescription($type, $panelUser),
            'old_json' => $oldResponse,
            'new_json' => $feedback->feedbackResponse()->first() ?? json_encode([]),
        ]);

        return redirect()->route('dashboard.viewFeedback', ['feedback_id' => $id])->with('success', 'با موفقیت انجام شد');
    }

    public function viewNotification()
    {
        $advertisements = Advertisement::join('panel_users', 'panel_user_id', '=', 'panel_users.id')->get([
            'panel_users.name as full_name',
            'advertisements.*',
        ]);
        $advertisements->map(function ($item) {
            $item['date'] = Helpers::convertDateTimeToJalali($item['created_at']);
            $item['expire_time'] = Helpers::convertDateTimeToJalali($item['expire_time']);
        });

        return view('admin.notifications', compact('advertisements'));
    }

    public function notificationView($id = null)
    {
        $advertisement = collect([
            'id' => $id,
            'title' => null,
            'text' => null,
            'link' => null,
            'expire_time' => null,
        ]);
        if ($id) {
            $advertisement = Advertisement::findOrFail($id);
            $advertisement['expire_time'] = Helpers::convertDateTimeToJalali($advertisement->expire_time);
        }
        $today = Helpers::convertDateTimeToJalali(now()->toDateTimeString());
        return view('admin.notification', compact('advertisement', 'today'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function postNotification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'expire_date' => 'date_format:Y/m/d H:i:s',
            'link' => 'url',
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        $expiredDate = Carbon::parse($request->expire_date);
        $expiredDate = Carbon::parse(
            Helpers::jalaliDateStringToGregorian($expiredDate->toDateString(), '-') .
            ' ' .
            $expiredDate->toTimeString()
        );
        $users = User::all();
        /** @var Advertisement $advertisement */
        $advertisement = Advertisement::updateOrCreate([
            'id' => (int)$request->advertisement_id,
        ], [
            'title' => $request->title,
            'text' => $request->text,
            'link' => $request->link,
            'expire_time' => $expiredDate,
            'panel_user_id' => auth()->id(),
        ]);
        foreach ($users as $user) {
            if ($request->advertisement_id) {
                /** @var User $user */
                $user->notifications()->where('data->advertisement_id', $advertisement->id)->update([
                    'data->title' => $advertisement->title,
                    'data->message' => $advertisement->text,
                    'data->link' => $advertisement->link,
                    'data->expired_at' => $advertisement->expire_time,
                    'expired_at' => $advertisement->expire_time,
                ]);
            } else {
                Notification::send(
                    $user,
                    new AdvertisementNotification(
                        $advertisement->title,
                        $advertisement->text,
                        $advertisement->link,
                        $advertisement->expire_time,
                        $advertisement->id
                    )
                );
            }
        }
        return redirect()->intended('panel/notifications')->with('success', trans('message.success'));
    }

    public function deleteNotification($id)
    {
        $expiredDate = now();
        $users = User::all();
        foreach ($users as $user) {
            $user->notifications()->where('data->advertisement_id', (int)$id)->update([
                'data->expired_at' => $expiredDate,
                'expired_at' => $expiredDate,
            ]);
        }
        Advertisement::where('id', $id)->update([
            'expire_time' => $expiredDate,
        ]);
        return redirect()->intended('panel/notifications')->with('success', trans('message.success'));
    }

    public function changePasswordView()
    {
        return view('dashboard.changePassword');
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        /** @var PanelUser $user */
        $user = auth()->user();
        if (!Hash::check($request->old_password, PanelUser::where('id', $user->id)->first()->password)) {
            $validator->errors()->add('old_password', 'password wrong!');
            return redirect()
                ->back()
                ->withInput($request->all())
                ->withErrors($validator);
        }
        PanelUser::where('id', $user->id)->update([
            'password' => Hash::make($request->password),
        ]);
        return redirect()->route('dashboard.home')->with('success', trans('message.password_changed_successfully'));
    }

    public function exportAllUserActivity(Request $request)
    {
        $loggedInUser = auth()->user();
        if (!$loggedInUser->hasPermissionTo('all_user_activity_full')) {
            return redirect()->back();
        }
        $cacheID = 'panel_all_user_activity';
        if (cache()->has($cacheID)) {
            $users = cache()->get($cacheID);
            $sort = null;
            $users = $users['users'];
            $this->filterAndSort($users, $sort, $request);
            $uuid = Uuid::uuid();
            (new PanelUserExport($users, true))->store('exports/' . $uuid . '.xlsx', 'public');
            return redirect()->to(url('storage/exports/' . $uuid . '.xlsx'));
        } else {
            return redirect()->back();
        }
    }

    private function filterAndSort(&$collection, &$sort, Request &$request)
    {
        $activationType = $request->input('activation_type', null);
        if ($activationType) {
            $collection = $collection->where('activationType', $activationType);
        }
        $sort = [$request->input('sort_field', 'registered_at'), $request->input('sort_type', 'DESC')];
        if ($sort[0] == 'name') {
            $collection = $collection->sortBy('name', SORT_REGULAR, $sort[1] == 'DESC')
                ->sortBy('family', SORT_REGULAR, $sort[1] == 'DESC');
        } else {
            $collection = $collection->sortBy($sort[0], SORT_REGULAR, $sort[1] == 'DESC');
        }
        $sort[1] = $sort[1] == 'DESC' ? 'ASC' : 'DESC';
    }

    public function sendSms(Request $request)
    {
        $phoneNumber = Helpers::formatPhoneNumber(Helpers::getEnglishString($request->phone_number));
        $text = $request->text;
        try {
            $result = Kavenegar::Send('10005000000550', $phoneNumber, $text);
            return redirect()->back()->with('success', 'با موفقیت ارسال شد');
        } catch (Exception $exception) {
            return redirect()->back()->withInput($request->all())->withException($exception);
        }
    }

    public function extractUserIds(Request $request)
    {
        $path1 = $request->file('users')->store('temp');
        $path = storage_path('app') . '/' . $path1;
        $userImport = new ConvertToUser();
        Excel::import($userImport, $path);
        $userIds = $userImport->data->unique()->toArray();

        $errors = $userImport->errors->toArray();

        if ($errors != []) {
            $validator = Validator::make([], []);
            foreach ($errors as $error) {
                $validator->errors()->add('error', "مشکل در شماره {$error}");
            }
            return redirect()->back()->withErrors($validator);
        }

        return redirect()
            ->route('dashboard.report.allUsersActivity', ['user_ids' => $userIds])
            ->with('success', 'با موفقیت انجام شد');
    }

    public function userExtendReport(Request $request)
    {
        $filter = [
            'start_day' => $request->input('start_day', 5),
            'end_day' => $request->input('end_day', 10),
            'start_user' => $request->input('start_user', 0),
            'end_user' => $request->input('end_user', 100),
            'start_volume' => $request->input('start_volume', 0),
            'end_volume' => $request->input('end_volume', 100000),
        ];
        $logs = UserStatusLog::query()
            ->with('user')
            ->whereIn('price_id', [PremiumDuration::YEAR, PremiumDuration::MONTH, PremiumDuration::SPECIAL])
            ->where('status', 1)
            ->orderBy('created_at')
            ->get();
        $logs = $logs->groupBy('user_id');
        $results = collect();
        $now = now()->toDateTimeString();
        foreach ($logs as $items) {
            $lastItem = $items->pop();
            if ($lastItem->end_date < $now) {
                /** @var User $user */
                $user = $lastItem->user;
                $userCount = $lastItem->user_count;
                $volumeSize = $lastItem->volume_size;
                $day = now()->diffInDays(Carbon::parse($lastItem->end_date));
                if ($lastItem->type == PurchaseType::UPGRADE) {
                    $preLast = $items->last();
                    $userCount += $preLast->user_count;
                    $volumeSize += $preLast->volume_size;
                }
                if ($day > $filter['end_day'] or $day < $filter['start_day']) {
                    continue;
                }
                if ($userCount > $filter['end_user'] or $userCount < $filter['start_user']) {
                    continue;
                }
                if ($volumeSize > $filter['end_volume'] or $volumeSize < $filter['start_volume']) {
                    continue;
                }
                $results->push([
                    'id' => $user->id,
                    'phone_number' => $user->phone_number,
                    'username' => ($user->name or $user->family) ? "$user->name $user->family" : '',
                    'price' => PremiumDuration::getSecondTitle($lastItem->price_id),
                    'user_count' => $userCount,
                    'volume_size' => $volumeSize,
                    'days' => $day,
                    'end_date' => $lastItem->end_date,
                ]);
            }
        }
        $filter['start_user'] = $results->min('user_count');
        $filter['end_user'] = $results->max('user_count');
        $filter['start_volume'] = $results->min('volume_size');
        $filter['end_volume'] = $results->max('volume_size');
        $results = $results->sortBy('days')->values();
        $users = Helpers::paginateCollection(
            $results,
            100
        );
//        return $results;
        return view('dashboard.report.userExtend', [
            'users' => $users,
            'filter' => $filter,
        ]);
    }
}
