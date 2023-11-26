<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('assets/css/fontawesome-all.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">

		<link rel="icon" href="{{ url('favicon.ico') }}">

        @if(isset($_COOKIE['theme']) && $_COOKIE['theme'] == 'dark')
            <link rel="stylesheet" href="{{ asset('assets/css/theme-dark.css') }}">
        @endif

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
    </head>

    <body class="is-preload admin">
        <div id="wrapper">
            <!-- Page Content -->
            <div id="main">
                <div class="inner">
                    {{ $slot }}
                </div>
            </div>

            @if($isDashboard)
                @include('layouts.navigation-dashboard')
            @elseif($isProfile)
                @include('layouts.navigation-profile')
            @endif
        </div>

        <!-- Scripts -->
        <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/js/browser.min.js') }}"></script>
        <script src="{{ asset('assets/js/breakpoints.min.js') }}"></script>
        <script src="{{ asset('assets/js/util.js') }}"></script>
        <script src="{{ asset('assets/js/main.js') }}"></script>
        <script src="{{ asset('assets/js/general.js') }}"></script>
        <!-- Charts -->
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    </body>
</html>
