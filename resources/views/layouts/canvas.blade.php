<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale() ?? '') }}">
    <head>
        <meta charset="utf-8" />
        <title>{{ $title ?? ""}} : {{config('app.name')}}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('assets/images/favicon.ico')}}">
        @yield('page-css')
    </head>
    <body>
        {{ $slot }}
        @yield('page-scripts')
    </body>
</html>
