<main class="main">

    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">میزکار</li>

    {{--<li class="breadcrumb-item active">میزکار</li>--}}

    <!-- Breadcrumb Menu-->
        <li class="breadcrumb-menu d-md-down-none">
            <div class="btn-group" role="group" aria-label="Button group">
                <a class="btn" href="#"><i class="icon-speech"></i></a>
                <a class="btn" target="_blank" href="../"><i class="icon-graph"></i> &nbsp;مشاهده وب سایت</a>
            </div>
        </li>
    </ol>


    <div class="container-fluid">

        <div class="animated fadeIn">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>
                                @yield('title')
                            </h4>
                        </div>
                        <div class="card-body">
                            @include('dashboard.layouts.success_message')
                            @include('dashboard.layouts.error_message')
                            @yield('filter')
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <!-- /.conainer-fluid -->
</main>
