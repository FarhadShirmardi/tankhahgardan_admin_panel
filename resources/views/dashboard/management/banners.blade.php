@extends('dashboard.layouts.master')

@section('title')
    <i class="fa fa-ticket"></i>
    اعلان‌ها
@endsection
@section('filter')

@endsection
@section('content')
    <div id="ajax-table" style="overflow-x: auto;">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>ردیف</th>
                <th>عنوان</th>
                <th>وضعیت</th>
                <th>کاربر</th>
            </tr>
            </thead>
            <tbody>
            @foreach($banners as $banner)
                <tr class="clickableRow table-row-clickable align-center"
                    data-href="{{ route('dashboard.bannerItem', ['id' => $banner->id]) }}"
                    data-target="_self"
                >
                    <td>{{ $loop->iteration }}</td>
                    <td class="ltr text-right">{{ $banner->title }}</td>
                    <td>
                        @if($banner->is_active) <img src="{{ asset('dashboard/icons/icon_check.png') }}">
                        @else <img src="{{ asset('dashboard/icons/icon_uncheck.png') }}">
                        @endif
                    </td>
                    <td>{{ $banner->panel_user_name }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="pt-5 pb-3 justify-content-center align-center row">
        <div class="col-md-2">
        </div>
        <div class="col-md-2">
            <a class="form-control btn btn-outline-success"
               href="{{ route('dashboard.bannerItem', ['id' => 0]) }}">افزودن اعلان</a>
        </div>
        <div class="col-md-2">
        </div>

    </div>
@endsection
