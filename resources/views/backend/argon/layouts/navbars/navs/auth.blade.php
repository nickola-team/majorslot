<!-- Top navbar -->
<nav class="navbar navbar-top navbar-expand border-bottom shadow {{ $navClass ?? 'navbar-dark' }}">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            
            <!-- Navbar links -->
            <ul class="navbar-nav align-items-center ml-md-auto">
                <li class="nav-item d-xl-none">
                <!-- Sidenav toggler -->
                    <div class="pr-3 sidenav-toggler sidenav-toggler-dark" data-action="sidenav-pin" data-target="#sidenav-main">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </div>
                </li>
                @if (!auth()->user()->hasRole('admin'))
                @if (auth()->user()->isInoutPartner())
                    <li class="nav-item d-none d-lg-block ml-lg-4">
                        <div class="nav-item-btn align-items-center">
                        <a class="nav-link btn btn-primary" href="{{argon_route('argon.joiners.list')}}">
                            신규가입<span id="join_newmark" style="color:red;">(0건)</span>
                        </a>
                        </div>
                    </li>
                    <li class="nav-item d-none d-lg-block ml-lg-4">
                        <div class="nav-item-btn align-items-center">
                        <a class="nav-link  btn btn-success" href="{{argon_route('argon.dw.addmanage')}}">
                            충전관리<span id="in_newmark" style="color:red;">(0건)</span>
                        </a>
                        </div>
                    </li>
                    <li class="nav-item d-none d-lg-block ml-lg-4">
                        <div class="nav-item-btn align-items-center">
                        <a class="nav-link  btn btn-warning" href="{{argon_route('argon.dw.outmanage')}}">
                            환전관리<span id="out_newmark" style="color:white;">(0건)</span>
                        </a>
                        </div>
                    </li>
                    <li class="nav-item d-none d-lg-block ml-lg-4">
                        <div class="nav-item-btn align-items-center">
                        <a class="nav-link text-dark btn btn-info" href="#" id="ratingOn">
                        <i class="ni ni-notification-70"><span class="text-red" id="rateText">{{auth()->user()->rating>0?'':'X'}}</span></i>
                        </a>
                        </div>
                    </li>
                @else
                <?php
                    $master = auth()->user()->referral;
                    while ($master!=null && !$master->isInoutPartner())
                    {
                        $master = $master->referral;
                    }
                ?>
                    <li class="nav-item d-none d-lg-block ml-lg-4">
                        <div class="nav-item-btn align-items-center">
                            텔레그램문의 {{$master->address}}
                        </div>
                    </li>
                    <li class="nav-item d-none d-lg-block ml-lg-4">
                        <div class="nav-item-btn align-items-center">
                        <a class="nav-link btn btn-success" href="{{argon_route('argon.dw.addrequest')}}">
                            충전신청
                        </a>
                        </div>
                    </li>
                    <li class="nav-item d-none d-lg-block ml-lg-4">
                        <div class="nav-item-btn align-items-center">
                        <a class="nav-link btn btn-warning" href="{{argon_route('argon.dw.outrequest')}}">
                            환전신청
                        </a>
                        </div>
                    </li>
                @endif
                <li class="nav-item d-none d-lg-block ml-lg-4">
                    <a class="nav-link text-dark" href="#">
                        현재 보유금 
                        @if (auth()->user()->hasRole('manager'))
                            {{number_format(auth()->user()->shop->balance)}}
                        @else
                            {{number_format(auth()->user()->balance)}}
                        @endif
                    </a>
                </li>
                <li class="nav-item d-none d-lg-block ml-lg-4">
                    <a class="nav-link text-dark" href="{{argon_route('argon.player.list', ['balance' => 1])}}">
                        하부 총 보유금 {{number_format(auth()->user()->childBalanceSum())}}
                    </a>
                </li>
                @endif
                
                <li class="nav-item dropdown">
                    <a class="nav-link" id="msgbutton" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-bell tada text-dark" style="font-size:16px"></i>
                        <span class="badges" id="unreadmsgcount">{{count($unreadmsgs)}}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right py-0 overflow-hidden">
                        <!-- Dropdown header -->
                        <div class="px-3 py-3">
                            <h6 class="text-sm text-muted m-0"><strong class="text-primary">{{count($unreadmsgs)}}</strong> 개의 새로운 쪽지가 있습니다</h6>
                        </div>
                        <!-- View all -->
                        <a href="{{argon_route('argon.msg.list', ['type'=>$msgtype])}}" class="dropdown-item text-center text-primary font-weight-bold py-3">모두보기</a>
                    </div>
            </ul>
            <ul class="navbar-nav align-items-center ml-auto ml-md-0">
                <li class="nav-item dropdown">
                    <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="media align-items-center">
                            <span class="avatar avatar-sm rounded-circle" style="background-color:white;">
                                <img alt="Image placeholder" src="{{ asset('back/argon') }}/img/theme/profile.png">
                            </span>
                            <div class="media-body ml-2 d-none d-lg-block">
                                <span class="mb-0 text-sm  font-weight-bold text-dark">{{ auth()->user()->username }}</span>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                        <div class=" dropdown-header noti-title">
                            <!-- <h6 class="text-overflow m-0">{{ __('Welcome!') }}</h6> -->
                        </div>
                        <a href="{{argon_route('argon.common.profile', ['id'=>auth()->user()->id])}}" class="dropdown-item">
                            <i class="ni ni-single-02"></i>
                            <span>설정 및 정보</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="{{argon_route('argon.auth.logout', ['force' => 1])}}" class="dropdown-item">
                            <i class="ni ni-curved-next"></i>
                            <span>모든 장치에서 로그아웃</span>
                        </a>
                        <a href="{{argon_route('argon.auth.logout')}}" class="dropdown-item">
                            <i class="ni ni-curved-next"></i>
                            <span>로그아웃</span>
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>