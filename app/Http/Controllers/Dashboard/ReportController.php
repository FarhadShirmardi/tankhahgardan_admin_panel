<?php

namespace App\Http\Controllers\Dashboard;

use App\Advertisement;
use App\City;
use App\Device;
use App\Feedback;
use App\FeedbackResponse;
use App\Helpers\Helpers;
use App\Image;
use App\Imprest;
use App\Note;
use App\PanelUser;
use App\Payment;
use App\Project;
use App\Receive;
use App\State;
use App\User;
use Cache;
use Carbon\Carbon;
use DB;
use Faker\Provider\Uuid;
use Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Notification;
use Validator;
use App\File;
use App\ProjectUser;
use App\StepByStep;
use Illuminate\Database\Eloquent\Model;
use App\Constants\ProjectUserState;
use App\Constants\FeedbackSource;
use App\Comment;
use App\Constants\FeedbackStatus;
use App\FeedbackTitle;

class ReportController extends Controller
{
    public function timeSeparation(Request $request)
    {
        list($startDate, $endDate) = $this->normalizeDate($request);

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

    protected function normalizeDate(Request &$request, $setNull = false)
    {
        $startDate = $request->input('start_date', null);
        if ($startDate) {
            $startDate = Helpers::jalaliDateStringToGregorian(Helpers::getEnglishString($startDate));
        } elseif (!$setNull) {
            $startDate = now()->subDays(7)->toDateString();
        }
        $endDate = $request->input('end_date', null);
        if ($endDate) {
            $endDate = Helpers::jalaliDateStringToGregorian(Helpers::getEnglishString($endDate));
        }
        $startDate = $startDate ? str_replace('/', '-', $startDate) : null;
        $endDate = $endDate ? str_replace('/', '-', $endDate) : null;
        return [$startDate, $endDate];
    }

    public function daySeparation(Request $request)
    {
        list($startDate, $endDate) = $this->normalizeDate($request);

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
            'end_date' => $endDate
        ]);
    }

    public function rangeSeparation(Request $request)
    {
        list($startDate, $endDate) = $this->normalizeDate($request);

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
            'end_date' => $endDate
        ]);
    }

    public function allUsersActivity(Request $request)
    {
        $filter = [
            'user_type' => $request->input('user_type', null),
            'sort_field' => $request->input('sort_field', 'registered_at'),
            'sort_type' => $request->input('sort_type', 'DESC'),
            'phone_number' => Helpers::getEnglishString($request->input('phone_number', '')),
            'name' => $request->input('name', ''),
        ];

        $paymentCountQuery = Payment::whereColumn('creator_user_id', 'users.id')->withoutTrashed()->selectRaw('count(*)')->getQuery();
        $receiveCountQuery = Receive::whereColumn('creator_user_id', 'users.id')->withoutTrashed()->selectRaw('count(*)')->getQuery();
        $noteCountQuery = Note::whereColumn('creator_user_id', 'users.id')->withoutTrashed()->selectRaw('count(*)')->getQuery();
        $imprestCountQuery = Imprest::whereColumn('creator_user_id', 'users.id')->withoutTrashed()->selectRaw('count(*)')->getQuery();
        $fileCountQuery = File::whereColumn('creator_user_id', 'users.id')->selectRaw('count(*)')->getQuery();
        $imageCountQuery = Image::whereColumn('user_id', 'users.id')->selectRaw('count(*)')->getQuery();
        $deviceCountQuery = Device::whereColumn('user_id', 'users.id')->selectRaw('count(*)')->getQuery();
        $feedbackCountQuery = Feedback::whereColumn('user_id', 'users.id')->selectRaw('count(*)')->getQuery();

        $paymentMaxQuery = Payment::whereColumn('creator_user_id', 'users.id')->withoutTrashed()->selectRaw('MAX(created_at)')->toSql();
        $receiveMaxQuery = Receive::whereColumn('creator_user_id', 'users.id')->withoutTrashed()->selectRaw('MAX(created_at)')->toSql();
        $noteMaxQuery = Note::whereColumn('creator_user_id', 'users.id')->withoutTrashed()->selectRaw('MAX(created_at)')->toSql();
        $imprestMaxQuery = Imprest::whereColumn('creator_user_id', 'users.id')->withoutTrashed()->selectRaw('MAX(created_at)')->toSql();

        $imageSizeQuery = Image::whereColumn('user_id', 'users.id')->selectRaw('sum(size) / 1024 / 1024')->getQuery();

        $projectCount = ProjectUser::whereColumn('user_id', 'users.id')->withoutTrashed()->selectRaw('count(*)')->getQuery();
        $ownProjectCount = ProjectUser::whereColumn('user_id', 'users.id')->withoutTrashed()->where('is_owner', true)->selectRaw('count(*)')->getQuery();
        $notOwnProjectCount = ProjectUser::whereColumn('user_id', 'users.id')->withoutTrashed()->where('is_owner', false)->selectRaw('count(*)')->getQuery();

        $stepByStep = StepByStep::whereColumn('user_id', 'users.id')->selectRaw('IFNULL(step, 0)')->getQuery();

        $maxTimeQuery = User::query()
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


        $usersQuery = User::query()
            ->joinSub($maxTimeQuery, 'MaxTime', 'MaxTime.user_id', '=', 'users.id')
            ->selectRaw("CONCAT_WS(' ', IFNULL(users.name, ''), IFNULL(users.family, '')) as name")
            ->addSelect('users.id as id')
            ->addSelect('phone_number')
            ->addSelect('users.created_at as registered_at')
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
            ->selectRaw('MaxTime.max_time as max_time')
            ->selectRaw($userTypeQuery)
            ->orderBy($filter['sort_field'], $filter['sort_type']);

        if ($filter['user_type']) {
            $usersQuery = $usersQuery->having('user_type', $filter['user_type']);
        }
        if (!empty($filter['phone_number'])) {
            $phoneNumber = '%' . Helpers::getEnglishString((string)(int)$filter['phone_number']) . '%';
            $usersQuery = $usersQuery->where('phone_number', 'like', $phoneNumber);
        }
        if (!empty($filter['name'])) {
            $name = '%' . $filter['name'] . '%';
            $usersQuery = $usersQuery->where('name', 'like', $name);
        }

        $users = $usersQuery->get();


        $counts = array_fill(1, 4, 0);
        $countUser = $users->groupBy('user_type');
        foreach ($countUser as $key => $item) {
            $counts[$key] = count($item);
        }

        $users = Helpers::paginateCollection($users);


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

        return view('dashboard.report.allUserActivity', [
                'users' => $users,
                'counts' => $counts,
                'colors' => $this->colors(),
                'filter' => $filter,
                'sortable_fields' => $sortableFields,
                'sortable_types' => $sortableTypes
            ]
        );
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

    public function colors()
    {
        return [
            1 => ['#BEDACE', 'یک هفته'],
            2 => ['#DCE1FF', 'دو هفته'],
            3 => ['#FBE9E7', 'یک ماه'],
            4 => ['#F1F3F4', 'غیرفعال'],
        ];
    }

    public function userActivity(Request $request, $id)
    {
        $user = User::with('projects')->findOrFail($id);

        $paymentCountQuery = Payment::whereColumn('project_id', 'projects.id')->where('creator_user_id', $id)
            ->withoutTrashed()->selectRaw('count(*)')->getQuery();
        $receiveCountQuery = Receive::whereColumn('project_id', 'projects.id')->where('creator_user_id', $id)
            ->withoutTrashed()->selectRaw('count(*)')->getQuery();
        $noteCountQuery = Note::whereColumn('project_id', 'projects.id')->where('creator_user_id', $id)
            ->withoutTrashed()->selectRaw('count(*)')->getQuery();
        $imprestCountQuery = Imprest::whereColumn('project_id', 'projects.id')->where('creator_user_id', $id)
            ->withoutTrashed()->selectRaw('count(*)')->getQuery();

        $projectsQuery = Project::query()
            ->join('project_user', function ($join) use ($id) {
                $join->on('project_user.project_id', 'projects.id')
                    ->where('project_user.user_id', $id);
            })
            ->addSelect('project_user.is_owner as is_owner')
            ->addSelect('projects.name as name')
            ->addSelect('projects.id as id')
            ->selectSub($paymentCountQuery, 'payment_count')
            ->selectSub($receiveCountQuery, 'receive_count')
            ->selectSub($noteCountQuery, 'note_count')
            ->selectSub($imprestCountQuery, 'imprest_count')
            ->orderBy('projects.created_at');

        $projects = $projectsQuery->get();

        $rangeCounts = [];
        foreach (range(0, 5) as $time) {
            $paymentSubQuery = Payment::where('creator_user_id', $id)
                ->whereRaw('substr(created_at, 12, 2) between ' . $time * 4 . ' AND ' . ($time + 1) * 4)->withoutTrashed()->selectRaw('count(*)')->getQuery();
            $receiveSubQuery = Receive::where('creator_user_id', $id)
                ->whereRaw('substr(created_at, 12, 2) between ' . $time * 4 . ' AND ' . ($time + 1) * 4)->withoutTrashed()->selectRaw('count(*)')->getQuery();
            $noteSubQuery = Note::where('creator_user_id', $id)
                ->whereRaw('substr(created_at, 12, 2) between ' . $time * 4 . ' AND ' . ($time + 1) * 4)->withoutTrashed()->selectRaw('count(*)')->getQuery();
            $imprestSubQuery = Imprest::where('creator_user_id', $id)
                ->whereRaw('substr(created_at, 12, 2) between ' . $time * 4 . ' AND ' . ($time + 1) * 4)->withoutTrashed()->selectRaw('count(*)')->getQuery();

            $result = DB::query()
                ->selectSub($paymentSubQuery, 'payments_count')
                ->selectSub($receiveSubQuery, 'receives_count')
                ->selectSub($noteSubQuery, 'notes_count')
                ->selectSub($imprestSubQuery, 'imprests_count')
                ->get();

            $rangeCounts[($time * 4) . ' - ' . (($time + 1) * 4)] = $result->first();
        }

        $paymentSubQuery = Payment::where('creator_user_id', $id)->withoutTrashed()->selectRaw('project_id, substr(created_at, 1, 10) as date')->getQuery();
        $receiveSubQuery = Receive::where('creator_user_id', $id)->withoutTrashed()->selectRaw('project_id, substr(created_at, 1, 10) as date')->getQuery();
        $noteSubQuery = Note::where('creator_user_id', $id)->withoutTrashed()->selectRaw('project_id, substr(created_at, 1, 10) as date')->getQuery();
        $imprestSubQuery = Imprest::where('creator_user_id', $id)->withoutTrashed()->selectRaw('project_id, substr(created_at, 1, 10) as date')->getQuery();

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
                'data' => $data->toJson()
            ]);
        }
        $dateCounts = $counts;
        $dates = $dates->map(function ($item) {
            return Helpers::gregorianDateStringToJalali($item);
        });

        return view('dashboard.report.userActivity', [
            'user' => $user,
            'projects' => $projects,
            'range_counts' => $rangeCounts,
            'date_counts' => $dateCounts,
            'dates' => $dates
        ]);
    }

    public function allProjectsActivity(Request $request)
    {

        $filter = [
            'project_type' => $request->input('project_type', null),
            'sort_field' => $request->input('sort_field', 'created_at'),
            'sort_type' => $request->input('sort_type', 'DESC'),
            'state_id' => $request->input('state_id', 0),
            'city_id' => $request->input('city_id', 0),
            'name' => $request->input('name', ''),
        ];

        $paymentCountQuery = Payment::whereColumn('project_id', 'projects.id')->withoutTrashed()->selectRaw('count(*)')->getQuery();
        $receiveCountQuery = Receive::whereColumn('project_id', 'projects.id')->withoutTrashed()->selectRaw('count(*)')->getQuery();
        $noteCountQuery = Note::whereColumn('project_id', 'projects.id')->withoutTrashed()->selectRaw('count(*)')->getQuery();
        $imprestCountQuery = Imprest::whereColumn('project_id', 'projects.id')->withoutTrashed()->selectRaw('count(*)')->getQuery();

        $paymentMaxQuery = Payment::whereColumn('project_id', 'projects.id')->withoutTrashed()->selectRaw('MAX(created_at)')->toSql();
        $receiveMaxQuery = Receive::whereColumn('project_id', 'projects.id')->withoutTrashed()->selectRaw('MAX(created_at)')->toSql();
        $noteMaxQuery = Note::whereColumn('project_id', 'projects.id')->withoutTrashed()->selectRaw('MAX(created_at)')->toSql();
        $imprestMaxQuery = Imprest::whereColumn('project_id', 'projects.id')->withoutTrashed()->selectRaw('MAX(created_at)')->toSql();

        $userCount = ProjectUser::whereColumn('project_id', 'projects.id')->withoutTrashed()->selectRaw('count(*)')->getQuery();
        $activeUserCount = ProjectUser::whereColumn('project_id', 'projects.id')->withoutTrashed()->where('state', ProjectUserState::ACTIVE)->selectRaw('count(*)')->getQuery();
        $notActiveUserCount = ProjectUser::whereColumn('project_id', 'projects.id')->withoutTrashed()->where('state', '<>', ProjectUserState::ACTIVE)->selectRaw('count(*)')->getQuery();

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


        $projectsQuery = Project::query()
            ->joinSub($maxTimeQuery, 'MaxTime', 'MaxTime.project_id', '=', 'projects.id')
            ->addSelect('projects.name as name')
            ->addSelect('projects.id as id')
            ->addSelect('projects.city_id as city_id')
            ->addSelect('projects.state_id as state_id')
            ->addSelect('projects.created_at as created_at')
            ->selectSub($paymentCountQuery, 'payment_count')
            ->selectSub($receiveCountQuery, 'receive_count')
            ->selectSub($noteCountQuery, 'note_count')
            ->selectSub($imprestCountQuery, 'imprest_count')
            ->selectSub($userCount, 'user_count')
            ->selectSub($activeUserCount, 'active_user_count')
            ->selectSub($notActiveUserCount, 'not_active_user_count')
            ->selectRaw('MaxTime.max_time as max_time')
            ->selectRaw($projectTypeQuery)
            ->orderBy($filter['sort_field'], $filter['sort_type']);

        if ($filter['project_type']) {
            $projectsQuery = $projectTypeQuery->having('project_type', $filter['project_type']);
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

        $projects = $projectsQuery->get();

        $counts = array_fill(1, 4, 0);
        $countProject = $projects->groupBy('project_type');
        foreach ($countProject as $key => $item) {
            $counts[$key] = count($item);
        }

        $projects = Helpers::paginateCollection($projects);

        list($states, $cities) = $this->getLocations();

        $sortableFields = [
            'name' => 'نام پروژه',
            'created_at' => 'تاریخ ایجاد پروژه',
            'max_time' => 'آخرین ثبت',
            'user_count' => 'تعداد کاربران پروژه',
            'active_user_count' => 'تعداد کاربران فعال',
            'not_active_user_count' => 'تعداد کاربران غیرفعال',
            'payment_count' => 'تعداد پرداخت',
            'receive_count' => 'تعداد دریافت',
            'note_count' => 'تعداد یادداشت',
            'imprest_count' => 'تعداد تنخواه',
        ];

        $sortableTypes = [
            'ASC' => 'صعودی',
            'DESC' => 'نزولی',
        ];

        return view('dashboard.report.allProjectActivity', [
            'projects' => $projects,
            'counts' => $counts,
            'states' => $states,
            'cities' => $cities,
            'filter' => $filter,
            'colors' => $this->colors(),
            'sortable_fields' => $sortableFields,
            'sortable_types' => $sortableTypes
        ]);
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

        $usersQuery = User::query()
            ->join('project_user', function ($join) use ($projectId) {
                $join->on('project_user.user_id', 'users.id')
                    ->where('project_user.project_id', $projectId);
            })
            ->addSelect('project_user.is_owner as is_owner')
            ->addSelect('users.name as name')
            ->addSelect('users.family as family')
            ->addSelect('users.phone_number as phone_number')
            ->addSelect('users.id as id')
            ->selectSub($paymentCountQuery, 'payment_count')
            ->selectSub($receiveCountQuery, 'receive_count')
            ->selectSub($noteCountQuery, 'note_count')
            ->selectSub($imprestCountQuery, 'imprest_count')
            ->orderBy('users.created_at');

        $users = $usersQuery->get();

        return view('dashboard.report.projectActivity', [
            'project' => $project,
            'users' => $users
        ]);
    }

    public function viewFeedback(Request $request)
    {
        list($startDate, $endDate) = $this->normalizeDate($request, true);
        if (!$startDate) {
            $startDate = Feedback::query()->selectRaw('min(Date(created_at)) as date')->first()->date;
        }

        $feedbackTitles = FeedbackTitle::all(['id'])->pluck('id')->toArray();

        $filter = [
            'source_type' => $request->input('source_type', FeedbackSource::toArray()),
            'titles' => $request->input('titles', $feedbackTitles),
            'user_id' => $request->input('user_id', null),
            'panel_user_ids' => $request->input('panel_user_ids', []),
            'sort_field_1' => $request->input('sort_field_1', 'source'),
            'sort_type_1' => $request->input('sort_type_1', 'asc'),
            'sort_field_2' => $request->input('sort_field_2', 'date'),
            'sort_type_2' => $request->input('sort_type_2', 'desc'),
            'start_date' => $startDate,
            'end_date' => $endDate
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
                DB::raw("false as is_selected")
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


        $feedbacks = Helpers::paginateCollection($feedbacks, 10);

        $sortableFields = [
            'date' => 'تاریخ',
            'title' => 'عنوان',
            'source' => 'منبع',
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
            'panel_users' => $panelUsers
        ]);
    }

    private function fetchFeedbacks($filter)
    {
        $feedbackSubQuery = Feedback::query()
            ->join('feedback_titles', 'feedback_titles.id', '=', 'feedback_title_id')
            ->join('users', 'users.id', '=', 'feedback.user_id')
            ->leftJoin('feedback_responses', 'feedback_responses.id', '=', 'feedback_response_id')
            ->leftJoin('panel_users', 'feedback_responses.panel_user_id', '=', 'panel_users.id')
            ->where(function ($query) use ($filter) {
                if (isset($filter['titles']) and $filter['titles'] != []) {
                    $query->whereIn('feedback_titles.id', $filter['titles']);
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
                'feedback.text as text',
                'users.id as user_id',
                'users.name as user_name',
                'users.family as user_family',
                'users.phone_number as user_phone_number',
                DB::raw("'" . FeedbackSource::APPLICATION . "'" . ' as source'),
            ])->getQuery();

        $commentSubQuery = Comment::query()
            ->leftJoin('feedback_titles', 'feedback_titles.id', '=', 'feedback_title_id')
            ->leftJoin('panel_users', 'comments.user_id', '=', 'panel_users.id')
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
                'comments.text as text',
                'comments.user_id as user_id',
                DB::raw('"" as user_family'),
                'comments.name as user_name',
                'comments.phone_number as user_phone_number',
                'comments.source',
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
                    $query->where('user_id', $filter['user_id'])
                        ->orWhereNull('user_id');
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
            $item->response_text_update_time = Helpers::convertDateTimeToJalali($item->response_text_update_time);
            $item->state = FeedbackStatus::getEnum($item->state);
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

        $feedbacks = $filter['user_id'] ? $this->fetchFeedbacks($filter) : [];

        $comment = Comment::find($id);
        if (isset($comment['date'])) {
            $comment['date'] = Helpers::convertDateTimeToJalali($comment->date);
        }
        if (isset($comment['response_date'])) {
            $comment['response_date'] = Helpers::convertDateTimeToJalali($comment->response_date);
        }
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

        $selectedUser = collect();
        $users = collect();

        if ($filter['phone_number']) {
            $users = User::query()
                ->where('phone_number', 'like', '%' . $filter['phone_number'] . '%')
                ->where('state', 1)
                ->selectRaw('false as is_selected, id, name, family, phone_number')->get();

            $users->where('id', $filter['user_id'])->first()['is_selected'] = true;
            if ($users->count() == 1 or $filter['user_id']) {
                $selectedUser = $users->first();
            }
        }

        return view('dashboard.report.newComment', [
            'id' => $id,
            'feedbacks' => $feedbacks,
            'comment' => $comment,
            'panel_users' => $panelUsers,
            'users' => $users,
            'source_types' => $sourceTypes,
            'feedback_titles' => $feedbackTitles,
            'filter' => $filter,
            'selected_user' => $selectedUser
        ]);
    }

    public function addComment(Request $request, $id = null)
    {
        $request->merge([
            'state' => FeedbackStatus::CLOSED
        ]);
        $request['date'] =
            Helpers::convertDateTimeToGregorian(Helpers::getEnglishString($request->date));
        $request['response_date'] = $request->response_date ?
            Helpers::convertDateTimeToGregorian(Helpers::getEnglishString($request->response_date)) : null;
        if (!$id) {
            Comment::create($request->all());
        } else {
            $comment = Comment::findOrFail($id);
            $comment->update($request->all());
        }

        return redirect()->route('dashboard.commentView')->with('success', 'با موفقیت انجام شد.');
    }

    public function responseFeedbackView($id)
    {
        $feedback = Feedback::query()->findOrFail($id);
        $filter = [
            'user_id' => $feedback->user_id,
            'sort_field_1' => 'date',
            'sort_type_1' => 'desc',
            'sort_field_2' => 'date',
            'sort_type_2' => 'desc',
        ];

        $feedbacks = $this->fetchFeedbacks($filter);

        $feedback = $feedbacks->where('feedback_id', $id)->first();

        $feedbacks->where('feedback_id', $id)->first()->is_selected = true;

        return view('dashboard.report.responseFeedback', [
            'feedback_item' => $feedback,
            'feedbacks' => $feedbacks
        ]);
    }

    public function responseFeedback($id, Request $request)
    {
        $feedback = Feedback::query()->findOrFail($id);
        $responseText = $request->input('response', '');
        if (trim($responseText) == '') {
            return redirect()->back()->withErrors('پاسخ بازخورد نباید خالی باشد.');
        }
        $feedbackResponse = FeedbackResponse::updateOrCreate([
            'id' => $feedback->feedback_response_id,
        ], [
            'panel_user_id' => auth()->id(),
            'text' => trim($responseText),
            'response_updated_at' => now(),
            'read_at' => null,
        ]);
        $feedback->feedback_response_id = $feedbackResponse->id;
        $feedback->state = $request->state;
        $feedback->save();

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
}
