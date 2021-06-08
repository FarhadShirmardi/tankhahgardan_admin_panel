@extends('dashboard.layouts.master')
@section('title')
    <i class="fa fa-bar-chart"></i>
    متریک‌ها
@endsection
@section('content')
    <form method="get" action="" id="filter">
        {{ csrf_field() }}
        <div class="pb-5 pt-5 justify-content-center">
            <div class="row pt-5">
                <div class="col-md-3 col-sm-12 pr-2">
                    <div class="row">
                        <label class="col-md-5 col-form-label text-md-left">تاریخ شروع</label>
                        <input id="start_date" class="form-control range_date col-md-7" type="text" name="start_date"
                               value="{{$filter['start_date']}}">
                    </div>
                </div>
                <div class="col-md-3 col-sm-12 pr-2">
                    <div class="row">
                        <label class="col-md-5 col-form-label text-md-left">تاریخ پایان</label>
                        <input id="end_date" class="form-control range_date col-md-7" type="text" name="end_date"
                               value="{{$filter['end_date']}}">
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach(range(1, 5) as $i)
                    <div class="col-md-3 pt-3">
                        <div class="row">
                            <label class="col-md-5 col-form-label text-md-left">تاریخ {{$i}}</label>
                            <input id="selected_dates[]" class="form-control automation_metrics col-md-7" type="text"
                                   name="selected_dates[]"
                                   value="{{ $filter['selected_dates'][$i - 1] ?? null }}">
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row pt-5 justify-content-center">
                <input class="btn btn-success" type="submit" value="ثبت">
                @if(auth()->user()->can('export_automation_metrics'))
                    <input class="btn btn-warning mr-2" type="button" value="فایل خروجی" onclick="exportClick()">
                @endif
            </div>
        </div>
    </form>
    <div class="col-md-4">{{ $metrics->appends(request()->input())->links() }}</div>

    @include('dashboard.automation.metricsList')

    {{ $metrics->appends(request()->input())->links() }}
@endsection
@section('scripts')
    <script>
        function exportClick() {
            var form = document.getElementById('filter');
            form.action = '{{ route('dashboard.automation.export_metrics') }}';

            document.getElementById('filter').submit();
            form.action = '';
        }
    </script>
@endsection
