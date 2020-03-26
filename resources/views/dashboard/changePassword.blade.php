@extends('dashboard.layouts.master')
@section('title')
    <i class="fa fa-user-secret"></i>
    تغییر رمز عبور
@endsection
@section('content')
    <form method="post" action="{{ route('dashboard.changePassword') }}">
        {{ csrf_field() }}
        <div class="input-group mb-3">
                                    <span class="input-group-addon"><i class="icon-lock"></i>
                                    </span>
            <input name="old_password" value="{{ old('old_password') }}" type="password" required
                   class="form-control"
                   placeholder="رمز عبور">
        </div>
        <div class="input-group mb-4">
                                    <span class="input-group-addon"><i class="icon-lock"></i>
                                    </span>
            <input name="password" type="password" required class="form-control"
                   placeholder="رمز عبور جدید">
        </div>
        <div class="input-group mb-4">
                                    <span class="input-group-addon"><i class="icon-lock"></i>
                                    </span>
            <input name="password_confirmation" type="password" required class="form-control"
                   placeholder="تکرار رمز عبور">
        </div>
        <div class="row">
            <div class="col-6">
                <button dusk="submit" type="submit" class="btn btn-primary px-4">ثبت</button>
            </div>
        </div>
    </form>

@endsection
