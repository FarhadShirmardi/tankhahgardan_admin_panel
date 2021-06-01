@extends('dashboard.layouts.master')
@section('extra_css')
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/css/jsonview.css') }}">
    <script src="{{ asset('dashboard/js/jsonview.js') }}"></script>
@endsection
@section('title')
    <i class="fa fa-sticky-note"></i>
    مرکز لاگ
@endsection
@section('filter')

@endsection
@section('content')
    <div class="row">
        <label class="col-md-5 col-form-label text-md-left">تاریخ</label>
        <label class="col-md-7 col-form-label text-md-right ltr">{{ \App\Helpers\Helpers::convertDateTimeToJalali
        ($log->date_time) }}</label>
    </div>
    <div class="row">
        <label class="col-md-5 col-form-label text-md-left">کاربر</label>
        <label class="col-md-7 col-form-label text-md-right">{{ $log->username }}</label>
    </div>
    <div class="row">
        <label class="col-md-5 col-form-label text-md-left">کاربر پنل</label>
        <label class="col-md-7 col-form-label text-md-right">{{ $log->panel_username }}</label>
    </div>
    <div class="row">
        <label class="col-md-5 col-form-label text-md-left">موضوع</label>
        <label class="col-md-7 col-form-label text-md-right">{{ \App\Constants\LogType::getTitle($log->type) }}</label>
    </div>
    <div class="row">
        <label class="col-md-5 col-form-label text-md-left">شرح</label>
        <label class="col-md-7 col-form-label text-md-right">{{ $log->description }}</label>
    </div>
    <div class="row pt-5">
        <div class="col-md-6 row justify-content-center">
            <div class="row"> دیتای قدیمی</div>
            <div class="row pt-4 ltr">
                <div id="old_json"></div>
            </div>
        </div>
        <div class="col-md-6 row justify-content-center">
            <div class="row"> دیتای جدید</div>
            <div class="row pt-4 ltr">
                <div id="new_json"></div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const oldData = '{!! $log->old_json ?? '{}' !!}';
        const oldTree = JsonView.createTree(oldData);
        JsonView.render(oldTree, document.querySelector('#old_json'));
        JsonView.expandChildren(oldTree);

        const newData = '{!! $log->new_json ?? '{}' !!}';
        const newTree = JsonView.createTree(newData);
        JsonView.render(newTree, document.querySelector('#new_json'));
        JsonView.expandChildren(newTree);
    </script>
@endsection
