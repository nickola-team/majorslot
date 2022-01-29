<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Brand -->
        <a class="navbar-brand pt-0" href="#">
            <img src="{{ asset('back/argon') }}/img/brand/blue.png" class="navbar-brand-img" alt="...">
        </a>
        <!-- User -->
        <ul class="nav align-items-center d-md-none">
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media align-items-center">
                        <span class="avatar avatar-sm rounded-circle">
                        <img alt="Image placeholder" src="{{ asset('back/argon') }}/img/theme/team-1-800x800.jpg">
                        </span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                    <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0">{{ __('Welcome!') }}</h6>
                    </div>
                    <a href="#" class="dropdown-item">
                        <i class="ni ni-single-02"></i>
                        <span>{{ __('My profile') }}</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                        <i class="ni ni-user-run"></i>
                        <span>{{ __('Logout') }}</span>
                    </a>
                </div>
            </li>
        </ul>
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
            <!-- Collapse header -->
            <div class="navbar-collapse-header d-md-none">
                <div class="row">
                    <div class="col-6 collapse-brand">
                        <a href="#">
                            <img src="{{ asset('back/argon') }}/img/brand/blue.png">
                        </a>
                    </div>
                    <div class="col-6 collapse-close">
                        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Form -->
            <form class="mt-4 mb-3 d-md-none">
                <div class="input-group input-group-rounded input-group-merge">
                    <input type="search" class="form-control form-control-rounded form-control-prepended" placeholder="{{ __('Search') }}" aria-label="Search">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <span class="fa fa-search"></span>
                        </div>
                    </div>
                </div>
            </form>
            <!-- Navigation -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#navbar-examples1" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-examples1">
                        <i class="ni ni-single-02 text-primary"></i>
                        <span class="nav-link-text text-blue">ROLE_ID</span>
                    </a>

                    <div class="collapse show" id="navbar-examples1">
                        <ul class="nav nav-sm flex-column">
                      
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    {{ __('Transfer History') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#navbar-examples2" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-examples2">
                        <i class="ni ni-user-run text-primary"></i>
                        <span class="nav-link-text text-blue">{{ __('Players') }}</span>
                    </a>

                    <div class="collapse show" id="navbar-examples2">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    {{ __('Player List') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    {{ __('Game History') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    {{ __('Balance Transfer') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#navbar-examples3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-examples3">
                    <i class="ni ni-trophy text-primary"></i>
                        <span class="nav-link-text text-blue">{{ __('Summary') }}</span>
                    </a>

                    <div class="collapse show" id="navbar-examples3">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    {{ __('Daily Summary') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    {{ __('Rate Summary') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="ni ni-books text-primary"></i> <span class="nav-link-text text-blue">{{ __('API Doc') }}</span>
                    </a>
                </li>

                
                
            </ul>
        </div>
    </div>
</nav>
