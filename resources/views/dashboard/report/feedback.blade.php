@extends('dashboard.layouts.master')

@section('title')
    <i class="fa fa-comment"></i>
    بازخورد کاربران
@endsection
@section('filter')
    <form id="filter" method="get" action="{{ route('dashboard.feedbacks') }}">
        {{ csrf_field() }}
        <div class="pb-5 pt-5 justify-content-center">
            <div class="row">
                <div class="col-md-4 row">
                    <label class="col-md-5 col-form-label text-md-left">منبع بازخورد</label>
                    <div class="ms-list col-md-7">
                        <select name="source_type[]" multiple="multiple" id="source_types" style="width: 100%">
                            @foreach($source_type as $sourceType)
                                <option @if($sourceType['is_selected']) selected @endif
                                value="{{$sourceType['value']}}">{{$sourceType['text']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4 row">
                    <label class="col-md-5 col-form-label text-md-left">موضوع بازخورد</label>
                    <div class="ms-list col-md-7">
                        <select name="titles[]" multiple="multiple" id="title_ids" style="width: 100%">
                            <option onselect="selectAll()"></option>
                            @foreach($titles as $title)
                                <option @if($title['is_selected']) selected @endif
                                value="{{$title['id']}}">{{$title['title']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4 row">
                    <label class="col-md-5 col-form-label text-md-left">کاربر</label>
                    <div class="ms-list col-md-7">
                        <select name="user_id" id="user_id" style="width: 100%">
                            <option></option>
                            @foreach($users as $key => $user)
                                <option @if($user['is_selected']) selected @endif
                                value="{{$user->id}}">{{$user->full_name . ' - ' . $user->phone_number}}</option>
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
                    <label class="col-md-5 col-form-label text-md-left">کارشناس</label>
                    <div class="ms-list col-md-7">
                        <select name="panel_user_ids[]" multiple="multiple" id="panel_user_ids" style="width: 100%">
                            @foreach($panel_users as $panelUser)
                                <option @if($panelUser['is_selected']) selected @endif
                                value="{{$panelUser['id']}}">{{$panelUser['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="pt-3 row">
                <div class="col-md-4 row">
                    <label class="col-md-5 col-form-label text-md-left">مرتب‌سازی اول</label>
                    <select class="col-md-4" id="sort_field_1" name="sort_field_1">
                        @foreach($sortable_fields as $key => $sortable_field)
                            <option @if ($key == $filter['sort_field_1']) selected
                                    @endif value="{{ $key }}">{{ $sortable_field }}</option>
                        @endforeach
                    </select>
                    <select class="col-md-3" id="sort_type_1" name="sort_type_1">
                        @foreach($sortable_types as $key => $sortable_type)
                            <option @if ($key == $filter['sort_type_1']) selected
                                    @endif value="{{ $key }}">{{ $sortable_type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 row">
                    <label class="col-md-5 col-form-label text-md-left">مرتب‌سازی دوم</label>
                    <select class="col-md-4" id="sort_field_2" name="sort_field_2">
                        @foreach($sortable_fields as $key => $sortable_field)
                            <option @if ($key == $filter['sort_field_2']) selected
                                    @endif value="{{ $key }}">{{ $sortable_field }}</option>
                        @endforeach
                    </select>
                    <select class="col-md-3" id="sort_type_2" name="sort_type_2">
                        @foreach($sortable_types as $key => $sortable_type)
                            <option @if ($key == $filter['sort_type_2']) selected
                                    @endif value="{{ $key }}">{{ $sortable_type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 row">
                    @if(auth()->user()->hasRole('Admin'))
                        <label class="col-md-5 col-form-label text-md-left">امتیاز</label>
                        <div class="ms-list col-md-7">
                            <select name="scores[]" multiple="multiple" id="scores" style="width: 100%">
                                @foreach($scores as $score)
                                    <option @if($score['is_selected']) selected @endif
                                    value="{{$score['id']}}">{{$score['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>
            </div>
            <div class="pt-3 row">
                <div class="col-md-4 row"></div>
                <div class="col-md-4 row">
                    <label class="col-md-5 col-form-label text-md-left">وضعیت
                        بازخورد</label>
                    <div class="ms-list col-md-7">
                        <select name="states[]" multiple="multiple" id="states" style="width: 100%">
                            @foreach($states as $state)
                                <option @if($state['is_selected']) selected @endif
                                value="{{$state['id']}}">{{$state['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4 row">
                    <label class="col-md-5 col-form-label text-md-left">پلتفرم</label>
                    <div class="ms-list col-md-7">
                        <select name="platforms[]" multiple="multiple" id="platforms" style="width: 100%">
                            @foreach($platforms as $platform)
                                <option @if($platform['is_selected']) selected @endif
                                value="{{$platform['id']}}">{{$platform['name']}}</option>
                            @endforeach
                        </select>
                    </div>
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
                @if(auth()->user()->can('new_feedback'))
                    <div class="col-md-2">
                        <a class="form-control btn btn-outline-success" href="{{ route('dashboard.commentView') }}">افزودن
                            بازخورد</a>
                    </div>
                @endif
            </div>
        </div>
    </form>
@endsection
@section('content')
    {{ $feedbacks->appends(request()->input())->links() }}
    @include('dashboard.report.listFeedback')
    {{ $feedbacks->appends(request()->input())->links() }}
    <script type="text/javascript">
        function selectAll() {
            $('#title_ids').attr('selected', 'selected');
        }

        $(document).ready(function () {
            $('#source_types').select2({width: 'element'});
            $('#title_ids').select2({width: 'element'});
            $('#panel_user_ids').select2({
                width: 'element',
                placeholder: 'انتخاب کارشناس'
            });
            $('#scores').select2({
                width: 'element',
                placeholder: 'امتیاز کاربر'
            });
            $('#user_id').select2({
                width: 'element',
                allowClear: true,
                placeholder: 'انتخاب کاربر'
            });
            $('#platforms').select2({
                width: 'element',
                allowClear: true,
                placeholder: 'انتخاب پلفرم'
            });
            $('#states').select2({
                width: 'element',
                allowClear: true,
                placeholder: 'انتخاب وضعیت'
            });
            $('#sort_field_1').select2({width: 'element'});
            $('#sort_field_2').select2({width: 'element'});
            $('#sort_type_1').select2({width: 'element'});
            $('#sort_type_2').select2({width: 'element'});
        });

    </script>
@endsection
