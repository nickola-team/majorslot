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

        <!-- Extra details for Live View on GitHub Pages -->

        <!-- Icons -->
        <link href="{{ asset('back/argon') }}/vendor/nucleo/css/nucleo.css" rel="stylesheet">
        <link href="{{ asset('back/argon') }}/vendor/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
        <!-- Argon CSS -->
        <link type="text/css" href="{{ asset('back/argon') }}/css/argon.css?v=1.0.0" rel="stylesheet">
        <link type="text/css" href="{{ asset('back/argon') }}/css/custom.css?v=1.0.0" rel="stylesheet">
        <!-- Font Awesome -->
        <link href="{{ asset('back/argon') }}/vendor/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">

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

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
        <script src="{{ asset('back/argon') }}/vendor/js-cookie/js.cookie.js"></script>
        <script src="{{ asset('back/argon') }}/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
        <script src="{{ asset('back/argon') }}/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
        <script src="{{ asset('back/argon') }}/vendor/lavalamp/js/jquery.lavalamp.min.js"></script>
        <!-- Optional JS -->
        <script src="{{ asset('back/argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
        <script src="{{ asset('back/argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
        
        @stack('js')
 
        <!-- Argon JS -->
        
        <script src="{{ asset('back/argon') }}/js/argon.js?v=1.0.0"></script>
    </body>
</html>