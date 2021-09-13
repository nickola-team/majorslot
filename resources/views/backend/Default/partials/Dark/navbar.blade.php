<header class="main-header">
    <!-- Logo -->
    <a class="logo" href="{{ url('/') }}">
        <span class="logo-mini"><b></b></span>
        <span class="logo-lg"><b></b></span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">@lang('app.toggle_navigation')</span>
        </a>
        <a href="#">
                <span>충전신청</span>
        </a>
        <div class="navbar-custom-menu">
            
            <ul class="nav navbar-nav">
                <li class="dropdown tasks-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-off text-aqua"></i></a>
                    <ul class="dropdown-menu">
                        <li class="header"><b>{{ auth()->user()->username }}</b></li>
                        <li>
                            <ul class="menu">

                                @if (config('session.driver') == 'database')
                                    <li><a href="{{ route($admurl.'.profile.sessions') }}"> @lang('app.active_sessions')</a></li>
                                @endif
                                <li><a href="{{ route($admurl.'.user.edit', auth()->user()->present()->id) }}"> @lang('app.my_profile')</a></li>
                                <li><a href="{{ route($admurl.'.auth.logout') }}"> @lang('app.logout')</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
            
        </div>
</nav>
</header>
<!-- Left side column. contains the logo and sidebar -->
