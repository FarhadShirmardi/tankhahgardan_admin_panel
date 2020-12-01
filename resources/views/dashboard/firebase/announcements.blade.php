@extends('dashboard.layouts.master')

@section('title')
    <i class="fa fa-ticket"></i>
    اعلان‌ها
@endsection
@section('filter')
    <form id="filter" method="get" action="{{ route('dashboard.announcements') }}">
        {{ csrf_field() }}
        <div class="pb-5 pt-5 justify-content-center">
            <div class="row">
                <div class="col-md-4 row">
                    <label class="col-md-5 col-form-label text-md-left">وضعیت اعلان</label>
                    <div class="ms-list col-md-7">
                        <select name="announcement_status[]" multiple="multiple" id="announcement_statuses"
                                style="width: 100%">
                            @foreach($announcement_status as $announcementStatus)
                                <option @if($announcementStatus['is_selected']) selected @endif
                                value="{{$announcementStatus['value']}}">{{$announcementStatus['text']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4 row">
                    <label class="col-md-5 col-form-label text-md-left">نوع اعلان</label>
                    <div class="ms-list col-md-7">
                        <select name="announcement_type[]" multiple="multiple" id="announcement_typees"
                                style="width: 100%">
                            @foreach($announcement_type as $announcementType)
                                <option @if($announcementType['is_selected']) selected @endif
                                value="{{$announcementType['value']}}">{{$announcementType['text']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4 row">
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
                <div class="col-md-2">
                    <a class="form-control btn btn-outline-success"
                       href="{{ route('dashboard.announcementItem', ['id' => 0]) }}">افزودن اعلان</a>
                </div>
            </div>
        </div>
    </form>

@endsection
@section('content')
    <div id="ajax-table" style="overflow-x: auto;">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>ردیف</th>
                <th>عنوان</th>
                <th>متن</th>
                <th>خلاصه</th>
                <th>تاریخ انقضا</th>
                <th>تاریخ ارسال</th>
                <th>کاربر</th>
                <th>وضعیت</th>
                <th>نوع</th>
                <th>تعداد نمایش</th>
            </tr>
            </thead>
            <tbody>
            @foreach($announcements as $announcement)
                <tr class="clickableRow table-row-clickable align-center"
                    data-href="{{ route('dashboard.announcementItem', ['id' => $announcement->id]) }}"
                    data-target="_self"
                >
                    <td>{{ $loop->iteration }}</td>
                    <td class="ltr text-right">{{ $announcement->title }}</td>
                    <td class="breaking-text">{{ $announcement->text }}</td>
                    <td class="breaking-text">{{ $announcement->summary }}</td>
                    <td class="ltr text-right">{{ \App\Helpers\Helpers::convertDateTimeToJalali($announcement->expire_at) }}</td>
                    <td class="ltr text-right">{{ \App\Helpers\Helpers::convertDateTimeToJalali($announcement->send_at) }}</td>
                    <td>{{ $announcement->panel_user_name }}</td>
                    <td>{{ \App\Constants\AnnouncementStatus::getEnum($announcement->announcement_status) }}</td>
                    <td>{{ \App\Constants\AnnouncementType::getEnum($announcement->user_type) }}</td>
                    <td>{{ $announcement->announcement_count }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#announcement_statuses').select2({width: 'element'});
            $('#announcement_typees').select2({width: 'element'});
        });
    </script>

@endsection
