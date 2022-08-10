@extends('dashboard.layouts.master')

@section('title')
    <div class="row">
        <div class="col-md-6">
            <i class="fa fa-bar-chart"></i>
            وضعیت کاربران
        </div>
        <div class="col-md-6 ltr">
            @if(auth()->user()->can('refresh_users_report'))
                <a href="{{ route('dashboard.generateReport') }}">
                    <i class="fa fa-refresh"></i>
                </a>
            @endif
        </div>
    </div>
@endsection
@section('filter')
    <form id="filter" method="get" action="">
        {{ csrf_field() }}
        <input type="hidden" id="route" name="route"/>
        <input type="hidden" id="user_ids" name="user_ids" value="{{ is_array($filter['user_ids']) ?
        (implode(',', $filter['user_ids']) ?? '') :
        $filter['user_ids'] }}">
        <div class="row pt-5 justify-content-center">
            <div class="col-md-3 col-sm-12">
                <table class="table table-bordered table-responsive">
                    <tr class="text-center">
                        <input id="userType" type="hidden" value="{{ $filter['user_type'] }}" name="user_type">
                        <td style="border: solid black 1px; cursor: pointer;" onclick="changeUserType({{0}})">
                            <span>همه</span>
                        </td>
                        @foreach($colors as $key => $color)
                            <td style="background-color: {{$color[0]}}; cursor: pointer;" onclick="changeUserType({{$key}})">
                                <span>{{$color[1]}}</span>
                            </td>
                        @endforeach
                    </tr>
                </table>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="row">
                    <label class="col-md-5 col-form-label text-md-left">جستجوی شماره</label>
                    <input type="text" id="phone_number" name="phone_number" value="{{$filter['phone_number']}}"
                           placeholder="جستجوی شماره" class="form-control col-md-7">
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="row">
                    <label class="col-md-5 col-form-label text-md-left">جستجوی نام</label>
                    <input type="text" id="name" name="name" value="{{$filter['name']}}"
                           placeholder="جستجوی نام" class="form-control col-md-7">
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="row">
                    <label class="col-md-5 col-lg-5 col-form-label text-md-left">مرتب‌سازی</label>
                    <select id="sort_field" class="form-control col-md-4 col-lg-4" name="sort_field">
                        @foreach($sortable_fields as $key => $sortable_field)
                            <option @if ($key == $filter['sort_field']) selected
                                    @endif value="{{ $key }}">{{ $sortable_field }}</option>
                        @endforeach
                    </select>
                    <select id="sort_type" class="form-control col-md-3 col-lg-3" name="sort_type">
                        @foreach($sortable_types as $key => $sortable_type)
                            <option @if ($key == $filter['sort_type']) selected
                                    @endif value="{{ $key }}">{{ $sortable_type }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3 col-sm-12"></div>
            <div class="col-md-3 col-sm-12">
                <div class="row">
                    <label class="col-md-5 col-form-label text-md-left">تاریخ شروع</label>
                    <input id="start_date" class="form-control range_date col-md-7" type="text" name="start_date"
                           value="{{$filter['start_date']}}">
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="row">
                    <label class="col-md-5 col-form-label text-md-left">تاریخ پایان</label>
                    <input id="end_date" class="form-control range_date col-md-7" type="text" name="end_date"
                           value="{{$filter['end_date']}}">
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="row">
                    <label class="col-md-5 col-form-label text-md-left">وضعیت کاربر</label>
                    <div class="ms-list col-md-7">
                        <select name="user_states[]" multiple="multiple" id="user_states" style="width: 100%">
                            @foreach($user_states as $userState)
                                <option @if($userState['is_selected']) selected @endif
                                value="{{$userState['id']}}">{{$userState['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <input type="hidden" name="page" value="1"/>
            <div class="row pt-2">
                <input class="btn btn-info" type="submit" value="اعمال فیلتر">
                @if(auth()->user()->can('export_users_report'))
                    <input class="btn btn-warning mr-2" type="button" value="فایل خروجی" onclick="exportClick()">
                @endif
            </div>
        </div>
    </form>
@endsection
@section('content')
    {{ $users->withQueryString()->links() }}
    @include('dashboard.report.listUser', ['clickable' => true])
    {{ $users->withQueryString()->links() }}
    <div class="row">
        <div class="col-md-2">
            <input class="form-control btn btn-success" onclick="extractIds('dashboard.campaignUser');" value="افزودن کد تخفیف">
        </div>
        <div class="col-md-2">
            <input class="form-control btn btn-success" onclick="extractIds('dashboard.announcementItem');"
                   value="افزودن اعلان">
        </div>
        <div class="col-md-2">
            <input class="form-control btn btn-success" onclick="extractIds('dashboard' +
             '.bannerItem');" value="افزودن بنر">

        </div>
        <div class="col-md-6">
            <div class="row">
                <form action="{{ route('dashboard.extractUserIds', [], true) }}" enctype="multipart/form-data" method="POST">
                    {{ csrf_field() }}
                    <div class="row">
                        <input class="col-md-8 form-control form-control-file"
                               type="file" name="users" accept=".xlsx, .xls" required>
                        <input class="col-md-4 bg-info btn" type="submit" value="تبدیل">
                    </div>
                </form>
            </div>
            <div class="row">
                <a href="{{ asset('users.xlsx') }}">نمونه فایل</a>
            </div>
        </div>
    </div>
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
        }

        function extractIds(route) {
            var routeEl = document.getElementById('route');
            routeEl.value = route;

            var form = document.getElementById('filter');
            form.action = '{{ route('dashboard.report.extractUserIdsWithFilter') }}';
            document.getElementById('filter').submit();
        }

        $('#user_states').select2({width: 'element'});

        $('#sort_field').select2({
            width: 'element',
        });

        $('#sort_type').select2({
            width: 'element',
        });

    </script>
@endsection
