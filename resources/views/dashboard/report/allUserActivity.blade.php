@extends('dashboard.layouts.master')

@section('title')
    <i class="fa fa-bar-chart"></i>
    وضعیت کاربران
@endsection
@section('filter')
    <form id="filter" method="get" action="">
        {{ csrf_field() }}
        <div class="row pb-5 pt-5 justify-content-center">
            <div class="col-md-3">
                <table>
                    <tr>
                        <input id="userType" type="hidden" value="{{ $filter['user_type'] }}" name="user_type">
                        <td style="border: solid black 1px;">
                            <div onclick="changeUserType({{0}})"
                                 style="cursor: pointer;">همه
                            </div>
                        </td>
                        @foreach($colors as $key => $color)
                            <td style="background-color: {{$color[0]}}">
                                <div onclick="changeUserType({{$key}})"
                                     style="cursor: pointer;">{{$color[1]}}</div>
                            </td>
                        @endforeach
                    </tr>
                </table>
            </div>
            <div class="col-md-3">
                <div class="pr-2">
                    <label>جستجوی شماره</label>
                    <input type="text" id="phone_number" name="phone_number" value="{{$filter['phone_number']}}"
                           placeholder="جستجوی شماره">
                </div>
            </div>
            <div class="col-md-3">
                <div class="pr-2">
                    <label>جستجوی نام</label>
                    <input type="text" id="name" name="name" value="{{$filter['name']}}"
                           placeholder="جستجوی نام">
                </div>
            </div>
            <div class="col-md-3">
                <div class="pr-2">
                    <label>مرتب‌سازی</label>
                    <select id="sort_field" name="sort_field">
                        @foreach($sortable_fields as $key => $sortable_field)
                            <option @if ($key == $filter['sort_field']) selected @endif value="{{ $key }}">{{ $sortable_field }}</option>
                        @endforeach
                    </select>
                    <select id="sort_type" name="sort_type">
                        @foreach($sortable_types as $key => $sortable_type)
                            <option value="{{ $key }}">{{ $sortable_type }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <input type="hidden" name="page" value="1"/>
            <input class="btn btn-info pt-2" type="submit" value="اعمال فیلتر">
        </div>
    </form>
@endsection
@section('content')
    <div>{{ $users }}</div>
    <div id="ajax-table">
        <table class="table">
            <thead>
            <tr>
                <th>ردیف</th>
                <th>نام و نام خانوادگی</th>
                <th>شماره تلفن</th>
                <th>تاریخ و ساعت ثبت نام</th>
                <th>آخرین ثبت</th>
                <th>تعداد کل پروژه</th>
                <th>تعداد پروژه مالک</th>
                <th>تعداد پروژه اشتراکی</th>
                <th>تعداد پرداخت</th>
                <th>تعداد دریافت</th>
                <th>تعداد یادداشت</th>
                <th>تعداد تنخواه</th>
                <th>تعداد فایل‌ها</th>
                <th>تعداد عکس‌ها</th>
                <th>حجم عکس‌ها</th>
                <th>تعداد بازخورد</th>
                <th>تعداد دستگاه‌ها</th>
                <th>گام به گام</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr style="background-color: {{ $colors[$user['user_type']][0] }}">
                    <td>{{($users->currentPage() - 1) * $users->perPage() + $loop->iteration}}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->phone_number }}</td>
                    <td>{{ \App\Helpers\Helpers::convertDateTimeToJalali($user->registered_at) }}</td>
                    <td>{{ $user->max_time ? \App\Helpers\Helpers::convertDateTimeToJalali($user->max_time) : ' - ' }}</td>
                    <td>{{ $user->project_count }}</td>
                    <td>{{ $user->own_project_count }}</td>
                    <td>{{ $user->not_own_project_count }}</td>
                    <td>{{ $user->payment_count }}</td>
                    <td>{{ $user->receive_count }}</td>
                    <td>{{ $user->note_count }}</td>
                    <td>{{ $user->imprest_count }}</td>
                    <td>{{ $user->file_count }}</td>
                    <td>{{ $user->image_count }}</td>
                    <td>{{ $user->image_size ? round($user->image_size, 2) : ' - ' }}</td>
                    <td>{{ $user->feedback_count }}</td>
                    <td>{{ $user->device_count }}</td>
                    <td>{{ $user->step_by_step }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $users }}
    <div id="dateChart" style="width:100%;"></div>
@endsection
@section('chart')
    @include('dashboard.report.charts.allUserActivity')
@endsection

<script>

    function changeUserType(userType) {
        var input = document.getElementById('userType');
        input.value = userType;

        document.getElementById('filter').submit();
    }

</script>
