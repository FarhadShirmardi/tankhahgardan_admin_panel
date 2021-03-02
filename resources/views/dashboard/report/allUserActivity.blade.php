@extends('dashboard.layouts.master')

@section('title')
    <div class="row">
        <div class="col-md-6">
            <i class="fa fa-bar-chart"></i>
            وضعیت کاربران
        </div>
        <div class="col-md-6 ltr">
            @if(auth()->user()->hasRole('Admin'))
                <a href="{{ route('dashboard.generateReport') }}">
                    <i class="fa fa-refresh"></i>
                </a>
            @endif
        </div>
    </div>
@endsection
@section('filter')
    @include('dashboard.layouts.link_message')
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
            <div class="col-md-3 col-sm-12 pr-2">
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
        <div class="col-md-4">{{ $users->appends(request()->input())->links() }}</div>
        <div class="col-md-8"><p>تعداد {{ $users->total() }} کاربر با شرایط فوق پیدا شد.</p></div>
    </div>
    @include('dashboard.report.listUser')
    {{ $users->appends(request()->input())->links() }}
    <hr>
    <div class="row">
        <div class="col-md-2">
            <a class="form-control btn btn-success"
               href="{{ route('dashboard.campaignUser', ['userIds' => $userIds]) }}">افزودن
                کد تخفیف</a>
        </div>
        <div class="col-md-2">
            <a class="form-control btn btn-success"
               href="{{ route('dashboard.announcementItem', ['id' => 0, 'userIds' => $userIds]) }}">افزودن
                اعلان</a>
        </div>
        <div class="col-md-2">
            <a class="form-control btn btn-success"
               href="{{ route('dashboard.bannerItem', ['id' => 0, 'userIds' => $userIds]) }}">افزودن بنر</a>
        </div>
        <div class="col-md-6">
            <div class="row">
                <form action="{{ route('dashboard.extractUserIds', [], true) }}" enctype="multipart/form-data" method="POST">
                    {{ csrf_field() }}
                    <div class="row">
                        <input class="col-md-8 form-control form-control-file"
                               type="file" name="users" accept=".xlsx, .xls" required>
                        <input class="col-md-4 btn btn-primary" type="submit" value="تبدیل">
                    </div>
                </form>
            </div>
            <div class="row">
                <a href="{{ asset('users.xlsx') }}">نمونه فایل</a>
            </div>
        </div>
    </div>
    <hr>
    <div id="dateChart" style="width:100%;"></div>
    <div id="rangeCount" class="my-5" style="width:100%;"></div>
@endsection
@section('chart')
    @include('dashboard.report.charts.allUserActivity')
    @include('dashboard.report.charts.rangeCount')
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

        $('#user_states').select2({width: 'element'});

        $('#sort_field').select2({
            width: 'element',
        });

        $('#sort_type').select2({
            width: 'element',
        });

    </script>
@endsection
