@extends('dashboard.layouts.master')

@section('title')
    <i class="fa fa-barcode"></i>
    @if($id)
        ویرایش کد تخفیف - کمپین {{ $campaign->name }} -
    @else
        افزودن کد تخفیف - کمپین {{ $campaign->name }} -
    @endif
    @if($userIds)
        @if($user)
            <span class="ltr">{{ $user->fullname }}</span>
        @else
            {{ 'جمعی از کابران' }}
        @endif
    @else
        {{ 'عمومی' }}
    @endif

@endsection
@section('filter')
@endsection
@section('content')
    <form method="post"
          action="{{ route('dashboard.promoCodeStore', ['campaignId'=> $campaignId, 'id' => $id, 'userIds' => $userIds]) }}">
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{ $promoCode['id'] ?? null }}">
        <div class="row">
            <div class="col-md-4 row">
                <label class="col-md-5 col-form-label text-md-left">عنوان تخفیف(قابل نمایش به کاربر)</label>
                <input name="text" class="form-control col-md-7" required
                       value="{{ $promoCode['text'] ?? null }}"/>
            </div>

            <div class="col-md-4 row">
                @if(!$userIds)
                    <label class="col-md-5 col-form-label text-md-left">کد</label>
                    <input name="code" class="form-control col-md-7 ltr" required
                           value="{{ $promoCode['code'] ?? null }}"/>
                @endif
            </div>
            <div class="col-md-4 row">
            </div>
        </div>
        <div class="row pt-2">
            <div class="col-md-4 row">
                <label class="col-md-5 col-form-label text-md-left">درصد تخفیف</label>
                <input name="discount_percent" class="form-control col-md-7 ltr" required
                       max="100" min="0" type="number"
                       value="{{ $promoCode['discount_percent'] ?? 0 }}"/>
            </div>
            <div class="col-md-4 row">
                <label class="col-md-5 col-form-label text-md-left">سقف تخفیف(ریال)</label>
                <input name="max_discount" class="form-control col-md-7 ltr" required
                       type="number" min="0"
                       value="{{ $promoCode['max_discount'] ?? 0 }}"/>
            </div>
            <div class="col-md-4 row">
                <label class="col-md-5 col-form-label text-md-left">تعداد کدهای قابل استفاده</label>
                <input name="max_count" class="form-control col-md-7 ltr" required @if ($userIds) disabled @endif
                type="number" min="1"
                       value="{{ $promoCode['max_count'] ?? 1 }}"/>
            </div>
        </div>
        <div class="row pt-2">
            <div class="col-md-4 row">
                <label class="col-md-5 col-form-label text-md-left">تاریخ شروع</label>
                <input required style="direction: ltr;" value="{{ $promoCode['start_at'] ?? null }}" id="start_at"
                       class="form-control time_picker col-md-7" type="text"
                       name="start_at">
            </div>
            <div class="col-md-4 row">
                <div class="col-md-5">
                    <div class="row">
                        <label class="col-md-12 col-form-label text-md-left">تاریخ انقضا</label>
                    </div>
                </div>
                <input style="direction: ltr;" value="{{ $promoCode['expire_at'] ?? now()->addWeek()->endOfDay()->toDateTimeString() }}" id="expire_at"
                       class="form-control time_picker col-md-7" type="text"
                       name="expire_at">
            </div>
            <div class="col-md-4 row">
                <label class="col-md-5 col-form-label text-md-left">تمپلیت sms</label>
                <input style="direction: ltr;" value="" id="template"
                       class="form-control col-md-7" type="text"
                       name="template">
            </div>
        </div>
        <div class="row justify-content-center pt-5">
            <div class="col-md-2">
                <input class="form-control btn btn-success" type="submit" value="ثبت">
            </div>
            @if($promoCode['id'] ?? false)
                <div class="col-md-2">
                    <button class="form-control btn btn-danger">
                        <a href="{{ route('dashboard.promoCodeDelete', ['id' => $promoCode->id]) }}">اتمام کد تخفیف</a>
                    </button>
                </div>
            @endif
        </div>
    </form>

    <hr>

    @include('dashboard.management.listTransactions')
    <script type="text/javascript">
    </script>
@endsection
