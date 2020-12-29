@extends('dashboard.layouts.master')
@section('title')
    <i class="fa fa-users"></i>
    کاربران پنل
@endsection
@section('content')
    @include('dashboard.admin.user_table')
    <a class="btn-info" href="{{ route('dashboard.admin.user_item') }}">ثبت کاربر جدید</a>
@endsection
