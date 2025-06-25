<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale() ?? '') }}">
    <head>
        <meta charset="utf-8" />
        <title>{{ $title ?? ""}} : {{config('app.name')}}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('assets/images/favicon.ico')}}">
        <!-- App css -->
        <link href="{{asset('assets/css/app-saas.min.css')}}" rel="stylesheet" type="text/css" id="app-style" />
        <!-- Icons css -->
        <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- PLUGINS STYLES-->
	    @yield('plugin-css')
        <!-- PAGE STYLES-->
        @yield('page-css')
    </head>
    <body>
        {{ $slot }}
        <!-- end page -->
        <footer class="footer footer-alt">&copy; {{now()->format('Y')}} {{config('app.name')}}</footer>
        <!-- Theme Config Js -->
        <script src="{{asset('assets/js/hyper-config.min.js')}}"></script>
        <!-- Vendor js -->
        <script src="{{asset('assets/js/vendor.min.js')}}"></script>
        <!-- App js -->
        <script src="{{asset('assets/js/app.min.js')}}"></script>
        <!-- PLUGINS-->
        @yield('plugin-scripts')
        <script src="{{asset('assets/js/plugin/jquery.notification.min.js')}}"></script>
        <!-- PAGE SCRIPTS-->
        @yield('page-scripts')
    </body>
</html>
