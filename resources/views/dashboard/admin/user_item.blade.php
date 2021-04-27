@extends('dashboard.layouts.master')
@section('title')
    <i class="fa fa-users"></i>
    کاربران پنل
@endsection
@section('content')
    <form method="post" action="">
        {{ csrf_field() }}
        <div class="pb-5 pt-5 justify-content-center">
            <div class="row">
                <label class="col-md-3 col-form-label text-md-left">شماره تلفن</label>
                <input type="text" id="phone_number" name="phone_number" required
                       value="{{ $user['phone_number'] ?? '' }}" class="form-control col-md-7">
            </div>
            <div class="row pt-5">
                <label class="col-md-3 col-form-label text-md-left">نام</label>
                <input type="text" id="name" name="name" required
                       value="{{ $user['name'] ?? '' }}" class="form-control col-md-7">
            </div>
            @if(!$user)
                <div class="row pt-5">
                    <label class="col-md-3 col-form-label text-md-left">رمزعبور</label>
                    <input type="text" id="password" name="password" required
                           class="form-control col-md-7">
                </div>
            @endif
            <div class="pt-5 row justify-content-center">
                <div class="row col-md-12 justify-content-center">
                    <div class="col-md-4 ">
                        <h3 ondblclick="toggle()">سطح دسترسی</h3>
                    </div>
                </div>

                <div class="row">
                    @foreach($permissions as $groupPermission)
                        <div class="col-md-12 row pb-2">
                            <div class="col-md-2"></div>
                            @foreach($groupPermission as $permission => $permissionName)
                                <div class="col-md-3">
                                    <input type="checkbox" name="permission_checkbox[]" value="{{ $permission }}"
                                           id="{{ $permission }}">
                                    <label for="{{ $permission }}">{{ $permissionName }}</label>
                                </div>
                            @endforeach
                            <div class="col-md-2"></div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="row pt-5 justify-content-center">
                <input class="btn btn-success" type="submit" value="ثبت">
            </div>
        </div>
    </form>
    @if($user)
        <hr class="pt-10">
        <form method="post" action="{{ route('dashboard.admin.resetPassword', ['id' => $user->id]) }}">
            {{ csrf_field() }}
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
    @endif
@endsection
@section('scripts')
    <script>
        function toggle() {
            checkboxes = document.getElementsByName('permission_checkbox[]');
            for (var i = 0, n = checkboxes.length; i < n; i++) {
                checkboxes[i].checked = !checkboxes[i].checked;
            }
        }
    </script>
@endsection
