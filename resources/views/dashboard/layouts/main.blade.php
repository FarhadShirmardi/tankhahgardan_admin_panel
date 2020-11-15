<main class="main">


    <div class="container-fluid pt-4">

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
