<header class="app-header navbar">
    <button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" type="button">☰</button>
    <a class="navbar-brand" href="#"></a>
    <button class="navbar-toggler sidebar-minimizer d-md-down-none" type="button">☰</button>

    <ul class="nav navbar-nav d-md-down-none">
        <li class="nav-item px-3">
            <a class="nav-link" href="{{ route('dashboard.home') }}">میزکار</a>
        </li>
    </ul>
    <ul class="nav navbar-nav ml-auto">
        <li class="nav-item dropdown pl-2">
            <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button"
               aria-haspopup="true" aria-expanded="false">
                <img src="" class="img-avatar" alt="">
                <span class="d-md-down-none">{{ Auth::user()->name }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="#"><i
                        class="fa fa-user"></i> پروفایل</a>
                <a class="dropdown-item" href="{{ route('dashboard.changePasswordView') }}"><i
                        class="fa fa-user-secret"></i> تغییر رمز عبور</a>
                <div class="divider"></div>
                <a class="dropdown-item" href="{{ route('dashboard.logout') }}"><i class="fa fa-lock"></i> خروج</a>
            </div>
        </li>
    </ul>
</header>
