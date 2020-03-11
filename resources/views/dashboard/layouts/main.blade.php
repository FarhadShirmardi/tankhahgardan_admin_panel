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
            @yield('content')
        </div>


    </div>
    <!-- /.conainer-fluid -->
</main>
