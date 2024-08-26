<div class="wallet-buttons-container">
    @if (Auth::check())
    <button class="btn btn-wallet withdraw" onclick="navClick('myinfo-popup')">마이 페이지</button>
    <button class="btn btn-wallet deposit ng-scope" onclick="logOut();">로그아웃</button>
    @else
    <button class="btn btn-wallet deposit" onclick="navClick('login-popup')">로그인</button>
    <button class="btn btn-wallet withdraw" onclick="navClick('register-popup')">회원가입</button>

    @endif
</div>
<div class="jackpot-container">
    <div class="container-jackpot">
        <p>&nbsp;</p>
        <div class="jackpot-odometer" style="height:39px">
        </div>
        <div class="currency-sign">
            <img src="/frontend/boss/V/won-sign.png" height="35">
        </div>
    </div>
</div>

<div class="game-buttons-container">
    <div class="game-buttons ng-scope live_m" onclick=@auth "navClick('casino-popup')" @else "navClick('login-popup')"
        @endif>
        <!-- <div class="title-container">
            <span ng-bind="mainButton.title" class="ng-binding">라이브 카지노</span>
        </div> -->
    </div>
    <div class="game-buttons ng-scope others_m" onclick=@auth "navClick('slots-popup')" @else "navClick('login-popup')"
        @endif>
        <!-- <div class="title-container">
            <span ng-bind="mainButton.title" class="ng-binding">슬롯게임</span>
        </div> -->
    </div>

    <!-- <div class="game-buttons ng-scope comming_m" onclick=
    @auth
                  {{$isCard = false}}
                    @foreach($categories AS $index=>$category)
                      @if ($category->type =='card')
                        @if ($category->view == 0)
                            "swal('지원하지 않는 게임입니다.');"
                        @elseif ($category->status == 0)
                            "swal('점검중입니다');"
                        @else
                        "holdemOpen('holdem-popup')" 
                        @endif
                        {{$isCard = true}}
                        @break
                      @endif
                    @endforeach
                    @if(!$isCard)
                      "swal('지원하지 않는 게임입니다.');"
                    @endif
                                     
                  @else
                    "navClick('login-popup')"
                  @endif>
    </div> -->
</div>


<div class="customercenter-container" >
    <ul class="list-inline list-unstyled">
        <li onclick=@if (!Auth::check()) "navClick('login-popup')"
            @else "navClick('msg-popup');setTab('notice-set','#msg-popup > div.ngdialog-content > div.ngdialog-customer-page.ngdialog-main-default-page.ng-scope > ul > li:nth-child(1)');"
            @endif>
            <img src="/frontend/boss/V/notice-icon.svg" class="filter-color-yellow" alt="">
            <p>공지사항</p>
        </li>
        <li onclick=@if (!Auth::check()) "navClick('login-popup')"
            @else "navClick('msg-popup');setTab('event-set','#msg-popup &gt; div.ngdialog-content &gt; div.ngdialog-customer-page.ngdialog-main-default-page.ng-scope &gt; ul &gt; li:nth-child(2)')"
            @endif>
            <img src="/frontend/boss/V/promo-icon.svg" class="filter-color-yellow" alt="">
            <p>이벤트</p>
        </li>
        <li onclick=@if (!Auth::check()) "navClick('login-popup')"
            @else "navClick('msg-popup');setTab('customer-set','#msg-popup &gt; div.ngdialog-content &gt; div.ngdialog-customer-page.ngdialog-main-default-page.ng-scope &gt; ul &gt; li:nth-child(3)');getCustomerPage();"
            @endif>
            <img src="/frontend/boss/V/customer-icon.svg" class="filter-color-yellow" alt="">
            <p>고객센터 <span class="badge dm-badge ng-binding cc-text">{{$unreadmsg}}</span>
            </p>
        </li>
        <li onclick=@if (!Auth::check()) "navClick('login-popup')" @else "autorequest();" @endif>
            <img src="/frontend/boss/V/dm-icon.svg" class="filter-color-yellow" alt="">
            <p>계좌요청
            </p>
        </li>
        <!-- <li onclick="location.href='/mobile/pages/view.php?view=desktop'"><img src="/common/images/customer/desktop-icon.svg" class="filter-color-yellow" alt=""><p>PC 버전</p></li> -->
    </ul>
</div>
<div class="clearfix"></div>
<br>
<br>
<br>
<br>
<br>
<br>
<br>

<div class="footer main">
    <button class="pull-left btn footer-btn btn-primary btn-bottom-left btn-lg" style="width: 100%;border: 3px solid #787164;padding:0;"
        onclick="navClick('page-popup');mydepositlist();setTab('deposit-set','#page-popup > div.ngdialog-content > div.ngdialog-wallet-page.ngdialog-main-default-page.ng-scope > ul > li:nth-child(1)')"><strong>입금
            신청</strong></button>
    <!-- @if (isset($telegram) && $telegram!='')
    <span class="btn btn-primary btn-live footer-btn live-chat" style="color: #ffffff; _opacity: 0;"
        onclick="window.open('https://t.me/{{$telegram??'Boss텔레'}}');"><img
            src="/frontend/boss/assets/images/telegram-logo.png" height="100%" border="0"
            alt=""><strong></strong></span>
    @endif -->
    <button class="pull-right btn footer-btn btn-primary btn-bottom-right btn-lg" style="width: 100%;margin-top:2px; border: 3px solid #787164;padding:0;"
        onclick="navClick('page-popup');setTab('withdraw-set','#page-popup &gt; div.ngdialog-content &gt; div.ngdialog-wallet-page.ngdialog-main-default-page.ng-scope &gt; ul &gt; li:nth-child(3)');mywithdrawlist();"><strong>출금
            신청</strong></button>
    <button class="pull-right btn footer-btn btn-primary btn-bottom-right btn-lg" style="width: 100%;margin-top:2px; border: 3px solid #787164;padding:0;"
        onclick="PointToMoney();"><strong>수수료 전환</strong></button>
</div>
