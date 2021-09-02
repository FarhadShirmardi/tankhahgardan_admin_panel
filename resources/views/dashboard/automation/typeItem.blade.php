@extends('dashboard.layouts.master')
@section('title')
    <i class="fa fa-bar-chart"></i>
    گزارش وضعیت - {{ $mappings[$type]['title'] }}
    @if($mappings[$type]['type'] == 'call')
        <i class="fa fa-phone">
        </i>
    @endif
    @if($mappings[$type]['type'] != 'none')
        <i class="fa fa-commenting">
        </i>
    @endif
@endsection
@section('content')
    {{ $items->appends(request()->input())->links() }}
    <div id="ajax-table" style="overflow-x: auto;">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>ردیف</th>
                <th>شماره تلفن</th>
                <th>نام و نام‌خانوادگی</th>
                <th>تعداد تراکنش</th>
                <th>آخرین ثبت</th>
                <th>تماس‌ها</th>
            </tr>
            </thead>
            <tbody>
            @foreach($items as $item)
                <tr class="clickableRow table-row-clickable"
                    data-href="{{ route('dashboard.report.userActivity', ['id' => $item->id]) }}">
                    <td>{{($items->currentPage() - 1) * $items->perPage() + $loop->iteration}}</td>
                    <td>{{ $item['phone_number'] }}</td>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['transaction_count'] }}</td>
                    <td class="ltr" style="text-align: right">{{ $item['max_time'] ?
                    \App\Helpers\Helpers::convertDateTimeToJalali
                    ($item['max_time']) : '' }}</td>
                    <td>
                        <div class="row">
                            <div class="col-md-6">
                                <img src="{{ asset('dashboard/icons/missed-call.png') }}" width="24" alt="">
                                <div class="badge-corner"><span>{{ $item['missed_count'] }}</span></div>
                            </div>
                            <div class="col-md-6">
                                <img src="{{ asset('dashboard/icons/phone-call.png') }}" width="24" alt="">
                                <div class="badge-corner"><span>{{ $item['total_count'] - $item['missed_count']
                                }}</span></div>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $items->appends(request()->input())->links() }}
@endsection
@section('scripts')

@endsection

