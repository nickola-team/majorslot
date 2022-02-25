<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-blue" id="sidenav-main">
    <div class="scrollbar-inner scroll-scrollx_visible">
        <div class="sidenav-header d-flex align-items-center">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('back/argon') }}/img/brand/blue.png" class="navbar-brand-img" alt="...">
            </a>
            <div class="ml-auto">
                <!-- Sidenav toggler -->
                <div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-unpin" data-target="#sidenav-main">
                    <div class="sidenav-toggler-inner">
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="navbar-inner">
            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="sidenav-collapse-main">
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
                            <a class="nav-link  text-white" href="{{argon_route('argon.agent.create')}}">
                                <i class="far fa-circle text-white sub-i"></i>
                                <span class="nav-link-text text-white">에이전트생성</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link  text-white" href="{{argon_route('argon.agent.list')}}">
                                <i class="far fa-circle text-white sub-i"></i>
                                <span class="nav-link-text text-white">에이전트목록</span>
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link  text-white" href="{{argon_route('argon.agent.transaction')}}">
                            <i class="far fa-circle text-white sub-i"></i>
                            지급내역
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link  text-white" href="{{argon_route('argon.agent.dealstat')}}">
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
                                    플레이어생성
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{argon_route('argon.player.list')}}">
                                <i class="far fa-circle text-white sub-i"></i>
                                    플레이어목록
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{argon_route('argon.player.transaction')}}">
                                <i class="far fa-circle text-white sub-i"></i>
                                    지급내역
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{argon_route('argon.player.gamehistory')}}">
                                <i class="far fa-circle text-white sub-i"></i>
                                    게임내역
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
    </div>
</nav>