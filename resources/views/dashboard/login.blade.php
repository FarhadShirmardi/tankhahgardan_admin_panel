<!DOCTYPE html>
<html lang="fa" dir="rtl">

@include('dashboard.layouts.head')

<body class="app flex-row align-items-center">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card-group mb-0">
                <div class="card p-4">
                    <div class="card-body">
                        @include('dashboard.layouts.error_message')
                        <h1>ورود</h1>
                        <p class="text-muted">ورود به پنل کاربری</p>
                        <form method="post" action="{{ route('dashboard.authenticate') }}">
                            {{ csrf_field() }}
                            <div class="input-group mb-3">
                                    <span class="input-group-addon"><i class="icon-user"></i>
                                    </span>
                                <input name="phone_number" value="{{ old('phone_number') }}" type="text" required
                                       class="form-control"
                                       placeholder="شماره تلفن">
                            </div>
                            <div class="input-group mb-4">
                                    <span class="input-group-addon"><i class="icon-lock"></i>
                                    </span>
                                <input name="password" type="password" required class="form-control"
                                       placeholder="رمز عبور">
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <button dusk="submit" type="submit" class="btn btn-primary px-4">ورود</button>
                                </div>
{{--                                <div class="col-6 text-right">--}}
{{--                                    <a href="{{ route('password.request') }}">--}}
{{--                                        <button type="button" class="btn btn-link px-0">یادآوری رمز عبور</button>--}}
{{--                                    </a>--}}
{{--                                </div>--}}
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('dashboard.layouts.scripts')

</body>

</html>
