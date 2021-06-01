@extends('dashboard.layouts.master')

@section('title')
    <i class="fa fa-money"></i>
    گزارش معیارهای سنجش پولی
@endsection
@section('filter')
    <div class="pt-5 pb-3 justify-content-center align-center row">
        <form id="filter" method="get" action="">
            {{ csrf_field() }}
            <div class="row">
                <label class="col-md-5 col-form-label">تفکیک</label>
                <select id="type" class="form-control col-md-7" name="type">
                    @foreach(\App\Constants\PremiumReportType::toArray() as $pType)
                        <option @if ($pType == $type) selected
                                @endif value="{{ $pType }}">{{ \App\Constants\PremiumReportType::getEnum($pType)
                                    }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="row pb-5 pt-5 justify-content-center">
                <div class="row pt-2">
                    <input class="btn btn-info" type="submit" value="اعمال فیلتر">
                </div>
            </div>
        </form>
    </div>
@endsection
@section('content')
    @if($items instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="col-md-4">{{ $items->appends(request()->input())->links() }}</div>
    @endif
    <div id="ajax-table" style="overflow-x: auto;">
        <table class="table table-striped">
            <thead>
            <tr>
                @if($type != \App\Constants\PremiumReportType::FULL)
                    <th>ردیف</th>
                    <th>تاریخ</th>
                @endif
                <th>مبلغ پرداخت شده موفق</th>
                <th>مبلغ پرداخت شده ناموفق</th>
                <th>مبلغ پرداخت شده موفق بدون کیف پول و تخفیف</th>
                <th>تعداد موفق</th>
                <th>تعداد سالانه موفق</th>
                <th>تعداد ماهانه موفق</th>
                <th>تعداد ۱۵ روزه موفق</th>
                <th>تعداد خرید موفق</th>
                <th>تعداد تمدید موفق</th>
                <th>تعداد ارتقا امکانات موفق</th>
                <th>تعداد ناموفق</th>
                <th>تعداد سالانه ناموفق</th>
                <th>تعداد ماهانه ناموفق</th>
                <th>تعداد ۱۵ روزه ناموفق</th>
                <th>تعداد خرید ناموفق</th>
                <th>تعداد تمدید ناموفق</th>
                <th>تعداد ارتقا امکانات ناموفق</th>
            </tr>
            </thead>
            <tbody>
            @foreach($items as $item)
                <tr>
                    @if($type != \App\Constants\PremiumReportType::FULL)
                        @if(!$items instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            <td>{{ $loop->iteration }}</td>
                        @else
                            <td>{{ ($items->currentPage() - 1) * $items->perPage() + $loop->iteration }}</td>
                        @endif
                        <th>{{ $item['date'] }}</th>
                    @endif
                    <td class="ltr text-right">{{ \App\Helpers\Helpers::formatNumber($item['successful_amount']) }}</td>
                    <td class="ltr text-right">{{ \App\Helpers\Helpers::formatNumber($item['unsuccessful_amount'])
                    }}</td>
                    <td class="ltr text-right">{{ \App\Helpers\Helpers::formatNumber($item['successful_amount_pure'])
                     }}</td>
                    <td class="ltr text-right">{{ $item['successful_count'] }}</td>
                    <td class="ltr text-right">{{ $item['successful_year_count'] }}</td>
                    <td class="ltr text-right">{{ $item['successful_month_count'] }}</td>
                    <td class="ltr text-right">{{ $item['successful_half_month_count'] }}</td>
                    <td class="ltr text-right">{{ $item['successful_new_count'] }}</td>
                    <td class="ltr text-right">{{ $item['successful_upgrade_count'] }}</td>
                    <td class="ltr text-right">{{ $item['successful_extend_count'] }}</td>
                    <td class="ltr text-right">{{ $item['unsuccessful_count'] }}</td>
                    <td class="ltr text-right">{{ $item['unsuccessful_year_count'] }}</td>
                    <td class="ltr text-right">{{ $item['unsuccessful_month_count'] }}</td>
                    <td class="ltr text-right">{{ $item['unsuccessful_half_month_count'] }}</td>
                    <td class="ltr text-right">{{ $item['unsuccessful_new_count'] }}</td>
                    <td class="ltr text-right">{{ $item['unsuccessful_upgrade_count'] }}</td>
                    <td class="ltr text-right">{{ $item['unsuccessful_extend_count'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
