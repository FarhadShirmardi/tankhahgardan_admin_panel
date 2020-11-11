@extends('dashboard.layouts.master')

@section('title')
    <i class="fa fa-bar-chart"></i>
    وضعیت پروژه - {{ $project->name }}
@endsection
@section('filter')

@endsection
@section('content')
    <div id="ajax-table">
        <table class="table table-striped table-responsive">
            <thead>
            <tr>
                <th>ردیف</th>
                <th>نام کاربر</th>
                <th>مالک پروژه</th>
                <th>تعداد پرداخت</th>
                <th>تعداد دریافت</th>
                <th>تعداد یادداشت</th>
                <th>تعداد تنخواه</th>
                <th>تعداد عکس</th>
                <th>حجم عکس</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr class="clickableRow table-row-clickable" data-href="{{ route('dashboard.report.userActivity', ['id' => $user->id]) }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ ($user->name or $user->family) ? ($user->name . ' ' . $user->family) : $user->phone_number }}</td>
                    <td>
                        @if($user->is_owner) <img src="{{ asset('dashboard/icons/icon_check.png') }}">
                        @endif
                    </td>
                    <td>{{ $user->payment_count }}</td>
                    <td>{{ $user->receive_count }}</td>
                    <td>{{ $user->note_count }}</td>
                    <td>{{ $user->imprest_count }}</td>
                    <td>{{ $user->image_count }}</td>
                    <td>{{ $user->image_size }}</td>
                </tr>
            @endforeach
            <tr class="table-primary">
                <td></td>
                <td>جمع</td>
                <td></td>
                <td>{{ $users->pluck('payment_count')->sum() }}</td>
                <td>{{ $users->pluck('receive_count')->sum() }}</td>
                <td>{{ $users->pluck('note_count')->sum() }}</td>
                <td>{{ $users->pluck('imprest_count')->sum() }}</td>
                <td>{{ $users->pluck('image_count')->sum() }}</td>
                <td>{{ $users->pluck('image_size')->sum() }}</td>
            </tr>
            </tbody>
        </table>
    </div>
@endsection
