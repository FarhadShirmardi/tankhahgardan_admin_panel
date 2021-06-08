@extends('dashboard.layouts.master')

@section('title')
    <i class="fa fa-calculator"></i>
    تراکنش‌ها
@endsection
@section('filter')
    <form id="filter" method="get" action="">
        {{ csrf_field() }}
        <input type="hidden" name="user_id" value="{{ $filter['user_id'] }}">
        <div class="pb-5 pt-5 justify-content-center">
            <div class="row">
                <div class="col-md-4 row">
                    <label class="col-md-5 col-form-label text-md-left">وضعیت</label>
                    <div class="ms-list col-md-7">
                        <select name="states[]" multiple="multiple" id="states" style="width: 100%">
                            @foreach($states as $state)
                                <option @if($state['is_selected']) selected @endif
                                value="{{$state['id']}}">{{$state['title']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4 row">
                    <label class="col-md-5 col-form-label text-md-left">طرح</label>
                    <div class="ms-list col-md-7">
                        <select name="plan_ids[]" multiple="multiple" id="plan_ids" style="width: 100%">
                            @foreach($plans as $plan)
                                <option @if($plan['is_selected']) selected @endif
                                value="{{$plan['id']}}">{{$plan['title']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4 row">
                    <label class="col-md-5 col-form-label text-md-left">بانک</label>
                    <div class="ms-list col-md-7">
                        <select name="bank_ids[]" multiple="multiple" id="bank_ids" style="width: 100%">
                            @foreach($banks as $bank)
                                <option @if($bank['is_selected']) selected @endif
                                value="{{$bank['id']}}">{{$bank['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row pt-3">
                <div class="col-md-4 row">
                    <label class="col-md-5 col-form-label text-md-left">تاریخ شروع</label>
                    <input id="start_date" class="form-control range_date col-md-7" type="text" name="start_date"
                           value="{{$filter['start_date']}}">
                </div>
                <div class="col-md-4 row">
                    <label class="col-md-5 col-form-label text-md-left">تاریخ پایان</label>
                    <input id="end_date" class="form-control range_date col-md-7" type="text" name="end_date"
                           value="{{$filter['end_date']}}">
                </div>
                <div class="col-md-4 row">
                    <label class="col-md-5 col-form-label text-md-left">نوع تراکنش</label>
                    <div class="ms-list col-md-7">
                        <select name="types[]" multiple="multiple" id="types" style="width: 100%">
                            @foreach($types as $type)
                                <option @if($type['is_selected']) selected @endif
                                value="{{$type['id']}}">{{$type['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row pt-3">
                <div class="col-md-4 row">
                    <label class="col-md-5 col-form-label text-md-left">مرتب‌سازی</label>
                    <select id="sort_field" name="sort_field">
                        @foreach($sortable_fields as $key => $sortable_field)
                            <option @if ($key == $filter['sort_field']) selected
                                    @endif value="{{ $key }}">{{ $sortable_field }}</option>
                        @endforeach
                    </select>
                    <select id="sort_type" name="sort_type">
                        @foreach($sortable_types as $key => $sortable_type)
                            <option @if ($key == $filter['sort_type']) selected
                                    @endif value="{{ $key }}">{{ $sortable_type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 row">
                        <label class="col-md-5 col-form-label text-md-left">جستجوی شماره</label>
                        <input type="text" id="phone_number" name="phone_number" value="{{$filter['phone_number']}}"
                               placeholder="جستجوی شماره" class="form-control col-md-7">
                </div>
            </div>
            <input type="hidden" name="page" value="1"/>
            <div class="pt-5 pb-3 justify-content-center align-center row">
                <div class="col-md-2">
                    <input class="form-control btn btn-info" type="submit" value="اعمال فیلتر">
                </div>
                <div class="col-md-2">
                    <a class="form-control btn btn-outline-primary" href="?">ریست کردن فیلتر</a>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('content')
    {{ $transactions->appends(request()->input())->links() }}
    @include('dashboard.management.listTransactions')
    {{ $transactions->appends(request()->input())->links() }}
    <script type="text/javascript">
        function selectAll() {
            $('#title_ids').attr('selected', 'selected');
        }

        $(document).ready(function () {
            $('#bank_ids').select2({
                width: 'element',
                allowClear: true,
                placeholder: 'انتخاب بانک'
            });
            $('#plan_ids').select2({
                width: 'element',
                allowClear: true,
                placeholder: 'انتخاب طرح'
            });
            $('#types').select2({
                width: 'element',
                allowClear: true,
                placeholder: 'انتخاب نوع تراکنش'
            });
            $('#states').select2({
                width: 'element',
                allowClear: true,
                placeholder: 'انتخاب وضعیت'
            });
            $('#sort_field').select2({
                width: 'element',
            });
            $('#sort_type').select2({
                width: 'element',
            });
        });

    </script>
@endsection
