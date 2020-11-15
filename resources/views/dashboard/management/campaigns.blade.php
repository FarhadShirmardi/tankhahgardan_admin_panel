@extends('dashboard.layouts.master')

@section('title')
    <i class="fa fa-barcode"></i>
    کمپین‌ها
@endsection
@section('filter')

@endsection
@section('content')
    @include('dashboard.management.campaign_list', ['userIds' => $userIds ?? null])
    <div class="pt-5 pb-3 justify-content-center align-center row">
        <div class="col-md-2">
        </div>
        <div class="col-md-2">
            <a class="form-control btn btn-outline-success" href="{{ route('dashboard.campaignItem', ['id' => 0]) }}">افزودن کمپین</a>
        </div>
        <div class="col-md-2">
        </div>

    </div>
@endsection
