@extends('dashboard.layouts.master')
@section('title')
    <i class="fa fa-google-wallet"></i>
    ویرایش کیف پول - @include('dashboard.layouts.username')
@endsection
@section('content')
    <form id="filter" method="POST" action="">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-md-4 row">
                <label class="col-md-5 form-control-label text-md-left">مبلغ کیف پول</label>
                <span class="col-md-7 form-control-label">{{ \App\Helpers\Helpers::formatNumber($user->wallet) }}</span>
            </div>
            <div class="col-md-4 row">
                <label class="col-md-5 form-control-label text-md-left">
                    مبلغ شارژ (ریال)
                </label>
                <input required type="number" min="10000" id="charge_amount" name="charge_amount" class="col-md-7
                form-control "/>
            </div>
            <div class="col-md-4 row">
                <label class="col-md-5 form-control-label text-md-left">
                    کم کردن اعتبار
                </label>
                <input type="checkbox" name="minus">
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 row">
                <label class="col-md-5 form-control-label text-md-left">
                    توضیحات
                </label>
                <div class="ms-list col-md-7">
                    <textarea required name="text" id="text" class="form-control"></textarea>
                </div>
            </div>
        </div>
        <div class="pt-5 pb-3 justify-content-center align-center row">
            <div class="col-md-2">
                <input class="form-control btn btn-success" type="submit" value="تایید">
            </div>
        </div>

    </form>
@endsection
@section('scripts')

@endsection
