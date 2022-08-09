@extends('dashboard.layouts.master')

@section('title')
    <div class="row">
        <div class="col-md-6">
            <i class="fa fa-money"></i>
            گزارش کاربرانی که پول داده‌اند و طرحشان فعال نشده است
        </div>
    </div>
@endsection
@section('filter')

@endsection
@section('content')
    <div></div>
    <div id="ajax-table" style="overflow-x: auto;">
        <table class="table">
            <thead>
            <tr>
                <th>ردیف</th>
                <th>شماره تلفن</th>
                <th>نام و نام خانوادگی</th>
                <th>کد پیگیری</th>
                <th>تاریخ</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $item)
                <tr class="clickableRow table-row-clickable"
                    data-href="{{ route('dashboard.report.userActivity', ['id' => $item->user_id]) }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->phone_number }}</td>
                    <td>{{ $item->username }}</td>
                    <td>{{ $item->authority }}</td>
                    <td class="ltr">{{ $item->date }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
@section('scripts')
@endsection
