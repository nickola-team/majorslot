<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-primary" id="sidenav-main">
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
                        <span class="avatar avatar-sm rounded-circle" style="background-color:white;">
                        <img alt="Image placeholder" src="{{ asset('back/argon') }}/img/theme/profile.png">
                        </span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                    <div class=" dropdown-header noti-title">
                        <!-- <h6 class="text-overflow m-0">{{ __('Welcome!') }}</h6> -->
                    </div>
                    <a href="#" class="dropdown-item">
                        <i class="ni ni-single-02"></i>
                        <span>설정 및 정보</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                        <i class="ni ni-curved-next"></i>
                        <span>로그아웃</span>
                    </a>
                </div>
            </li>
        </ul>
        <!-- <hr class="my-0 sidebar-hr"> -->

        <!-- Collapse -->
        <div class="collapse navbar-collapse bg-primary" id="sidenav-collapse-main">
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

            <!-- Navigation -->
            <ul class="navbar-nav">
                <hr class="my-0 sidebar-hr">
                <li class="nav-item">
                    <a class="nav-link  text-white" href="#">
                        <i class="ni ni-chart-bar-32 text-white"></i>
                        <span class="nav-link-text text-white">대시보드</span>
                    </a>
                </li>
                <hr class="my-0 sidebar-hr">
                <li class="nav-item">
                    <a class="nav-link active" href="#navbar-agents" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-agents">
                        <i class="ni ni-single-02 text-white"></i>
                        <span class="nav-link-text text-white">에이전트</span>
                    </a>
                    <div class="collapse" id="navbar-agents">
                    <ul class="nav nav-sm flex-column">
                    @if (!auth()->user()->hasRole('manager'))
                            <li class="nav-item">
                                <a class="nav-link  text-white" href="#">
                                    <i class="far fa-circle text-white sub-i"></i>
                                    <span class="nav-link-text text-white">에이전트생성</span>
                                </a>
                            </li>
                    
                    @for ($role=auth()->user()->role_id-1;$role>=3;$role--)
                                <li class="nav-item">
                                    <a class="nav-link  text-white" href="#">
                                    <i class="far fa-circle text-white sub-i"></i>
                                        {{\VanguardLTE\Role::where('level',$role)->first()->description}}
                                    </a>
                                </li>
                    @endfor
                    @endif
                    <li class="nav-item">
                        <a class="nav-link  text-white" href="#">
                            <i class="far fa-circle text-white sub-i"></i>
                            롤링내역
                        </a>
                    </li>
                    </div>
                </li>
                <hr class="my-0 sidebar-hr">
                <li class="nav-item">
                    <a class="nav-link" href="#navbar-players" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-players">
                        <i class="ni ni-user-run text-white"></i>
                        <span class="nav-link-text text-white">플레이어</span>
                    </a>

                    <div class="collapse" id="navbar-players">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#">
                                <i class="far fa-circle text-white sub-i"></i>
                                    플레이어목록
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#">
                                <i class="far fa-circle text-white sub-i"></i>
                                    게임배팅내역
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#">
                                <i class="far fa-circle text-white sub-i"></i>
                                    지급내역
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @if (auth()->user()->hasRole('admin') )
                <hr class="my-0 sidebar-hr">
                <li class="nav-item">
                    <a class="nav-link" href="#navbar-games" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-players">
                        <i class="ni ni-controller text-white"></i>
                        <span class="nav-link-text text-white">게임설정</span>
                    </a>

                    <div class="collapse" id="navbar-games">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#">
                                <i class="far fa-circle text-white sub-i"></i>
                                    게임사
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#">
                                <i class="far fa-circle text-white sub-i"></i>
                                    게임
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#">
                                <i class="far fa-circle text-white sub-i"></i>
                                    환수금
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#">
                                <i class="far fa-circle text-white sub-i"></i>
                                    콜
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#">
                                <i class="far fa-circle text-white sub-i"></i>
                                    환수금내역
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif

                <hr class="my-0 sidebar-hr">
                <li class="nav-item">
                    <a class="nav-link" href="#navbar-deposit" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-players">
                        <i class="ni ni-money-coins text-white"></i>
                        <span class="nav-link-text text-white">충환전</span>
                    </a>

                    <div class="collapse" id="navbar-deposit">
                        <ul class="nav nav-sm flex-column">
                            @if (auth()->user()->isInoutPartner())
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#">
                                <i class="far fa-circle text-white sub-i"></i>
                                    충전
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#">
                                <i class="far fa-circle text-white sub-i"></i>
                                    환전
                                </a>
                            </li>
                            @else
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#">
                                <i class="far fa-circle text-white sub-i"></i>
                                    충전신청
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#">
                                <i class="far fa-circle text-white sub-i"></i>
                                    환전신청
                                </a>
                            </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#">
                                <i class="far fa-circle text-white sub-i"></i>
                                    충환전내역
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <hr class="my-0 sidebar-hr">
                <li class="nav-item">
                    <a class="nav-link" href="#navbar-report" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-players">
                        <i class="ni ni-chart-pie-35 text-white"></i>
                        <span class="nav-link-text text-white">보고서</span>
                    </a>

                    <div class="collapse" id="navbar-report">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#">
                                <i class="far fa-circle text-white sub-i"></i>
                                    일별벳윈
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#">
                                <i class="far fa-circle text-white sub-i"></i>
                                    월별벳윈
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#">
                                <i class="far fa-circle text-white sub-i"></i>
                                    게임별벳윈
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#">
                                <i class="far fa-circle text-white sub-i"></i>
                                    일별충환전
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @if (auth()->user()->isInoutPartner())
                <hr class="my-0 sidebar-hr">
                <li class="nav-item">
                    <a class="nav-link" href="#navbar-adjustment" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-players">
                        <i class="ni ni-collection text-white"></i>
                        <span class="nav-link-text text-white">정산</span>
                    </a>

                    <div class="collapse" id="navbar-adjustment">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#">
                                <i class="far fa-circle text-white sub-i"></i>
                                    벳윈정산
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif
                @if (auth()->user()->isInoutPartner())
                <hr class="my-0 sidebar-hr">
                <li class="nav-item">
                    <a class="nav-link" href="#navbar-customer" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-players">
                        <i class="ni ni-headphones text-white"></i>
                        <span class="nav-link-text text-white">고객센터</span>
                    </a>

                    <div class="collapse" id="navbar-customer">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#">
                                <i class="far fa-circle text-white sub-i"></i>
                                    공지
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#">
                                <i class="far fa-circle text-white sub-i"></i>
                                    쪽지
                                </a>
                            </li>
                            
                        </ul>
                    </div>
                </li>
                @endif

                @if (auth()->user()->hasRole('admin'))
                <hr class="my-0 sidebar-hr">
                <li class="nav-item">
                    <a class="nav-link" href="#navbar-system" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-players">
                        <i class="ni ni-settings text-white"></i>
                        <span class="nav-link-text text-white">시스템설정</span>
                    </a>

                    <div class="collapse" id="navbar-system">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#">
                                <i class="far fa-circle text-white sub-i"></i>
                                    도메인
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#">
                                <i class="far fa-circle text-white sub-i"></i>
                                    접속로그
                                </a>
                            </li>
                            
                        </ul>
                    </div>
                </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
