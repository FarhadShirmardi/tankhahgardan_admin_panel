<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="icon-speedometer"></i> میزکار </a>
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-basket"></i>فعال سازی کاربران</a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard.users.activation', ['step' => \App\Http\Controllers\Api\V1\Constants\UserActivationConstant::STATE_FIRST_STEP_INACTIVE]) }}">
                            <i class="icon-pie-chart"></i>
                            غیر فعال 24 ساعت گذشته
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard.users.activation', ['step' => \App\Http\Controllers\Api\V1\Constants\UserActivationConstant::STATE_SECOND_STEP_INACTIVE]) }}">
                            <i class="icon-pie-chart"></i>
                            غیر فعال یک هفته گذشته
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard.users.activation', ['step' => \App\Http\Controllers\Api\V1\Constants\UserActivationConstant::STATE_THIRD_STEP_INACTIVE]) }}">
                            <i class="icon-pie-chart"></i>
                            غیر فعال یک ماه گذشته
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</div>
