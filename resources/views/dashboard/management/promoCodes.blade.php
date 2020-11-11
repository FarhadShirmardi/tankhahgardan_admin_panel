@extends('dashboard.layouts.master')

@section('title')
    <i class="fa fa-barcode"></i>
    کدهای تخفیف
    @if($user) @include('dashboard.layouts.username') @endif
@endsection
@section('filter')

@endsection
@section('content')

    @include('dashboard.management.promoCode_list')
    <hr>

    @if($user)
        <div class="col-md-2 justify-content-center">
            <a class="form-control btn btn-success" href="{{ route('dashboard.campaignUser', ['id' => $user->id]) }}">افزودن
                کد تخفیف</a>
        </div>
    @endif
@endsection
