@extends('dashboard.layouts.master')

@section('title')
    <div class="row">
        <div class="col-md-6">
            <i class="fa fa-bar-chart"></i>
            نمودار وضعیت کاربران
        </div>
    </div>
@endsection
@section('filter')
@endsection
@section('content')
    <div id="dateChart" style="width:100%;"></div>
@endsection
@section('chart')
    @include('dashboard.report.charts.allUserActivity')
@endsection
@section('scripts')
@endsection
