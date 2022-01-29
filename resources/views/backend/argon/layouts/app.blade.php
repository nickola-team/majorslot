<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('page-title')</title>
        <!-- Favicon -->
        <link href="{{ asset('back/argon') }}/img/brand/favicon.png" rel="icon" type="image/png">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@300&display=swap" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

        <!-- Extra details for Live View on GitHub Pages -->

        <!-- Icons -->
        <link href="{{ asset('back/argon') }}/vendor/nucleo/css/nucleo.css" rel="stylesheet">
        <link href="{{ asset('back/argon') }}/vendor/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
        <!-- Argon CSS -->
        <link type="text/css" href="{{ asset('back/argon') }}/css/argon.css?v=1.0.0" rel="stylesheet">
        @stack('css')
    </head>
    <body class="{{ $class ?? '' }}">
        @auth()
            <form id="logout-form" action="#" method="POST" style="display: none;">
                @csrf
            </form>
            @include('backend.argon.layouts.navbars.sidebar')
        @endauth
        
        <div class="main-content">
            @include('backend.argon.layouts.navbars.navbar')
            @auth()
                @include('backend.argon.layouts.headers.auth')
            @endauth
            @yield('content')
        </div>

        @include('backend.argon.layouts.footers.guest')

        <script src="{{ asset('back/argon') }}/vendor/jquery/dist/jquery.min.js"></script>
        <script src="{{ asset('back/argon') }}/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
        
        @stack('js')
 
        <!-- Argon JS -->
        
        <script src="{{ asset('back/argon') }}/js/argon.js?v=1.0.0"></script>
    </body>
</html>