<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="icon-speedometer"></i> میزکار </a>
            </li>
            @if(auth()->user()->hasRole('Admin'))
                <li class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-basket"></i>فعال سازی کاربران</a>
                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a class="nav-link"
                               href="{{ route('dashboard.users.activation', ['step' => \App\Constants\UserActivationConstant::STATE_FIRST_STEP_INACTIVE]) }}">
                                <i class="icon-pie-chart"></i>
                                غیر فعال 24 ساعت گذشته
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               href="{{ route('dashboard.users.activation', ['step' => \App\Constants\UserActivationConstant::STATE_SECOND_STEP_INACTIVE]) }}">
                                <i class="icon-pie-chart"></i>
                                غیر فعال یک هفته گذشته
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               href="{{ route('dashboard.users.activation', ['step' => \App\Constants\UserActivationConstant::STATE_THIRD_STEP_INACTIVE]) }}">
                                <i class="icon-pie-chart"></i>
                                غیر فعال یک ماه گذشته
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
            @if(auth()->user()->can('time_separation') or auth()->user()->can('day_separation') or auth()->user()->can('range_separation'))
                <li class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-pie-chart"></i>گزارش ثبت نام</a>
                    <ul class="nav-dropdown-items">
                        @can('time_separation')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard.report.timeSeparation') }}">
                                    <i class="icon-chart"></i>
                                    تفکیک ساعت
                                </a>
                            </li>
                        @endcan
                        @can('day_separation')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard.report.daySeparation') }}">
                                    <i class="icon-chart"></i>
                                    تفکیک روز هفته
                                </a>
                            </li>
                        @endcan
                        @can('range_separation')
                            <li class="nav-item">
                                <a class="nav-link"
                                   href="{{ route('dashboard.report.rangeSeparation') }}">
                                    <i class="icon-chart"></i>
                                    تفکیک روز
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endif
            @if(auth()->user()->can('all_user_activity'))
                <li class="nav-item nav-dropdown">
                    <a class="nav-link" href="{{ route('dashboard.report.allUsersActivity') }}"><i
                            class="icon-pie-chart"></i>گزارش وضعیت کاربران</a>
                </li>
            @endif
            @if(auth()->user()->can('all_project_activity'))
                <li class="nav-item nav-dropdown">
                    <a class="nav-link" href="{{ route('dashboard.report.allProjectsActivity') }}"><i
                            class="icon-pie-chart"></i>گزارش وضعیت پروژه</a>
                </li>
            @endif
            <li class="nav-item nav-dropdown">
                <a class="nav-link" href="{{ route('dashboard.feedbacks') }}"><i class="icon-user"></i>بازخوردها</a>
            </li>
            @if(auth()->user()->hasRole('Admin'))
                <li class="nav-item nav-dropdown">
                    <a class="nav-link" href="{{ route('dashboard.admin.user_list') }}"><i class="icon-people"></i>کاربران پنل</a>
                </li>
            @endif
        </ul>
    </nav>
</div>
