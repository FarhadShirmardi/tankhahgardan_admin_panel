<?php

namespace App\Http\Controllers\Dashboard;

use App\Announcement;
use App\AnnouncementUser;
use App\Constants\AnnouncementStatus;
use App\Constants\AnnouncementType;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Jobs\AnnouncementJob;
use App\User;
use Carbon\Carbon;
use DB;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Storage;
use Validator;

class FirebaseController extends Controller
{
    public function announcements(Request $request)
    {
        $filter = [
            'announcement_status' => $request->input('announcement_status', []),
            'announcement_type' => $request->input('announcement_type', []),
        ];

        $announcementReadQuery = AnnouncementUser::query()
            ->whereColumn('announcement_id', 'announcements.id')
            ->where('read', true)
            ->selectRaw('count(*) as count')
            ->getQuery();

        $announcements = Announcement::query()
            ->join('panel_users', 'panel_user_id', '=', 'panel_users.id')
            ->addSelect([
                'panel_users.name as panel_user_name',
                'announcements.*'
            ])
            ->selectSub($announcementReadQuery, 'announcement_count')
            ->addSelect([
                'announcements.*',
                DB::raw(AnnouncementStatus::ACTIVE . " as announcement_status")
            ])
            ->orderByDesc('announcements.updated_at')
            ->get();

        $announcementStatuses = collect();
        foreach (AnnouncementStatus::toArray() as $source) {
            $item = [];
            $item['value'] = $source;
            $item['text'] = AnnouncementStatus::getEnum($source);
            $item['is_selected'] = in_array($source, $filter['announcement_status']);
            $announcementStatuses->push($item);
        }
        $announcementStatuses = $announcementStatuses->toArray();

        $announcementTypes = collect();
        foreach (AnnouncementType::toArray() as $source) {
            $item = [];
            $item['value'] = $source;
            $item['text'] = AnnouncementType::getEnum($source);
            $item['is_selected'] = in_array($source, $filter['announcement_type']);
            $announcementTypes->push($item);
        }
        $announcementTypes = $announcementTypes->toArray();

        $announcements = $announcements->filter(function ($item) use ($filter) {
            if ($item['expire_at'] < now()->toDateTimeString()) {
                $item['announcement_status'] = AnnouncementStatus::EXPIRED;
            } elseif ($item['send_at'] > now()->toDateTimeString()) {
                $item['announcement_status'] = AnnouncementStatus::NOT_SENT;
            }
            if (
                ($filter['announcement_status'] != [] and !in_array($item['announcement_status'], $filter['announcement_status'])) or
                ($filter['announcement_type'] and !in_array($item['user_type'], $filter['announcement_type']))
            ) {
                return;
            }
            return $item;
        });

        return view('dashboard.firebase.announcements', [
            'announcements' => $announcements,
            'announcement_status' => $announcementStatuses,
            'announcement_type' => $announcementTypes
        ]);
    }

    public function announcementItem(Request $request, $id)
    {
        $userIds = $request->userIds;
        if ($id) {
            /** @var Announcement $announcement */
            $announcement = Announcement::query()->findOrFail($id);
            $userIds = implode(',', $announcement->announcementUser()->pluck('user_id')->toArray());
        } else {
            $announcement = new Announcement([
                'link_type' => 1,
                'expire_at' => now()->addWeek()->endOfDay()
            ]);
        }
        $user = null;
        $users = collect();
        if ($userIds) {
            $userIds = explode(',', $userIds);
            $userStates = AnnouncementUser::query()
                ->where('announcement_id', $id)
                ->whereIn('user_id', $userIds)
                ->get();
            if (count($userIds) == 1) {
                $user = User::query()->findOrFail($userIds[0]);
                $users->push([
                    'username' => Helpers::getEnglishString($user->full_name),
                    'state' => $userStates->count() ? $userStates->where('user_id', $user->id)->first()->read : 0,
                ]);
            } else {
                foreach ($userIds as $userId) {
                    $userTemp = User::query()->findOrFail($userId);
                    $users->push([
                        'username' => Helpers::getEnglishString($userTemp->full_name),
                        'state' => $userStates->count() ? $userStates->where('user_id', $userId)->first()->read : 0,
                    ]);
                }
            }
            $userIds = implode(',', $userIds);
        }

        if ($announcement->user_type == AnnouncementType::PUBLIC) {
            $userIds = null;
            $user = null;
        }

        $users = Helpers::paginateCollection($users->sortByDesc('state'), 10);

        return view('dashboard.firebase.announcementItem', [
            'id' => $id,
            'announcement' => $announcement,
            'user' => $user,
            'userIds' => $userIds,
            'users' => $users
        ]);
    }

    public function storeAnnouncement(Request $request, $id)
    {
        $userIds = $request->userIds;
        if (!$userIds) {
            $request->merge([
                'user_id' => null
            ]);
        } else {
            $userIds = explode(',', $userIds);
            $userCount = User::query()->whereIn('id', $userIds)->count();
            if ($userCount != count($userIds)) {
                $validator = Validator::make([], []);
                $validator->errors()->add('error', 'کاربر یافت نشد.');
                return redirect()->back()->withErrors($validator);
            }
        }
        $sendAt = Helpers::convertDateTimeToGregorian(Helpers::getEnglishString($request->send_at));
        $expireAt = $request->expire_at ? Helpers::convertDateTimeToGregorian(Helpers::getEnglishString($request->expire_at)) : null;
        $request->merge([
            'send_at' => $sendAt,
            'expire_at' => $expireAt,
            'panel_user_id' => auth()->id(),
            'user_type' => $userIds ? 2 : 1,
            'external_link' => $request->link_type == 1 ? null : $request->external_link
        ]);

        try {
            /** @var Announcement $announcement */
            $announcement = Announcement::query()->updateOrCreate([
                'id' => $id
            ], $request->all());

            if ($userIds and !$id) {
                $announcementUser = collect();
                foreach ($userIds as $userId) {
                    $announcementUser->push([
                        'user_id' => $userId,
                        'announcement_id' => $announcement->id
                    ]);

                }
                AnnouncementUser::query()
                    ->insert($announcementUser->toArray());
            }

            $id = $announcement->id;

            $announcement->update([
                'icon_path' => null,
                'image_path' => null,
                'git_path' => null
            ]);

            $images = [
                'icon' => $request->file('icon'),
                'image' => $request->file('image'),
                'gif' => $request->file('gif'),
            ];

            $isNull = true;
            $imageRequest = [];
            /** @var UploadedFile $image */
            foreach ($images as $key => $image) {
                if (!$image) {
                    continue;
                }
                $image->storeAs('/', 'Announcement_' . $key . '.' . $image->getClientOriginalExtension());
                array_push($imageRequest, [
                    'name' => $key,
                    'filename' => $image->getClientOriginalName(),
                    'contents' => file_get_contents(storage_path() . '/app/' . 'Announcement_' . $key . '.' . $image->getClientOriginalExtension())
                ]);
                $isNull = false;
            }
            if (!$isNull) {
                $http = new Client;
                $response = $http->post(
                    env('TANKHAH_URL') . '/panel/' . env('TANKHAH_TOKEN') . '/announcement/' . $id . '/image',
                    [
                        'headers' => [
                            'Accept' => 'application/json',
                        ],
                        'multipart' => $imageRequest
                    ]
                );
                $response = json_decode($response->getBody());
                foreach ($response as $key => $item) {
                    $announcement->$key = $item;
                }
                $announcement->save();
                foreach ($images as $key => $image) {
                    if (!$image) {
                        continue;
                    }
                    Storage::delete('/' . 'Announcement_' . $key . '.' . $image->getClientOriginalExtension());
                }
            }

            $delay = now();
            $sendTime = new Carbon($announcement->send_at);
            if ($sendTime->isFuture()) {
                $delay = $sendTime;
            }

            dispatch(new AnnouncementJob($announcement))->onQueue('activationSms')
                ->delay($delay);
        } catch (Exception $exception) {
            dd($exception);
        }

        return redirect()->route('dashboard.announcements')->with('success', 'با موفقیت انجام شد');
    }

    public function deleteAnnouncement($id)
    {
        $announcement = Announcement::query()->findOrFail($id);
        $announcement->update([
            'expire_at' => now()->toDateTimeString()
        ]);

        return redirect()->route('dashboard.announcements')->with('success', 'با موفقیت انجام شد');
    }
}
