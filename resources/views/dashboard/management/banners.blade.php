@extends('dashboard.layouts.master')

@section('title')
    <i class="fa fa-ticket"></i>
    بنرها
@endsection
@section('filter')
    <div class="pt-5 pb-3 justify-content-center align-center row">
        <div class="col-md-2">
        </div>
        <div class="col-md-2">
            <a class="form-control btn btn-outline-success"
               href="{{ route('dashboard.bannerItem', ['id' => 0]) }}">افزودن بنر</a>
        </div>
        <div class="col-md-2">
        </div>

    </div>
@endsection
@section('content')
    <div id="ajax-table" style="overflow-x: auto;">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>ردیف</th>
                <th>عنوان</th>
                <th>نوع</th>
                <th>تاریخ انقضا</th>
                <th>تاریخ شروع</th>
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
                    <td>{{ \App\Constants\BannerType::getEnum($banner->type) }}</td>
                    <td class="ltr text-right">{{ \App\Helpers\Helpers::convertDateTimeToJalali($banner->expire_at) }}</td>
                    <td class="ltr text-right">{{ \App\Helpers\Helpers::convertDateTimeToJalali($banner->start_at) }}</td>
                    <td>{{ \App\Constants\BannerStatus::getEnum($banner->status) }}</td>
                    <td>{{ $banner->panel_user_name }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
