<div wire:init="load">
    @if($readyToLoad)
        <form id="filter" method="POST" action="">
            {{ csrf_field() }}
            <div class="row pb-4">
                <div class="col-md-4 row">
                    <label class="col-md-5 form-control-label text-md-left" for="user_count">
                        طرح
                    </label>
                </div>
                <div class="col-md-4 row">
                    <label class="col-md-5 form-control-label text-md-left" for="volume_size">
                        حجم (مگابایت)
                    </label>
                </div>
                <div class="col-md-4 row">
                    @if($type != \App\Constants\PurchaseType::UPGRADE)
                        <label class="col-md-5 form-control-label text-md-left">
                            زمان طرح
                        </label>
                        <select name="price_id" id="price_id" class="form-control col-md-7"
                                onchange="changePrice(this)">
                            @foreach($durations as $duration)
                                <option
                                    value="{{$duration['id']}}">{{ $duration['title'] }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
            </div>
            <div class="row pb-4">
                <div class="col-md-4 row">
                    <label class="col-md-5 form-control-label text-md-left" for="text">
                        توضیحات
                    </label>
                    <textarea required name="text" id="text" class="form-control col-md-7"></textarea>
                </div>
                <div class="row col-md-4">
                    <label class="col-md-5 col-form-label text-md-left" for="discount_price">مبلغ تخفیف</label>
                    <input required type="number" min="0"
                           id="discount_price" name="discount_price" value="0" class="col-md-7 form-control ">
                </div>
                <div class="row col-md-4">
                    <label class="col-md-5 form-control-label text-md-left" for="use_wallet">
                        استفاده از اعتبار ({{ \App\Helpers\Helpers::formatNumber($user->wallet_amount) }} ریال)
                    </label>
                    <input type="checkbox" name="use_wallet">
                </div>
            </div>
            <div class="row" style="display: none;" id="custom_price">
                <div class="row col-md-4">
                    @if($type == \App\Constants\PurchaseType::NEW)
                        <label class="col-md-5 col-form-label text-md-left" for="start_date">تاریخ شروع</label>
                        <input id="start_date" class="form-control col-md-7" type="text" name="start_date"
                               value="">
                    @endif
                </div>
                <div class="row col-md-4">
                    <label class="col-md-5 col-form-label text-md-left" for="end_date">تاریخ پایان</label>
                    <input id="end_date" class="form-control col-md-7" type="text" name="end_date" value="{{ now()->addMonth()
                }}">
                </div>
                <div class="row col-md-4">
                    <label class="col-md-5 col-form-label text-md-left" for="total_price">مبلغ کل</label>
                    <input required type="number" min="10000"
                           id="total_price" name="total_price" value="10000" class="col-md-7 form-control ">
                </div>
            </div>
            <div class="pt-5 pb-3 justify-content-center align-center row">
                <div class="col-md-2">
                    <input class="form-control btn btn-success" type="submit" value="پیش فاکتور">
                </div>
            </div>
        </form>

    @else
        @include('dashboard.layouts.loading')
    @endif
</div>
