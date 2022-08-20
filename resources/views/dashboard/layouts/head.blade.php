<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>پنل مدیریت تنخواه گردان</title>

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('dashboard/css/font-awesome.css') }}"/>
    <link rel="stylesheet" href="{{ asset('dashboard/css/simple-line-icons.css') }}"/>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo.png') }}">

    {{--    <link rel="stylesheet" href="{{ asset('dashboard/css/selectr.css') }}">--}}
    <link rel="stylesheet" href="{{ asset('dashboard/css/jstree.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('dashboard/css/select2.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('dashboard/css/cropper.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('dashboard/css/dropzone.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('dashboard/css/persian-datepicker.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('dashboard/css/jquery.toast.min.css') }}"/>

    <!-- Main styles for this application -->
    <link href="{{ asset('dashboard/css/style.css') }}" rel="stylesheet">
    <script src="{{ asset("js/jquery.min.js") }}"></script>
    <script src="{{ asset('js/dropzone.min.js') }}"></script>

    @yield('extra_css')
    @livewireStyles
</head>
