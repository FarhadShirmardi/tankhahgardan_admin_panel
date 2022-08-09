@extends('dashboard.layouts.master')

@section('title')
    <div class="row">
        <div class="col-md-6">
            <i class="fa fa-money"></i>
            گزارش کاربرانی که پول داده‌اند و تمدید نکرده‌اند
        </div>
    </div>
@endsection
@section('filter')
    <form id="filter" method="get" action="">
        {{ csrf_field() }}
        <div class="row pb-5 pt-2 justify-content-center">
            <div class="col-md-3 col-sm-12">
                <div class="row">
                    <label class="col-md-5 col-form-label text-md-left">از روز</label>
                    <input type="number" id="start_day" name="start_day" value="{{$filter['start_day']}}"
                           placeholder="حداقل تعداد روز" class="form-control col-md-7">
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="row">
                    <label class="col-md-5 col-form-label text-md-left">تا روز</label>
                    <input type="number" id="end_day" name="end_day" value="{{$filter['end_day']}}"
                           placeholder="حداکثر تعداد روز" class="form-control col-md-7">
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
            </div>
            <div class="col-md-3 col-sm-12">
            </div>
        </div>
        <div class="row pb-5 justify-content-center">
            <div class="col-md-3 col-sm-12">
                <div class="row">
                    <label class="col-md-5 col-form-label text-md-left">از یوزر</label>
                    <input type="number" id="start_user" name="start_user" value="{{$filter['start_user']}}"
                           class="form-control col-md-7">
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="row">
                    <label class="col-md-5 col-form-label text-md-left">تا یوزر</label>
                    <input type="number" id="end_user" name="end_user" value="{{$filter['end_user']}}"
                           class="form-control col-md-7">
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="row">
                    <label class="col-md-5 col-form-label text-md-left">از حجم</label>
                    <input type="number" id="start_volume" name="start_volume" value="{{$filter['start_volume']}}"
                           class="form-control col-md-7">
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="row">
                    <label class="col-md-5 col-form-label text-md-left">تا حجم</label>
                    <input type="number" id="end_volume" name="end_volume" value="{{$filter['end_volume']}}"
                           class="form-control col-md-7">
                </div>
            </div>
        </div>
        <div class="row pb-5 justify-content-center">
            <input type="hidden" name="page" value="1"/>
            <div class="row pt-2">
                <input class="btn btn-info" type="submit" value="اعمال فیلتر">
            </div>
        </div>
    </form>
@endsection
@section('content')
    <div></div>
    {{ $users->appends(request()->input())->links() }}
    @include('dashboard.report.listUserExtend')
    {{ $users->appends(request()->input())->links() }}
@endsection
@section('scripts')
    <script>

    </script>
@endsection
