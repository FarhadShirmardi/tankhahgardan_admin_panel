@extends('dashboard.layouts.master')
@section('title')
    <i class="fa fa-credit-card"></i>
    {{ \App\Constants\PurchaseType::getEnum($type) }} طرح /
    @include('dashboard.layouts.username')
@endsection
@section('content')
    <form id="filter" method="POST" action="">
        {{ csrf_field() }}
        <div class="row pb-4">
            <div class="col-md-4 row">
                <label class="col-md-5 form-control-label text-md-left" for="user_count">
                    تعداد کاربر
                </label>
                <select name="user_count" id="user_count" class="form-control col-md-7">
                    @foreach(\App\Constants\PremiumPrices::filterPrice(\App\Constants\PremiumConstants::USER_PRICE,
                    1, $current_plan ? $current_plan->user_count : 0, $type == \App\Constants\PurchaseType::UPGRADE) as
                    $price)
                        <option value="{{ $price['value'] }}">{{ \App\Helpers\Helpers::formatNumber($price['value'])
                        }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 row">
                <label class="col-md-5 form-control-label text-md-left" for="volume_size">
                    حجم (مگابایت)
                </label>
                <select name="volume_size" id="volume_size" class="form-control col-md-7">
                    @foreach(\App\Constants\PremiumPrices::filterPrice(\App\Constants\PremiumConstants::VOLUME_PRICE,
                    1,
                     $current_plan ? $current_plan->volume_size : 0, $type == \App\Constants\PurchaseType::UPGRADE) as
                     $price)
                        <option value="{{ $price['value'] }}">{{ \App\Helpers\Helpers::formatNumber($price['value'])
                        }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 row">
                @if($type != \App\Constants\PurchaseType::UPGRADE)
                    <label class="col-md-5 form-control-label text-md-left">
                        نوع طرح
                    </label>
                    <select name="price_id" id="price_id" class="form-control col-md-7"
                            onchange="changePrice(this)">
                        @foreach($prices as $price)
                            <option value="{{$price}}">{{ \App\Constants\PremiumDuration::getTitle($price) }}</option>
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
@endsection
@section('scripts')
    <script>
        function changePrice(selectedObject) {
            var value = selectedObject.value;
            var x = document.getElementById('custom_price')
            if (value == '{{ \App\Constants\PremiumDuration::SPECIAL }}') {
                x.style.display = "flex";
            } else {
                x.style.display = "none";
            }
        }

        $(document).ready(function () {
            $('#start_date').pDatepicker({
                format: "YYYY/MM/DD H:m",
                altFormat: "YYYY/MM/DD H:m",
                initialValue: true,
                initialValueType: 'gregorian',
                autoClose: true,
                minDate: '{{ now()->toDateTimeString() }}',
                calendar: {
                    persian: {
                        locale: 'fa',
                        showHint: false,
                        leapYearMode: 'algorithmic'
                    }
                },
                timePicker: {
                    enabled: true,
                    step: 1,
                    hour: {
                        enabled: true,
                        step: null
                    },
                    minute: {
                        enabled: true,
                        step: 5
                    },
                    second: {
                        enabled: false,
                        step: null
                    }
                },
            });

            $('#end_date').pDatepicker({
                format: "YYYY/MM/DD H:m",
                altFormat: "YYYY/MM/DD H:m",
                initialValue: true,
                initialValueType: 'gregorian',
                autoClose: true,
                minDate: '{{ \App\Helpers\Helpers::convertDateTimeToGregorian($selected_plan['end_date'] ?? '') }}',
                calendar: {
                    persian: {
                        locale: 'fa',
                        showHint: false,
                        leapYearMode: 'algorithmic'
                    }
                },
                timePicker: {
                    enabled: true,
                    step: 1,
                    hour: {
                        enabled: true,
                        step: null
                    },
                    minute: {
                        enabled: true,
                        step: 5
                    },
                    second: {
                        enabled: false,
                        step: null
                    }
                },
            });
        });
    </script>
@endsection
