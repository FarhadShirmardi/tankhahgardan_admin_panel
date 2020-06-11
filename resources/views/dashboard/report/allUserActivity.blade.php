@extends('dashboard.layouts.master')

@section('title')
    <i class="fa fa-bar-chart"></i>
    وضعیت کاربران
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
        <div class="col-md-4">{{ $users->appends(request()->input())->links() }}</div>
        <div class="col-md-8"><p>تعداد {{ $users->total() }} کاربر با شرایط فوق پیدا شد.</p></div>
    </div>
    @include('dashboard.report.listUser')
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
