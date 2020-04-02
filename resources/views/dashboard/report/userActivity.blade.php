@extends('dashboard.layouts.master')

@section('title')
    <i class="fa fa-bar-chart"></i>
    وضعیت کاربر - {{ $user->full_name }}
@endsection
@section('filter')

@endsection
@section('content')
    <div id="ajax-table">
        <table class="table table-striped table-responsive">
            <thead>
            <tr>
                <th>ردیف</th>
                <th>نام پروژه</th>
                <th>مالک پروژه</th>
                <th>تعداد پرداخت</th>
                <th>تعداد دریافت</th>
                <th>تعداد یادداشت</th>
                <th>تعداد تنخواه</th>
            </tr>
            </thead>
            <tbody>
            @foreach($projects as $project)
                <tr class="clickableRow table-row-clickable" data-href="{{ route('dashboard.report.projectActivity', ['id' => $project->id]) }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $project->name }}</td>
                    <td>
                        @if($project->is_owner) <img src="{{ asset('dashboard/icons/icon_check.png') }}">
                        @else <img src="{{ asset('dashboard/icons/icon_uncheck.png') }}">
                        @endif
                    </td>
                    <td>{{ $project->payment_count }}</td>
                    <td>{{ $project->receive_count }}</td>
                    <td>{{ $project->note_count }}</td>
                    <td>{{ $project->imprest_count }}</td>
                </tr>
            @endforeach
            <tr class="table-primary">
                <td></td>
                <td>جمع</td>
                <td></td>
                <td>{{ $projects->pluck('payment_count')->sum() }}</td>
                <td>{{ $projects->pluck('receive_count')->sum() }}</td>
                <td>{{ $projects->pluck('note_count')->sum() }}</td>
                <td>{{ $projects->pluck('imprest_count')->sum() }}</td>
            </tr>
            </tbody>
        </table>
    </div>
    <div id="chart" class="my-5" style="width:100%;"></div>
    <div id="chart2" class="my-5" style="width:100%;"></div>
@endsection
@section('chart')
    @include('dashboard.report.charts.userActivity')
@endsection
