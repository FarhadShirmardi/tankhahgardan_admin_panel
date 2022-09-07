<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            @if(auth()->user()->can('view_registration'))
                <li x-data="{dropdownMenu: false}">
                    <div class="row nav-clickable">
                        <span class="nav-link dropdown-toggle" @click="dropdownMenu = ! dropdownMenu">
                            <i class="icon-pie-chart"></i>
                            گزارش ثبت نام
                        </span>
                    </div>
                    <ul x-show="dropdownMenu" class="pr-2">
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
                <li x-data="{dropdownMenu: true}">
                    <div class="row nav-clickable">
                        <span class="nav-link dropdown-toggle" @click="dropdownMenu = ! dropdownMenu">
                            <i class="icon-chart"></i>
                            وضعیت کاربران
                        </span>
                    </div>
                    <ul x-show="dropdownMenu" class="pr-2">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard.report.allUsersActivity') }}"><i
                                    class="icon-list"></i>گزارش وضعیت کاربران</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard.report.userActivityCountChart') }}"><i
                                    class="icon-chart"></i>نمودار وضعیت کاربران</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard.report.userActivityRangeChart') }}"><i
                                    class="icon-chart"></i>نمودار وضعیت تراکنش در بازه</a>
                        </li>
                    </ul>
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
            @if(auth()->user()->can('view_notification') or auth()->user()->can('view_banner'))
                <li x-data="{dropdownMenu: false}">
                    <div class="row nav-clickable">
                        <span class="nav-link dropdown-toggle" @click="dropdownMenu = ! dropdownMenu">
                            <i class="icon-menu"></i>
                            اعلان و بنر
                        </span>
                    </div>
                    <ul x-show="dropdownMenu" class="pr-2">
                        @if(auth()->user()->can('view_notification'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard.announcements') }}"><i
                                        class="icon-bell"></i>اعلان‌ها</a>
                            </li>
                        @endif
                        @if(auth()->user()->can('view_banner'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard.banners') }}"><i
                                        class="icon-picture"></i>بنرها</a>
                            </li>
                        @endif
                    </ul>
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
                <li x-data="{dropdownMenu: false}">
                    <div class="row nav-clickable">
                        <span class="nav-link dropdown-toggle" @click="dropdownMenu = ! dropdownMenu">
                            <i class="icon-notebook"></i>
                            کد تخفیف و کمپین
                        </span>
                    </div>
                    <ul x-show="dropdownMenu" class="pr-2">
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
                <li x-data="{dropdownMenu: false}">
                    <div class="row nav-clickable">
                        <span class="nav-link dropdown-toggle" @click="dropdownMenu = ! dropdownMenu">
                            <i class="icon-refresh"></i>
                            اتوماسیون
                        </span>
                    </div>
                    <ul x-show="dropdownMenu" class="pr-2">
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
            @if(auth()->user()->can('view_premium_report') or
                auth()->user()->can('view_extend_user_report') or
                auth()->user()->can('view_unverified_user'))
                <li x-data="{dropdownMenu: false}">
                    <div class="row nav-clickable">
                        <span class="nav-link dropdown-toggle" @click="dropdownMenu = ! dropdownMenu">
                            <i class="icon-wallet"></i>
                            گزارش‌های پولی
                        </span>
                    </div>
                    <ul x-show="dropdownMenu" class="pr-2">
                        @if(auth()->user()->can('view_premium_report'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard.report.premiumReport') }}"><i
                                        class="icon-list"></i> گزارش معیارهای سنجش پولی</a>
                            </li>
                        @endif
                        @if(auth()->user()->can('view_extend_user_report'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard.report.userExtendReport') }}"><i
                                        class="icon-list"></i> گزارش کاربران تمدید نکرده</a>
                            </li>
                        @endif
                        @if(auth()->user()->can('view_unverified_user'))
                            <li class="nav-item">
                                <a class="nav-link"
                                   href="{{ route('dashboard.report.unverifiedPaymentReport') }}"><i
                                        class="icon-list"></i>گزارش کاربرانی که پول داده‌اند و طرحشان فعال نشده است</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            <li class="nav-item nav-dropdown">
                <a class="nav-link" href="{{ route('dashboard.downloadCenter') }}"><i
                        class="icon-cloud-download"></i>مرکز دانلود</a>
            </li>
            @if(auth()->user()->can('view_log_center'))
                <li class="nav-item nav-dropdown">
                    <a class="nav-link" href="{{ route('dashboard.log_centers.index') }}"><i
                            class="icon-notebook"></i>مرکز لاگ</a>
                </li>
            @endif
        </ul>
    </nav>
</div>
