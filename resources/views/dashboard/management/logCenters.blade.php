@extends('dashboard.layouts.master')
@section('title')
    <i class="fa fa-sticky-note"></i>
    مرکز لاگ
@endsection
@section('filter')
    <form id="filter" method="get" action="">
        @csrf
        <div class="pt-2 pb-3 justify-content-center align-center">
            <div class="row">
                <div class="col-md-4 row">
                    <label class="col-md-5 col-form-label text-md-left">نوع لاگ</label>
                    <div class="ms-list col-md-7">
                        <select name="types[]" multiple="multiple" id="type_ids" style="width: 100%">
                            <option onselect="selectAll()"></option>
                            @foreach($types as $type)
                                <option @if($type['is_selected']) selected @endif
                                value="{{$type['id']}}">{{$type['title']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4 row">
                    <label class="col-md-5 col-form-label text-md-left">کاربر پنل</label>
                    <div class="ms-list col-md-7">
                        <select name="user_ids[]" multiple="multiple" id="user_ids" style="width: 100%">
                            <option onselect="selectAll()"></option>
                            @foreach($users as $user)
                                <option @if($user['is_selected']) selected @endif
                                value="{{$user['id']}}">{{$user['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row pt-4 justify-content-center">
                <input class="btn btn-info" type="submit" value="اعمال فیلتر">
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
                <th>کاربر</th>
                <th>کاربر پنل</th>
                <th>تاریخ</th>
                <th>موضوع</th>
                <th>شرح</th>
            </tr>
            </thead>
            <tbody>
            @foreach($logs as $log)
                <tr class="clickableRow table-row-clickable align-center" data-href="{{ route('dashboard.log_centers.show', ['id' => $log->id]) }}">
                    @if($logs instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <td>{{ ($logs->currentPage() - 1) * $logs->perPage() + $loop->iteration }}</td>
                    @else
                        <td>{{ $loop->iteration }}</td>
                    @endif
                    <td>{{ $log->username }}</td>
                    <td>{{ $log->panel_username }}</td>
                    <td class="ltr text-right">{{ \App\Helpers\Helpers::convertDateTimeToJalali($log->date_time) }}</td>
                    <td>{{ \App\Constants\LogType::getTitle($log->type) }}</td>
                    <td>{{ $log->description }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <script>
        function selectAll() {
            $('#title_ids').attr('selected', 'selected');
        }

        $(document).ready(function () {
            $('#type_ids').select2({
                width: 'element',
                allowClear: true,
                placeholder: 'انتخاب نوع'
            });

            $('#user_ids').select2({
                width: 'element',
                allowClear: true,
                placeholder: 'انتخاب کاربر'
            });
        });
    </script>
@endsection
