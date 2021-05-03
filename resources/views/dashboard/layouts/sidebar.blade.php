<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            @if(auth()->user()->can('view_registration'))
                <li class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-pie-chart"></i>گزارش ثبت نام</a>
                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard.report.timeSeparation') }}">
                                <i class="icon-chart"></i>
                                تفکیک ساعت
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard.report.daySeparation') }}">
                                <i class="icon-chart"></i>
                                تفکیک روز هفته
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               href="{{ route('dashboard.report.rangeSeparation') }}">
                                <i class="icon-chart"></i>
                                تفکیک روز
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
            @if(auth()->user()->can('view_users_report'))
                <li class="nav-item nav-dropdown">
                    <a class="nav-link" href="{{ route('dashboard.report.allUsersActivity') }}"><i
                            class="icon-pie-chart"></i>گزارش وضعیت کاربران</a>
                </li>
            @endif
            @if(auth()->user()->can('view_projects_report'))
                <li class="nav-item nav-dropdown">
                    <a class="nav-link" href="{{ route('dashboard.report.allProjectsActivity') }}"><i
                            class="icon-pie-chart"></i>گزارش وضعیت پروژه</a>
                </li>
            @endif
            @if(auth()->user()->can('view_feedback'))
                <li class="nav-item nav-dropdown">
                    <a class="nav-link" href="{{ route('dashboard.feedbacks') }}"><i class="icon-user"></i>بازخوردها</a>
                </li>
            @endif
            @if(auth()->user()->can('view_notification'))
                <li class="nav-item nav-dropdown">
                    <a class="nav-link" href="{{ route('dashboard.announcements') }}"><i class="icon-bell"></i>اعلان‌ها</a>
                </li>
            @endif
            @if(auth()->user()->can('view_banner'))
                <li class="nav-item nav-dropdown">
                    <a class="nav-link" href="{{ route('dashboard.banners') }}"><i
                            class="icon-picture"></i>بنرها</a>
                </li>
            @endif
            @if(auth()->user()->can('edit_user_panels'))
                <li class="nav-item nav-dropdown">
                    <a class="nav-link" href="{{ route('dashboard.admin.user_list') }}"><i class="icon-people"></i>کاربران
                        پنل</a>
                </li>
            @endif
            @if(auth()->user()->can('view_transactions'))
                <li class="nav-item nav-dropdown">
                    <a class="nav-link" href="{{ route('dashboard.transactions') }}"><i class="icon-calculator"></i>تراکنش‌ها</a>
                </li>
            @endif
            @if(auth()->user()->can('view_promo_code'))
                <li class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-notebook"></i>کد تخفیف و کمپین</a>
                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard.campaigns') }}">
                                <i class="icon-list"></i>
                                کمپین‌ها
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard.promoCodes') }}">
                                <i class="icon-list"></i>
                                کدهای تخفیف
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                @if(auth()->user()->can('view_automation'))
                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-refresh"></i>اتوماسیون</a>
                        <ul class="nav-dropdown-items">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard.automation.metrics') }}">
                                    <i class="icon-list"></i>
                                    متریک‌ها
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard.automation.types') }}">
                                    <i class="icon-list"></i>
                                    گزارش وضعیت
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

        </ul>
    </nav>
</div>
