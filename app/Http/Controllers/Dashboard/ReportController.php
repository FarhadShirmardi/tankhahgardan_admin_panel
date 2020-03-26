<?php

namespace App\Http\Controllers\Dashboard;

use App\Advertisement;
use App\City;
use App\Device;
use App\Exports\PanelUserExport;
use App\Feedback;
use App\FeedbackResponse;
use App\Helpers\Helpers;
use App\Http\Controllers\Api\V1\Constants\NotificationExpireTime;
use App\Http\Controllers\Api\V1\Constants\ProjectUserState;
use App\Image;
use App\Imprest;
use App\Note;
use App\Notifications\AdvertisementNotification;
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

    protected function normalizeDate(Request &$request)
    {
        $startDate = $request->input('start_date', null);
        if ($startDate) {
            $startDate = Helpers::jalaliDateStringToGregorian(Helpers::getEnglishString($startDate));
        } else {
            $startDate = now()->subDays(7)->toDateString();
        }
        $endDate = $request->input('end_date', null);
        if ($endDate) {
            $endDate = Helpers::jalaliDateStringToGregorian(Helpers::getEnglishString($endDate));
        }
        $startDate = str_replace('/', '-', $startDate);
        $endDate = str_replace('/', '-', $endDate);
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
        /** @var PanelUser $loggedInUser */
        $loggedInUser = auth()->user();
        $cacheID = 'panel_all_user_activity';
        if ($request->input('clean_cache')) {
            $this->cleanCache($cacheID, 'users');
            return redirect(url()->current());
        }
        $data = null;
        $counts = [
            '1' => 0,
            '2' => 0,
            '3' => 0,
            '4' => 0,
        ];
        if (cache()->has($cacheID)) {
            $data = cache()->get($cacheID);
            $users = $data['users'];
            $projectNames = $data['projectNames'];
        } else {
            $projectNames = [];
            $users = User::all();
            $this->generateUserData($users, $cacheID);
        }
        $colors = $this->colors();
        $sort = null;
        $this->filterAndSort($users, $sort, $request);
        foreach ($users as $user) {
            $counts[$user['activationType']]++;
        }
        $users = Helpers::paginateCollection($users, 100);
        return view('admin.allUserActivity', compact('users', 'projectNames',
            'counts', 'colors', 'sort'));
    }

    public function cleanCache($cacheID, $key)
    {
        $data = cache()->get($cacheID);
        if (isset($data[$key])) {
            foreach ($data[$key] as $item) {
                cache()->forget($cacheID . '_' . $item->id);
            }
        }
        cache()->forget($cacheID);
    }

    public function generateUserData(&$users, $cacheID)
    {
        $cacheTime = now()->addHours(25);
        $data = null;
        $projectNames = [];
        foreach ($users as $key => $user) {
            if (cache()->has($cacheID . '_' . $user->id)) {
                $users[$key] = cache()->get($cacheID . '_' . $user->id);
                continue;
            } else {
                /** @var User $user */
                $projects = $user->projects()->pluck('projects.id');
                $ownedProjects = $user->projects()->where('is_owner', true)->count();
                $notOwnedProjects = $user->projects()->where('is_owner', false)->count();
                $projectNames = $user->projects()->get(['projects.id', 'name']);
                $payment = Payment::whereIn('project_id', $projects)
                    ->where('creator_user_id', $user->id)->get();
                $receive = Receive::whereIn('project_id', $projects)
                    ->where('creator_user_id', $user->id)->get();
                $note = Note::whereIn('project_id', $projects)
                    ->where('creator_user_id', $user->id)->get();
                $imprest = Imprest::whereIn('project_id', $projects)
                    ->where('creator_user_id', $user->id)->get();
                $image = Image::where('user_id', $user->id)->count();
                $imageSize = Image::where('user_id', $user->id)->sum('size');
                $file = Imprest::whereIn('project_id', $projects)
                    ->where('creator_user_id', $user->id)->get(['created_at']);
                $device = Device::where('user_id', $user->id)->count();
                $feedback = Feedback::where('user_id', $user->id)->get();
                $stepByStep = $user->stepByStep()->first();
                $stepByStep = $stepByStep ? $stepByStep->step : 0;
                $items = collect();
                $items = $items->merge($payment)
                    ->merge($receive)
                    ->merge($imprest)
                    ->merge($note);
                $maxTime = $items->max('created_at');
                $times = $this->times();
                $rangeCount = 1;
                $user['activationType'] = 4;
                foreach ($times as $time) {
                    $count = $items->where('created_at', $time[0], $time[1])->count();
                    if ($count >= $rangeCount) {
                        $user['activationType'] = $time[2];
                        break;
                    }
                }

                $user['max_time'] = $maxTime == null ? null : ($maxTime->format('m/d/Y H:i:s'));
                $user['registered_at'] = ($user['created_at']->format('m/d/Y H:i:s'));
                $user['projectId'] = $projects;
                $user['project'] = count($projects);
                $user['owned_projects'] = $ownedProjects;
                $user['not_owned_projects'] = $notOwnedProjects;
                $user['payment'] = count($payment);
                $user['receive'] = count($receive);
                $user['imprest'] = count($imprest);
                $user['note'] = count($note);
                $user['file'] = count($file);
                $user['image'] = $image;
                $user['image_size'] = $imageSize / 1024 / 1024;
                $user['feedback'] = count($feedback);
                $user['device'] = $device;
                $user['step_by_step'] = $stepByStep;
            }
            if (!cache()->has($cacheID . '_' . $user->id)) {
                cache()->put(
                    $cacheID . '_' . $user->id,
                    $user,
                    $cacheTime
                );
            }
        }
        $data = [];
        $data['users'] = $users;
        $data['projectNames'] = $projectNames;
        $data['user_ids'] = $users->pluck('id');
        cache()->put(
            $cacheID,
            $data,
            $cacheTime
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

    public function userActivity(Request $request, $id)
    {
        $cacheID = 'panel_one_user_activity_' . $id;
        if ($request->input('clean_cache')) {
            cache()->forget($cacheID);
        }
        if (Cache::has($cacheID)) {
            $cacheData = Cache::get($cacheID);
            $user = $cacheData['users'];
            $count = $cacheData['count'];
            $data = $cacheData['data'];
            $tafkikData = $cacheData['tafkikData'];
            $dates = $cacheData['dates'];
        } else {
            $user = User::findOrFail($id);
            $projects = $user->projects()->get(['projects.id', 'name']);
            $projectIds = $user->projects()->pluck('projects.id');
            $payment = Payment::whereIn('project_id', $projects)
                ->where('creator_user_id', $user->id)->count();
            $receive = Receive::whereIn('project_id', $projects)
                ->where('creator_user_id', $user->id)->count();
            $note = Note::whereIn('project_id', $projects)
                ->where('creator_user_id', $user->id)->count();
            $imprest = Imprest::whereIn('project_id', $projects)
                ->where('creator_user_id', $user->id)->count();
            $user['project'] = $projects;
            $user['project_count'] = count($projects);
            $user['payment'] = $payment;
            $user['receive'] = $receive;
            $user['imprest'] = $imprest;
            $user['note'] = $note;
            $user['sum'] = count($projects) + $payment + $receive + $imprest + $note;
            $count = Payment::selectRaw('COUNT(type) as c, project_id, creator_user_id, type')
                ->from(DB::raw("
                (SELECT project_id, creator_user_id, 1 as type FROM payments WHERE isnull(deleted_at)
                    UNION ALL
                SELECT project_id, creator_user_id, 2 as type FROM receives WHERE isnull(deleted_at)
                    UNION ALL
                SELECT project_id, creator_user_id, 3 as type FROM imprests WHERE isnull(deleted_at)
                    UNION ALL
                SELECT project_id, creator_user_id, 4 as type FROM notes WHERE isnull(deleted_at)) as t"
                ))
                ->whereIn('project_id', $projectIds)->where('creator_user_id', $id)
                ->groupBy(DB::raw('type, project_id'))->withTrashed()->get();

            $count2 = Payment::selectRaw('COUNT(type) as c, project_id, creator_user_id, type, date')
                ->from(DB::raw("
                (SELECT project_id, creator_user_id, 1 as type, substr(updated_at,1,10) as date FROM payments WHERE isnull(deleted_at)
                    UNION ALL
                SELECT project_id, creator_user_id, 2 as type, substr(updated_at,1,10) as date FROM receives WHERE isnull(deleted_at)
                    UNION ALL
                SELECT project_id, creator_user_id, 3 as type, substr(updated_at,1,10) as date FROM imprests WHERE isnull(deleted_at)
                    UNION ALL
                SELECT project_id, creator_user_id, 4 as type, substr(updated_at,1,10) as date FROM notes WHERE isnull(deleted_at)) as t"
                ))
                ->whereIn('project_id', $projectIds)->groupBy(DB::raw('type, project_id, date'))->
                orderBy('date')->withTrashed()->get();

            $tafkikData = [];
            $datesArrays = $count2->pluck('date');
            $dates = [];
            foreach ($datesArrays as $item) {
                $dates[$item]['normal'] = $item;
                $dates[$item]['converted'] = str_replace('/', '-', Helpers::jalaliDateStringToGregorian($item));
            }
            foreach ($dates as $date) {

                foreach ($projects as $project) {
                    $payment = $count2->where('type', 1)
                        ->where('project_id', $project->id)
                        ->where('creator_user_id', $user->id)
                        ->where('date', $date['normal'])->first();
                    $tafkikData[$project->id][$date['normal']][1] = $payment == null ? 0 : $payment['c'];

                    $receive = $count2->where('type', 2)
                        ->where('project_id', $project->id)
                        ->where('creator_user_id', $user->id)
                        ->where('date', $date['normal'])->first();
                    $tafkikData[$project->id][$date['normal']][2] = $receive == null ? 0 : $receive['c'];

                    $imprest = $count2->where('type', 3)
                        ->where('project_id', $project->id)
                        ->where('creator_user_id', $user->id)
                        ->where('date', $date['normal'])->first();
                    $tafkikData[$project->id][$date['normal']][3] = $imprest == null ? 0 : $imprest['c'];

                    $note = $count2->where('type', 4)
                        ->where('project_id', $project->id)
                        ->where('creator_user_id', $user->id)
                        ->where('date', $date['normal'])->first();
                    $tafkikData[$project->id][$date['normal']][4] = $note == null ? 0 : $note['c'];
                }

            }

            $data = [];
            $range = Payment::selectRaw('COUNT(type) as c, type, substr(date, 12, 2) DIV 4 as time')
                ->from(DB::raw("
                (SELECT project_id, creator_user_id, 1 as type, payments.created_at as date FROM payments WHERE isnull(deleted_at)
                    UNION ALL
                SELECT project_id, creator_user_id, 2 as type, receives.created_at as date FROM receives WHERE isnull(deleted_at)
                    UNION ALL
                SELECT project_id, creator_user_id, 3 as type, imprests.created_at as date FROM imprests WHERE isnull(deleted_at)
                    UNION ALL
                SELECT project_id, creator_user_id, 4 as type, notes.created_at as date FROM notes WHERE isnull(deleted_at)) as t"
                ))
                ->whereIn('project_id', $projectIds)
                ->where('creator_user_id', $user->id)
                ->groupBy(DB::raw('time, type'))->withTrashed()->get();

            foreach (range(0, 5) as $item) {
                $itemCount = $range->where('time', '=', $item);
                $payment = $itemCount->where('type', '=', 1)->first();
                $payment = $payment == null ? 0 : $payment['c'];

                $receive = $itemCount->where('type', '=', 2)->first();
                $receive = $receive == null ? 0 : $receive['c'];

                $imprest = $itemCount->where('type', '=', 3)->first();
                $imprest = $imprest == null ? 0 : $imprest['c'];

                $note = $itemCount->where('type', '=', 4)->first();
                $note = $note == null ? 0 : $note['c'];

                $data[($item * 4) . ' - ' . (($item + 1) * 4)]['payment'] = $payment;
                $data[($item * 4) . ' - ' . (($item + 1) * 4)]['receive'] = $receive;
                $data[($item * 4) . ' - ' . (($item + 1) * 4)]['imprest'] = $imprest;
                $data[($item * 4) . ' - ' . (($item + 1) * 4)]['note'] = $note;
            }
            $cacheData = [];
            $cacheData['users'] = $user;
            $cacheData['count'] = $count;
            $cacheData['data'] = $data;
            $cacheData['tafkikData'] = $tafkikData;
            $cacheData['dates'] = $dates;
            Cache::put(
                $cacheID,
                $cacheData,
                60
            );
        }
        return view('admin.userActivity', compact('user', 'count', 'data', 'tafkikData', 'dates'));
    }

    public function allProjectsActivity(Request $request)
    {
        $cacheID = 'panel_all_projects_activity';
        $selectedState = $request->input('state', 0);
        $selectedCity = $request->input('city', 0);
        if ($request->input('clean_cache')) {
            $this->cleanCache($cacheID, 'projects');
            return redirect(url()->current());
        }
        $flag = false;
        if (cache()->has($cacheID)) {
            $flag = true;
            $data = cache()->get($cacheID);
            if ($data['selected_state'] != $selectedState) {
                $flag = false;
            }
            if ($data['selected_city'] != $selectedCity) {
                $flag = false;
            }
        }
        if ($flag) {
            $data = cache()->get($cacheID);
            $projects = $data['projects'];
            $states = $data['states'];
            $cities = $data['cities'];
        } else {
            $projects = $this->getProjects($selectedState, $selectedCity);
            $this->generateProjectData($projects, $cacheID, $selectedState, $selectedCity);
        }

        $colors = $this->colors();
        list($states, $cities) = $this->getLocations();
        $sort = null;
        $this->filterAndSort($projects, $sort, $request);
        $projects = Helpers::paginateCollection($projects, 100);
        return view('admin.allProjectActivity',
            compact('projects', 'sort', 'states', 'cities', 'selectedState', 'selectedCity', 'colors'));
    }

    public function getProjects(string $selectedState, string $selectedCity)
    {
        if ($selectedState != 0) {
            if ($selectedCity != 0) {
                $projects = Project::where('state_id', $selectedState)
                    ->where('city_id', $selectedCity)->get();
            } else {
                $projects = Project::where('state_id', $selectedState)->get();
            }
        } else {
            $projects = Project::all();
        }
        return $projects;
    }

    public function generateProjectData(&$projects, $cacheID, $selectedState = 0, $selectedCity = 0)
    {
        foreach ($projects as $key => $project) {
            if (cache()->has($cacheID . '_' . $project->id)) {
                $projects[$key] = cache()->get($cacheID . '_' . $project->id);
                continue;
            } else {
                /** @var Project $project */
                $activeUsers = $project->users()->where('project_user.state', ProjectUserState::ACTIVE)->count();
                $notActiveUsers =
                    $project->users()->where('project_user.state', '<>', ProjectUserState::ACTIVE)->count();

                $payment = Payment::where('project_id', $project->id)->get();
                $receive = Receive::where('project_id', $project->id)->get();
                $note = Note::where('project_id', $project->id)->get();
                $imprest = Imprest::where('project_id', $project->id)->get();
                $times = $this->times();
                $rangeCount = 1;
                $project['activationType'] = 4;
                $items = collect();
                $items = $items
                    ->merge($payment)
                    ->merge($receive)
                    ->merge($note)
                    ->merge($imprest);
                $maxTime = $items->max('created_at');
                foreach ($times as $time) {
                    $count = $items->where('created_at', $time[0], $time[1])->count();
                    if ($count >= $rangeCount) {
                        $project['activationType'] = $time[2];
                        break;
                    }
                }

                $project['max_time'] = $maxTime == null ? null : Helpers::convertDateTimeToJalali($maxTime);
                $project['total_count'] = $items->count();
                $project['user_count'] = $activeUsers + $notActiveUsers;
                $project['active_user'] = $activeUsers;
                $project['not_active_user'] = $notActiveUsers;
            }
            if (!cache()->has($cacheID . '_' . $project->id)) {
                cache()->put(
                    $cacheID . '_' . $project->id,
                    $project,
                    now()->addHours(25)
                );
            }
        }
        list($states, $cities) = $this->getLocations();
        $data = [];
        $data['projects'] = $projects;
        $data['states'] = $states;
        $data['cities'] = $cities;
        $data['selected_state'] = $selectedState;
        $data['selected_city'] = $selectedCity;
        cache()->put(
            $cacheID,
            $data,
            now()->addHours(25)
        );
    }

    private function getLocations()
    {
        $states = State::orderBy('name')->get(['id', 'name']);
        $states->push([
            'id' => 0,
            'name' => 'همه',
        ]);
        $states = $states->sortBy('id');
        $cities = City::orderBy('name')->get(['id', 'state_id', 'name']);
        foreach ($states as $state) {
            $cities->push([
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
        $cacheID = 'panel_one_project_activity_' . $projectId;
        if ($request->input('clean_cache')) {
            cache()->forget($cacheID);
        }
        if (Cache::has($cacheID)) {
            $cacheData = Cache::get($cacheID);
            $users = $cacheData['users'];
            $project = $cacheData['project'];
        } else {
            $project = Project::findOrFail($projectId);
            $users = $project->users()->get();
            foreach ($users as $user) {
                $payment = Payment::where('project_id', $projectId)
                    ->where('creator_user_id', $user->id)->count();
                $receive = Receive::where('project_id', $projectId)
                    ->where('creator_user_id', $user->id)->count();
                $note = Note::where('project_id', $projectId)
                    ->where('creator_user_id', $user->id)->count();
                $imprest = Imprest::where('project_id', $projectId)
                    ->where('creator_user_id', $user->id)->count();
                $user['payment'] = $payment;
                $user['receive'] = $receive;
                $user['imprest'] = $imprest;
                $user['note'] = $note;
                $user['sum'] = $payment + $receive + $imprest + $note;

            }

            $cacheData = [];
            $cacheData['users'] = $users;
            $cacheData['project'] = $project;
            Cache::put(
                $cacheID,
                $cacheData,
                60
            );
        }
        $projectStates = ProjectUserState::toArray();
        return view('admin.projectActivity', compact('project', 'users', 'projectStates'));
    }

    public function viewFeedback(Request $request)
    {
        $feedbacks = Feedback::join('feedback_titles', 'feedback_titles.id', '=', 'feedback_title_id')
            ->join('users', 'users.id', '=', 'feedback.user_id')
            ->leftJoin('feedback_responses', 'feedback_responses.id', '=', 'feedback_response_id')
            ->orderBy('feedback_response_id')
            ->orderBy('feedback.created_at', 'DESC')->get([
                'feedback.created_at as date',
                'feedback.id',
                'feedback_titles.title as title',
                'feedback_responses.text as response_text',
                'feedback_responses.response_updated_at as response_text_update_time',
                'feedback.text as text',
                'users.name as user_name',
                'users.family as user_family',
            ]);
        $feedbacks->map(function ($item) {
            $item['date'] = Helpers::convertDateTimeToJalali($item['date']);
            $item['response_text_update_time'] =
                Helpers::convertDateTimeToJalali($item['response_text_update_time']);
            $item['full_name'] = $item['user_name'] . ' ' . $item['user_family'];
        });

        $sort = ['', 'DESC'];
        if ($request->has('sort_field')) {
            $sort = [$request->input('sort_field'), $request->input('sort_type')];
            $feedbacks = $feedbacks->sortBy($sort[0], SORT_REGULAR, $sort[1] == 'DESC');
            $sort[1] = $sort[1] == 'DESC' ? 'ASC' : 'DESC';
        }

        return view('admin.feedback', compact('feedbacks', 'checkTitles', 'sort'));
    }

    public function responseFeedbackView($id)
    {
        $feedback = Feedback::join('users', 'users.id', '=', 'feedback.user_id')
            ->where('feedback.id', $id)->first([
                'feedback.*',
                'users.name',
                'users.family',
                'users.phone_number',
            ]);
        $feedback['user_name'] = $feedback->name . ' ' . $feedback->family;
        $feedback['phone_number'] = '0' . $feedback->phone_number;
        $response = $feedback->feedbackResponse()->first();
        if ($response) {
            $feedback->response = $response->text;
        } else {
            $feedback->response = null;
        }
        $oldFeedbacks = Feedback::where('user_id', $feedback->user_id)
            ->orderBy('created_at')->get();
        foreach ($oldFeedbacks as $oldFeedback) {
            $oldFeedback['date'] = Helpers::convertDateTimeToJalali($oldFeedback['created_at']);
            $response = $oldFeedback->feedbackResponse()->first();
            $oldFeedback['response'] = $response ? $response->text : null;
            $oldFeedback['response_text_update_time'] =
                Helpers::convertDateTimeToJalali($response['response_updated_at']);
        }
        return view('admin.feedbackResponse', compact('feedback', 'oldFeedbacks'));
    }

    public function responseFeedback($id, Request $request)
    {
        $feedback = Feedback::where('id', $id)->first();
        $responseText = $request->input('response', '');
        if (trim($responseText) == '') {
            return redirect()->back()->withErrors('پاسخ بازخورد نباید خالی باشد.');
        }
        $feedbackResponse = FeedbackResponse::updateOrCreate([
            'id' => $feedback->feedback_response_id,
        ], [
            'panel_user_id' => auth()->user()->id,
            'text' => trim($responseText),
            'response_updated_at' => now(),
            'read_at' => null,
        ]);
        $feedback->feedback_response_id = $feedbackResponse->id;
        $feedback->save();

        return redirect()->route('panel.feedbacks')->with('success', 'با موفقیت انجام شد');
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
            'panel_user_id' => auth()->user()->id,
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
}
