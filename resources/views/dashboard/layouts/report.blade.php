<!--
 * CoreUI - Open Source Bootstrap Admin Template
 * @version v1.0.0-alpha.6
 * @link http://coreui.io
 * Copyright (c) 2017 creativeLabs Łukasz Holeczek
 * @license MIT
 -->
<!DOCTYPE html>
<html lang="fa" dir="rtl">

@include('dashboard.layouts.head')

<!-- BODY options, add following classes to body to change options

// Header options
1. '.header-fixed'					- Fixed Header

// Sidebar options
1. '.sidebar-fixed'					- Fixed Sidebar
2. '.sidebar-hidden'				- Hidden Sidebar
3. '.sidebar-off-canvas'		- Off Canvas Sidebar
4. '.sidebar-minimized'			- Minimized Sidebar (Only icons)
5. '.sidebar-compact'			  - Compact Sidebar

// Aside options
1. '.aside-menu-fixed'			- Fixed Aside Menu
2. '.aside-menu-hidden'			- Hidden Aside Menu
3. '.aside-menu-off-canvas'	- Off Canvas Aside Menu

// Breadcrumb options
1. '.breadcrumb-fixed'			- Fixed Breadcrumb

// Footer options
1. '.footer-fixed'					- Fixed footer

-->

<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">


@include('dashboard.layouts.header')


<div class="app-body">

    @include('dashboard.layouts.sidebar')

    {{-- Gets content section--}}
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
                                @yield('title')
                            </div>
                            <div class="card-body">
                                @include('dashboard.layouts.success_message')
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


    @include('dashboard.layouts.aside_menu')

</div>


@include('dashboard.layouts.footer')

@include('dashboard.layouts.scripts')

@yield('chart')

@include('dashboard.layouts.datepickers')

</body>

</html>
