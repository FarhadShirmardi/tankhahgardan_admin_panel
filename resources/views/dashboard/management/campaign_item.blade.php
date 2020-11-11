@extends('dashboard.layouts.master')

@section('title')
    <i class="fa fa-barcode"></i>
    @if($id)
        ویرایش کمپین
    @else
        افزودن کمپین
    @endif

@endsection
@section('filter')
@endsection
@section('content')
    <form method="post" action="{{ route('dashboard.campaignStore', ['id' => $id]) }}">
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{ $campaign['id'] ?? null }}">
        <div class="row">
            <div class="col-md-4 row">
                <label class="col-md-5 col-form-label text-md-left">نام</label>
                <input name="name" class="form-control col-md-7" required
                       value="{{ $campaign['name'] ?? null }}"/>
            </div>
            <div class="col-md-4 row">
                <label class="col-md-5 col-form-label text-md-left">نماد</label>
                <input name="symbol" class="form-control col-md-7 ltr" required
                       placeholder="SYM_EX1" value="{{ $campaign['symbol'] ?? null }}"/>
            </div>
            <div class="col-md-4 row">
                <label class="col-md-5 col-form-label text-md-left">تعداد</label>
                <input name="count" class="form-control col-md-7" disabled
                       value="{{ $campaign['count'] ?? null }}"/>
            </div>
        </div>
        <div class="row pt-2">
            <div class="col-md-4 row">
                <label class="col-md-5 col-form-label text-md-left">تاریخ شروع</label>
                <input required style="direction: ltr;" value="{{ $campaign['start_date'] ?? null }}" id="start_date"
                       class="form-control time_picker col-md-7" type="text"
                       name="start_date">
            </div>
            <div class="col-md-4 row">
                <label class="col-md-5 col-form-label text-md-left">تاریخ پایان</label>
                <input required style="direction: ltr;" value="{{ $campaign['end_date'] ?? null }}" id="end_date"
                       class="form-control time_picker col-md-7" type="text"
                       name="end_date">
            </div>
            <div class="col-md-4 row">

            </div>
        </div>
        <div class="row justify-content-center pt-5">
            <div class="col-md-2">
                <input class="form-control btn btn-success" type="submit" value="ثبت">
            </div>
            @if($campaign['id'] ?? false)
                <div class="col-md-2">
                    <button class="form-control btn btn-danger">
                        <a href="{{ route('dashboard.campaignDelete', ['id' => $campaign->id]) }}">اتمام کمپین</a>
                    </button>
                </div>
            @endif
        </div>
    </form>

    <hr>

    @include('dashboard.management.promoCode_list')

    @if($id ?? false)
        <div class="pt-5 pb-3 justify-content-center align-center row">
            <div class="col-md-2">
            </div>
            <div class="col-md-2">
                <a class="form-control btn btn-success"
                href="{{ route('dashboard.promoCodeItem', ['campaignId' => $id, 'id' => 0, 'userIds' => 0]) }}">افزودن
                    کد تخفیف</a>
            </div>
            <div class="col-md-2">
            </div>
        </div>
    @endif

    <script type="text/javascript">
    </script>
@endsection
