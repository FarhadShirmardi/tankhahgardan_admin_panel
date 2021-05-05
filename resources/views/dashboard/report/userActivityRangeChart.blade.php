@extends('dashboard.layouts.master')

@section('title')
    <div class="row">
        <div class="col-md-6">
            <i class="fa fa-bar-chart"></i>
            نمودار وضعیت تراکنش در بازه
        </div>
    </div>
@endsection
@section('filter')
@endsection
@section('content')
    <div id="rangeCount" class="my-5" style="width:100%;"></div>
@endsection
@section('chart')
    @include('dashboard.report.charts.rangeCount')
@endsection
@section('scripts')
@endsection
