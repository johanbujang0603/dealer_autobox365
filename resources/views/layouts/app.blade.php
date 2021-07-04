<!DOCTYPE html>
<html lang="{{ \App::getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> {{$page_title}} - Autobox365 </title>
    <meta name="LogedUserId" content="{{ Auth::user()->id }}">
    <!-- Global stylesheets -->
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link href="{{ asset('new_assets/css/app.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('new_assets/icons/icomoon/styles.min.css') }}" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    @yield('styles')
    @yield('scripts')

</head>

<body class="app">
    @include('layouts.common.mobile_menu')
    
    <!-- nclude('layouts.common.navbar') -->

    <div class="flex">
        <!-- BEGIN: Side Menu -->

        @include('layouts.common.sidebar')
        
        <!-- END: Side Menu -->

        <!-- BEGIN: Page content -->
        <div class="content">
            <div class="top-bar">
                @yield('page-title')
                @include('layouts.common.topbar')
            </div>
            @yield('page_header')
            @include('common.message')
            @yield('page_content')
            <!-- ude('common.message')
            ld('page_content') --> 

        </div>
        <!-- END: Page content -->
    </div>
    <div class="footer text-right">
        <p class="text-white mt-5 mr-5">2020 © <a href="https://autobox365.com">autobox365</a> - Dealership Management System</p>
    </div>
    <!-- /page content -->
    <script src="{{ asset('new_assets/js/app.js') }}"></script>
    <script type="text/javascript"
        src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_API_KEY') }}&libraries=places&language={{ app()->getLocale() }}">
    </script>
    <script>
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' } });
        
        $(".search-toggle").on('click', function() {
            $(".search-box-popup").toggle();
        });
    </script>
    @yield('scripts_after')
</body>

</html>
