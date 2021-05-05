@extends('dashboard.layouts.master')

@section('title')
    <i class="fa fa-bar-chart"></i>
    وضعیت کاربر -
    @include('dashboard.layouts.username')
@endsection
@section('filter')
    <div class="row pb-3">
        <div class="col-md-2">
            <a class="form-control btn btn-info" href="{{ route('dashboard.promoCodes', ['user_id' => $user->id]) }}">کد تخفیف‌های کاربر</a>
        </div>
        <div class="col-md-2">
            <a class="form-control btn btn-info" href="{{ route('dashboard.transactions', ['user_id' => $user->id]) }}">تراکنش‌های
                کاربر</a>
        </div>
        <div class="col-md-2">
            <a class="form-control btn btn-info" href="{{ route('dashboard.feedbacks', ['user_id' => $user->id])
            }}">بازخوردهای
                کاربر</a>
        </div>
        <div class="col-md-2">
            <a class="form-control btn btn-info" href="{{ route('dashboard.automation.callLogs', ['id' => $user->id])
            }}">تماس‌های کاربر</a>
        </div>
    </div>
    <div class="row pb-5">
        <div class="col-md-2">
            <a class="form-control btn btn-success" href="{{ route('dashboard.campaignUser', ['userIds' => $user->id]) }}">افزودن
                کد تخفیف</a>
        </div>
        <div class="col-md-2"></div>
        <div class="col-md-2">
            <a class="form-control btn btn-success"
               href="{{ route('dashboard.newComment', ['phone_number' => $user->phone_number, 'user_id' => $user->id]) }}">افزودن
                بازخورد</a>
        </div>
        <div class="col-md-2">
            <a class="form-control btn btn-success"
               href="{{ route('dashboard.bannerItem', ['id' => 0, 'userIds' => $user->id]) }}">افزودن بنر</a>
        </div>
        <div class="col-md-2">
            <a class="form-control btn btn-success"
               href="{{ route('dashboard.announcementItem', ['id' => 0, 'userIds' => $user->id]) }}">افزودن اعلان</a>
        </div>
    </div>
@endsection
@section('content')
    <div class="row">
        @include('dashboard.report.listUser', [
                'users' => $userItem,
                'clickable' => false,
                'extraData' => [
                    'کیف پول' => $user->wallet,
                    'کیف پول رزرو شده' => $user->reserve_wallet
                ]
        ])
    </div>
    <div id="ajax-table">
        <table class="table table-striped table-responsive">
            <thead>
            <tr>
                <th>ردیف</th>
                <th>نام پروژه</th>
                <th>مالک پروژه</th>
                <th>وضعیت کاربر در پروژه</th>
                <th>وضعیت پروژه</th>
                <th>تعداد پرداخت</th>
                <th>تعداد دریافت</th>
                <th>تعداد یادداشت</th>
                <th>تعداد تنخواه</th>
                <th>تعداد عکس</th>
                <th>حجم عکس</th>
                <th>وضعیت آرشیو</th>
            </tr>
            </thead>
            <tbody>
            @foreach($projects as $project)
                <tr class="clickableRow table-row-clickable"
                    data-href="{{ route('dashboard.report.projectActivity', ['id' => $project->id]) }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $project->name }}</td>
                    <td>
                        @if($project->is_owner) <img src="{{ asset('dashboard/icons/icon_check.png') }}">
                        @else <img src="{{ asset('dashboard/icons/icon_uncheck.png') }}">
                        @endif
                    </td>
                    <td>{{ \App\Constants\ProjectUserState::getEnum($project->status) }}</td>
                    <td>{{ \App\Constants\UserPremiumState::getEnum($project->project_state) }}</td>
                    <td>{{ $project->payment_count }}</td>
                    <td>{{ $project->receive_count }}</td>
                    <td>{{ $project->note_count }}</td>
                    <td>{{ $project->imprest_count }}</td>
                    <td>{{ $project->image_count }}</td>
                    <td>{{ $project->image_size }}</td>
                    <td>
                        @if($project->is_archived) <img src="{{ asset('dashboard/icons/icon_check.png') }}">
                        @else <img src="{{ asset('dashboard/icons/icon_uncheck.png') }}">
                        @endif
                    </td>
                </tr>
            @endforeach
            <tr class="table-primary">
                <td></td>
                <td>جمع</td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ $projects->pluck('payment_count')->sum() }}</td>
                <td>{{ $projects->pluck('receive_count')->sum() }}</td>
                <td>{{ $projects->pluck('note_count')->sum() }}</td>
                <td>{{ $projects->pluck('imprest_count')->sum() }}</td>
                <td>{{ $projects->pluck('image_count')->sum() }}</td>
                <td>{{ $projects->pluck('image_size')->sum() }}</td>
                <td></td>
            </tr>
            </tbody>
        </table>
    </div>
    <hr class="pt-4 pb-2">
    <h5 class="text-center pb-3">وضعیت اتوماسیون</h5>
    @if($automationState)
        <a target="_blank" href="{{ route('dashboard.automation.typeItem', ['id' => $automationState['automation_state']]) }}">{{
    $type_mappings[$automationState['automation_state']]
    }} (وضعیت {{
    $automationState['automation_state'] }})
        </a>
    @else
        تشخیص داده نشده!
    @endif
    <hr class="pt-4 pb-2">
    <h5 class="text-center pb-3">طرح‌های کاربر</h5>
    @include('dashboard.management.listUserStatus')
    <hr class="pt-4 pb-2">
    <h5 class="text-center pb-3">دستگاه‌های کاربر</h5>
    @include('dashboard.report.listDevices')
    <hr>
    <div id="rangeCount" class="my-5" style="width:100%;"></div>
    <div id="chart2" class="my-5" style="width:100%;"></div>
@endsection
@section('chart')
    @include('dashboard.report.charts.rangeCount')
    @include('dashboard.report.charts.dateCount')
@endsection
