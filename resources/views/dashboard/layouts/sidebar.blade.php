<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard.home') }}"><i class="icon-speedometer"></i> میزکار </a>
                <a class="nav-link" href="{{ route('dashboard.products.index') }}"><i class="icon-pie-chart"></i>
                    محصولات </a>
                <a class="nav-link" href="{{ route('dashboard.variations.report', ['product_variation_status' => -1]) }}"><i class="icon-pie-chart"></i>
                    گزارش انبار </a>
                <a class="nav-link" href="{{ route('dashboard.shippments.index') }}">
                    <i class="icon-pie-chart"></i>
                    مرسوله‌ها
                </a>
                <a class="nav-link" href="{{ route('dashboard.warehouses.index') }}">
                    <i class="icon-pie-chart"></i>
                    انبار
                </a>
            </li>
        </ul>
    </nav>
</div>
