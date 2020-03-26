@extends('dashboard.layouts.report')

@section('title')
    <i class="fa fa-line-chart"></i>
    کاربران ثبت‌نام شده به تفکیک ساعت
@endsection
@section('filter')
    <form method="post" action="">
        {{ csrf_field() }}
        <div class="row pb-5 pt-5 justify-content-center">
            <div class="pl-2">
                <label>تاریخ شروع</label>
                <input id="start_date" class="range_date" type="text" name="start_date" value="{{$start_date}}">
            </div>
            <div class="pl-2">
                <label>تاریخ پایان</label>
                <input id="end_date" class="range_date" type="text" name="end_date" value="{{$end_date}}">
            </div>
            <input class="btn btn-info" type="submit" value="اعمال فیلتر">
        </div>
    </form>
@endsection
@section('content')
    <div id="ajax-table">
        <table class="table table-striped">
            <thead>
            <tr>
                @foreach($users_time as $time)
                    <th>
                        {{$time->num * 4}} - {{($time->num + 1) * 4}}
                    </th>
                @endforeach
                <th>جمع</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                @foreach($users_time as $time)
                    <td>
                        {{$time->c}}
                    </td>
                @endforeach
                <td>
                    {{$sum}}
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div id="container" style="width:100%;"></div>
@endsection
@section('chart')
    @include('dashboard.report.charts.timeSeparation')
@endsection
