@extends('dashboard.layouts.master')

@section('title')
    <i class="fa fa-bar-chart"></i>
    وضعیت کاربران
@endsection
@section('filter')
    <form id="filter" method="get" action="">
        {{ csrf_field() }}
        <div class="row pb-5 pt-5 justify-content-center">
            <div class="col-md-3 col-sm-10">
                <table class="table table-bordered table-responsive">
                    <tr class="text-center">
                        <input id="userType" type="hidden" value="{{ $filter['user_type'] }}" name="user_type">
                        <td style="border: solid black 1px; cursor: pointer;">
                            <div onclick="changeUserType({{0}})">همه</div>
                        </td>
                        @foreach($colors as $key => $color)
                            <td style="background-color: {{$color[0]}}; cursor: pointer;">
                                <div onclick="changeUserType({{$key}})">{{$color[1]}}</div>
                            </td>
                        @endforeach
                    </tr>
                </table>
            </div>
            <div class="col-md-3 col-sm-10 pr-2">
                <div class="row">
                    <label class="col-md-5 col-form-label text-md-left">جستجوی شماره</label>
                    <input type="text" id="phone_number" name="phone_number" value="{{$filter['phone_number']}}"
                           placeholder="جستجوی شماره" class="form-control col-md-7">
                </div>
            </div>
            <div class="col-md-3 col-sm-12 pr-2">
                <div class="row">
                    <label class="col-md-5 col-form-label text-md-left">جستجوی نام</label>
                    <input type="text" id="name" name="name" value="{{$filter['name']}}"
                           placeholder="جستجوی نام" class="form-control col-md-7">
                </div>
            </div>
            <div class="col-md-3 col-sm-12 pr-2">
                <div class="row">
                    <label class="col-md-5 col-form-label text-md-left">مرتب‌سازی</label>
                    <select id="sort_field" name="sort_field">
                        @foreach($sortable_fields as $key => $sortable_field)
                            <option @if ($key == $filter['sort_field']) selected
                                    @endif value="{{ $key }}">{{ $sortable_field }}</option>
                        @endforeach
                    </select>
                    <select id="sort_type" name="sort_type">
                        @foreach($sortable_types as $key => $sortable_type)
                            <option @if ($key == $filter['sort_type']) selected
                                    @endif value="{{ $key }}">{{ $sortable_type }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3 col-sm-12 pr-2"></div>
            <div class="col-md-3 col-sm-12 pr-2">
                <div class="row">
                    <label class="col-md-5 col-form-label text-md-left">تاریخ شروع</label>
                    <input id="start_date" class="form-control range_date col-md-7" type="text" name="start_date"
                           value="{{$filter['start_date']}}">
                </div>
            </div>
            <div class="col-md-3 col-sm-12 pr-2">
                <div class="row">
                    <label class="col-md-5 col-form-label text-md-left">تاریخ پایان</label>
                    <input id="end_date" class="form-control range_date col-md-7" type="text" name="end_date"
                           value="{{$filter['end_date']}}">
                </div>
            </div>
            <div class="col-md-3 col-sm-12 pr-2"></div>
            <input type="hidden" name="page" value="1"/>
            <div class="row pt-2">
                <input class="btn btn-info" type="submit" value="اعمال فیلتر">
                @if(auth()->user()->hasRole('Admin'))
                    <input class="btn btn-warning mr-2" type="button" value="فایل خروجی" onclick="exportClick()">
                @endif
            </div>
        </div>
    </form>
@endsection
@section('content')
    <div></div>
    <div class="row">
        <div class="col-md-4">{{ $users }}</div>
        <div class="col-md-8"><p>تعداد {{ $users->total() }} کاربر با شرایط فوق پیدا شد.</p></div>
    </div>
    <div id="ajax-table" style="overflow-x: auto;">
        <table class="table">
            <thead>
            <tr style="cursor: pointer;">
                <th>ردیف</th>
                <th onclick="sortTable('name')">نام و نام خانوادگی</th>
                <th onclick="sortTable('phone_number')">شماره تلفن</th>
                <th onclick="sortTable('registered_at')">تاریخ و ساعت ثبت نام</th>
                <th onclick="sortTable('max_time')">آخرین ثبت</th>
                <th onclick="sortTable('project_count')">تعداد کل پروژه</th>
                <th onclick="sortTable('own_project_count')">تعداد پروژه مالک</th>
                <th onclick="sortTable('not_own_project_count')">تعداد پروژه اشتراکی</th>
                <th onclick="sortTable('payment_count')">تعداد پرداخت</th>
                <th onclick="sortTable('receive_count')">تعداد دریافت</th>
                <th onclick="sortTable('note_count')">تعداد یادداشت</th>
                <th onclick="sortTable('imprest_count')">تعداد تنخواه</th>
                <th onclick="sortTable('file_count')">تعداد فایل‌ها</th>
                <th onclick="sortTable('image_count')">تعداد عکس‌ها</th>
                <th onclick="sortTable('image_size')">حجم عکس‌ها</th>
                <th onclick="sortTable('feedback_count')">تعداد بازخورد</th>
                <th onclick="sortTable('device_count')">تعداد دستگاه‌ها</th>
                <th onclick="sortTable('step_by_step')">گام به گام</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr class="clickableRow table-row-clickable"
                    data-href="{{ route('dashboard.report.userActivity', ['id' => $user->id]) }}"
                    style="background-color: {{ $colors[$user['user_type']][0] }}">
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
@section('scripts')
    <script>

        function changeUserType(userType) {
            var input = document.getElementById('userType');
            input.value = userType;

            document.getElementById('filter').submit();
        }

        function exportClick() {
            var form = document.getElementById('filter');
            form.action = '{{ route('dashboard.report.export.allUsersActivity') }}';

            document.getElementById('filter').submit();
            form.action = '';
        }

        $('#sort_field').select2({
            width: 'element',
        });

        $('#sort_type').select2({
            width: 'element',
        });

    </script>
@endsection
