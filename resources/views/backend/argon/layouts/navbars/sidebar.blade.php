<?php
    $manualjoin = 0;
    if (isset(auth()->user()->sessiondata()['manualjoin']))
    {
        $manualjoin = auth()->user()->sessiondata()['manualjoin'];
    }
?>

<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-blue sidenav-bg" id="sidenav-main">
    <div class="scrollbar-inner scroll-scrollx_visible">
        <div class="sidenav-header d-flex align-items-center">
            <a class="navbar-brand" href="#">
                <!-- <img src="{{ asset('back/argon') }}/img/brand/blue.png" class="navbar-brand-img" alt="..."> -->
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
                    <a class="nav-link  {{ (isset($parentSection) && $parentSection == 'dashboard') ? 'active' : '' }}" href="{{argon_route('argon.dashboard')}}">
                        <i class="ni ni-chart-bar-32 text-white"></i>
                        <span class="nav-link-text text-white">대시보드</span>
                    </a>
                </li>
                @if (auth()->user()->isInoutPartner())
                <hr class="my-0 sidebar-hr">
                <li class="nav-item">
                    <a class="nav-link {{ (isset($parentSection) && $parentSection == 'share') ? 'active' : '' }}" >
                        <i class="ni ni-single-02 text-white"></i>
                        <span class="nav-link-text text-white">받치기</span>
                    </a>
                    <div class="collapse show" id="navbar-share">
                    <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                        @if (auth()->user()->hasRole('comaster'))
                        <a class="nav-link  text-white" href="{{argon_route('argon.share.setting')}}">
                        @else
                        <a class="nav-link  text-white" href="{{argon_route('argon.share.index')}}">
                        @endif
                            <i class="far fa-circle text-white sub-i"></i>
                            받치기설정
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link  text-white" href="{{argon_route('argon.share.gamestat')}}">
                            <i class="far fa-circle text-white sub-i"></i>
                            받치기게임내역
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link  text-white" href="{{argon_route('argon.share.report.daily')}}">
                            <i class="far fa-circle text-white sub-i"></i>
                            받치기일별정산
                        </a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link  text-white" href="{{argon_route('argon.agent.transaction')}}">
                            <i class="far fa-circle text-white sub-i"></i>
                            받치기게임별정산
                        </a>
                    </li> -->
                    </div>
                </li>
                @endif
                <hr class="my-0 sidebar-hr">
                <li class="nav-item">
                    <a class="nav-link {{ (isset($parentSection) && $parentSection == 'agent') ? 'active' : '' }}" >
                        <i class="ni ni-single-02 text-white"></i>
                        <span class="nav-link-text text-white">파트너</span>
                    </a>
                    <div class="collapse show" id="navbar-agents">
                    <ul class="nav nav-sm flex-column">
                    @if (!auth()->user()->hasRole('manager'))
                        <li class="nav-item">
                            <a class="nav-link  text-white" href="{{argon_route('argon.agent.create')}}">
                                <i class="far fa-circle text-white sub-i"></i>
                                <span class="nav-link-text text-white">파트너생성</span>
                            </a>
                        </li>

                        @if (auth()->user()->isInOutPartner() && $manualjoin)
                        <li class="nav-item">
                            <a class="nav-link  text-white" href="{{argon_route('argon.agent.joinlist')}}">
                                <i class="far fa-circle text-white sub-i"></i>
                                <span class="nav-link-text text-white">신규가입리스트</span>
                            </a>
                        </li>
                        @endif
                        <li class="nav-item ">
                            <a class="nav-link text-white" href="{{argon_route('argon.agent.list')}}">
                                <i class="far fa-circle text-white sub-i"></i>
                                <span class="nav-link-text text-white">파트너목록</span>
                            </a>
                        </li>
                        @for ($role=auth()->user()->role_id-1;$role>=3;$role--)
                        <li class="nav-item ">
                            <a class="nav-link text-white" href="{{argon_route('argon.agent.list', ['role' => $role ])}}">
                                <i class="far fa-circle text-white sub-i"></i>
                                <span class="nav-link-text text-white">{{\VanguardLTE\Role::where('level',$role)->first()->description}}</span>
                            </a>
                        </li>
                        @endfor
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
                    <a class="nav-link {{ (isset($parentSection) && $parentSection == 'player') ? 'active' : '' }}" >
                        <i class="ni ni-user-run text-white"></i>
                        <span class="nav-link-text text-white">유저</span>
                    </a>

                    <div class="collapse show" id="navbar-players">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{argon_route('argon.player.create')}}">
                                <i class="far fa-circle text-white sub-i"></i>
                                    유저생성
                                </a>
                            </li>
                            @if (auth()->user()->isInOutPartner() && $manualjoin)
                            <li class="nav-item">
                                <a class="nav-link  text-white" href="{{argon_route('argon.player.joinlist')}}">
                                    <i class="far fa-circle text-white sub-i"></i>
                                    <span class="nav-link-text text-white">신규가입유저</span>
                                </a>
                            </li>
                            @endif
                            @if (auth()->user()->isInoutPartner())
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{argon_route('argon.player.vlist')}}">
                                <i class="far fa-circle text-white sub-i"></i>
                                    개인유저목록
                                </a>
                            </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{argon_route('argon.player.list')}}">
                                <i class="far fa-circle text-white sub-i"></i>
                                    유저목록
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{argon_route('argon.player.list',['online' => 1])}}">
                                <i class="far fa-circle text-white sub-i"></i>
                                    접속중유저
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

                            @if (auth()->user()->isInOutPartner() )

                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{argon_route('argon.player.gamepending')}}">
                                <i class="far fa-circle text-white sub-i"></i>
                                    미결중 게임내역
                                </a>
                            </li>
                            
                            @endif

                        </ul>
                    </div>
                </li>
                @if (auth()->user()->isInOutPartner() )
                
                <hr class="my-0 sidebar-hr">
                <li class="nav-item">
                    <a class="nav-link {{ (isset($parentSection) && $parentSection == 'game') ? 'active' : '' }}">
                        <i class="ni ni-controller text-white"></i>
                        <span class="nav-link-text text-white">게임설정</span>
                    </a>

                    <div class="collapse show" id="navbar-games">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{argon_route('argon.game.domain')}}">
                                <i class="far fa-circle text-white sub-i"></i>
                                    도메인별 게임관리
                                </a>
                            </li>
                            @if (auth()->user()->hasRole('admin') )
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{argon_route('argon.game.category')}}">
                                <i class="far fa-circle text-white sub-i"></i>
                                    파트너별 게임관리
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{argon_route('argon.game.bank')}}">
                                <i class="far fa-circle text-white sub-i"></i>
                                    환수금
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{argon_route('argon.game.transaction')}}">
                                <i class="far fa-circle text-white sub-i"></i>
                                    환수금내역
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{argon_route('argon.happyhour.list')}}">
                                <i class="far fa-circle text-white sub-i"></i>
                                    콜
                                </a>
                            </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{argon_route('argon.game.betlimit')}}">
                                <i class="far fa-circle text-white sub-i"></i>
                                    에볼루션 배팅한도
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{argon_route('argon.game.gactable')}}">
                                <i class="far fa-circle text-white sub-i"></i>
                                    에볼루션 테이블설정
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{argon_route('argon.game.missrole')}}">
                                <i class="far fa-circle text-white sub-i"></i>
                                    공배팅
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif

                <hr class="my-0 sidebar-hr">
                <li class="nav-item">
                    <a class="nav-link  {{(isset($parentSection) && $parentSection == 'dw') ? 'active' : '' }}">
                        <i class="ni ni-money-coins text-white"></i>
                        <span class="nav-link-text text-white">충환전</span>
                    </a>

                    <div class="collapse show" id="navbar-deposit">
                        <ul class="nav nav-sm flex-column">
                            @if (auth()->user()->isInoutPartner())
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{argon_route('argon.dw.addmanage')}}">
                                <i class="far fa-circle text-white sub-i"></i>
                                    충전관리
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{argon_route('argon.dw.outmanage')}}">
                                <i class="far fa-circle text-white sub-i"></i>
                                    환전관리
                                </a>
                            </li>
                            @else
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{argon_route('argon.dw.dealconvert')}}">
                                <i class="far fa-circle text-white sub-i"></i>
                                    롤링전환
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{argon_route('argon.dw.addrequest')}}">
                                <i class="far fa-circle text-white sub-i"></i>
                                    충전신청
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{argon_route('argon.dw.outrequest')}}">
                                <i class="far fa-circle text-white sub-i"></i>
                                    환전신청
                                </a>
                            </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{argon_route('argon.dw.history')}}">
                                <i class="far fa-circle text-white sub-i"></i>
                                    충환전내역
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <hr class="my-0 sidebar-hr">
                <li class="nav-item">
                    <a class="nav-link {{(isset($parentSection) && $parentSection == 'report') ? 'active' : '' }}">
                        <i class="ni ni-chart-pie-35 text-white"></i>
                        <span class="nav-link-text text-white">보고서</span>
                    </a>

                    <div class="collapse show" id="navbar-report">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{argon_route('argon.report.daily.dw')}}">
                                <i class="far fa-circle text-white sub-i"></i>
                                    일별정산
                                </a>
                            </li>
                            <!-- <li class="nav-item">
                                <a class="nav-link text-white" href="{{argon_route('argon.report.daily')}}">
                                <i class="far fa-circle text-white sub-i"></i>
                                    일별벳윈
                                </a>
                            </li> -->
                            <!-- <li class="nav-item">
                                <a class="nav-link text-white" href="{{argon_route('argon.report.monthly')}}">
                                <i class="far fa-circle text-white sub-i"></i>
                                    월별벳윈
                                </a>
                            </li> -->
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{argon_route('argon.report.game')}}">
                                <i class="far fa-circle text-white sub-i"></i>
                                    게임별벳윈
                                </a>
                            </li>
                            
                        </ul>
                    </div>
                </li>
                <!-- @if (auth()->user()->isInoutPartner())
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
                @endif -->
                
                <hr class="my-0 sidebar-hr">
                <li class="nav-item">
                    <a class="nav-link  {{(isset($parentSection) && $parentSection == 'customer') ? 'active' : '' }}">
                        <i class="ni ni-headphones text-white"></i>
                        <span class="nav-link-text text-white">고객센터</span>
                    </a>
                    
                    <div class="collapse show" id="navbar-customer">
                        <ul class="nav nav-sm flex-column">
                            @if (auth()->user()->isInoutPartner())
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{argon_route('argon.notice.list')}}">
                                <i class="far fa-circle text-white sub-i"></i>
                                    공지
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{argon_route('argon.msgtemp.list')}}">
                                <i class="far fa-circle text-white sub-i"></i>
                                    템플릿
                                </a>
                            </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{argon_route('argon.msg.list', ['type'=>1])}}">
                                <i class="far fa-circle text-white sub-i"></i>
                                    계좌문의
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{argon_route('argon.msg.list', ['type'=>0])}}">
                                <i class="far fa-circle text-white sub-i"></i>
                                    일반쪽지
                                </a>
                            </li>

                            
                            
                        </ul>
                    </div>
                </li>
                
                @if (auth()->user()->isInoutPartner())
                <hr class="my-0 sidebar-hr">
                <li class="nav-item">
                    <a class="nav-link  {{(isset($parentSection) && $parentSection == 'setting') ? 'active' : '' }}">
                        <i class="ni ni-settings text-white"></i>
                        <span class="nav-link-text text-white">시스템설정</span>
                    </a>

                    <div class="collapse show" id="navbar-system">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{argon_route('argon.website.list')}}">
                                <i class="far fa-circle text-white sub-i"></i>
                                    도메인
                                </a>
                            </li>
                            @if (auth()->user()->hasRole('admin'))
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{argon_route('argon.activity.index')}}">
                                <i class="far fa-circle text-white sub-i"></i>
                                    접속로그
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{argon_route('argon.system.statistics')}}">
                                <i class="far fa-circle text-white sub-i"></i>
                                    시스템상황
                                </a>
                            </li>
                            @endif
                            
                        </ul>
                    </div>
                </li>
                @endif
            </ul>
                
            </div>
        </div>
    </div>
</nav>