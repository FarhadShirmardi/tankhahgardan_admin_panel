<header class="app-header navbar">
    <button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" type="button">☰</button>
    <a class="navbar-brand" href="#"></a>
    <button class="navbar-toggler sidebar-minimizer d-md-down-none" type="button">☰</button>

    <ul class="nav navbar-nav d-md-down-none">
        <li class="nav-item px-3">
            <a class="nav-link" href="#">میزکار</a>
        </li>
    </ul>
    <ul class="nav navbar-nav ml-auto">
        <li class="nav-item d-md-down-none">
            <a class="nav-link" href="#"><i class="icon-bell"></i>
                @if($numOfUnreadNotifications ?? 0)
                    <span class="badge badge-pill badge-danger">{{ $numOfUnreadNotifications }}</span>
                @endif
            </a>
        </li>
        <li class="nav-item d-md-down-none">
            <a class="nav-link" href="#"><i class="icon-list"></i></a>
        </li>
        <li class="nav-item d-md-down-none">
            <a class="nav-link" href="#"><i class="icon-location-pin"></i></a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button"
               aria-haspopup="true" aria-expanded="false">
                <img src="" class="img-avatar" alt="">
                <span class="d-md-down-none">{{ Auth::user()->full_name }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                {{--<div class="dropdown-header text-center">--}}
                {{--<strong>حساب کاربری</strong>--}}
                {{--</div>--}}
                {{--<a class="dropdown-item" href="#"><i class="fa fa-bell-o"></i> بروزرسانی ها<span--}}
                {{--class="badge badge-info">42</span></a>--}}
                {{--<a class="dropdown-item" href="#"><i class="fa fa-envelope-o"></i> پیام ها<span--}}
                {{--class="badge badge-success">42</span></a>--}}
                {{--<a class="dropdown-item" href="#"><i class="fa fa-tasks"></i> کارها<span--}}
                {{--class="badge badge-danger">42</span></a>--}}
                {{--<a class="dropdown-item" href="#"><i class="fa fa-comments"></i> نظرات<span class="badge badge-warning">42</span></a>--}}
{{--                <div class="dropdown-header text-center">--}}
{{--                    <strong>تنظیمات</strong>--}}
{{--                </div>--}}
                <a class="dropdown-item" href="{{ route('dashboard.profile.edit') }}"><i
                        class="fa fa-user"></i> پروفایل</a>
{{--                <a class="dropdown-item" href="#"><i class="fa fa-wrench"></i>--}}
{{--                    تنظیمات</a>--}}
                {{--<a class="dropdown-item" href="#"><i class="fa fa-usd"></i> پرداخت ها<span class="badge badge-default">42</span></a>--}}
                {{--<a class="dropdown-item" href="#"><i class="fa fa-file"></i> پروژه ها<span class="badge badge-primary">42</span></a>--}}
                <div class="divider"></div>
                {{--<a class="dropdown-item" href="#"><i class="fa fa-shield"></i> قفل کردن حساب کاربری</a>--}}
                <a class="dropdown-item" href="{{ route('dashboard.logout') }}"><i class="fa fa-lock"></i> خروج</a>
            </div>
        </li>
    </ul>
{{--    <button class="navbar-toggler aside-menu-toggler" type="button">☰</button>--}}

</header>
