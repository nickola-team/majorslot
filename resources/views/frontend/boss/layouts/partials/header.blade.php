<style type="text/css">
    .header-bottom .links>ul>li p:nth-child(2) {
        color: #999999;
        font-size: 16px;
        font-weight: normal;
    }

    .header-bottom .links>ul>li:hover p:nth-child(2) {
        color: #c1ad8b !important;
    }

    .header-bottom .links>ul>li p:nth-child(3) {
        color: #c9ae7c;
        font-weight: normal;
    }

    .header-bottom .links>ul>li:hover p:nth-child(3) {
        color: #c1ad8b !important;
    }
</style>
<navigation-page>
        <div class="navigation-page">
          <div class="header-top">
            <div class="logo-container" style="cursor: pointer;z-index: 999999;" onclick="window.location.href='/';">
              <div class="content">
                <img src="/frontend/{{$logo??'boss'}}/LOGO.png?0.1" width="260px">
              </div>
            </div>

            <div class="nav-container">
              <div class="main-container">
                @if (Auth::check())
                <form class="loggedIn pull-right ng-pristine ng-valid" ng-show="loggedIn">
                  <div style="margin-bottom: 5px">
                    <strong class="guest_name">{{auth()->user()->username}}</strong>
                    <strong>
                      <i role="button" onclick="navClick('msg-popup');setTab('customer-set','#msg-popup &gt; div.ngdialog-content &gt; div.ngdialog-customer-page.ngdialog-main-default-page.ng-scope &gt; ul &gt; li:nth-child(3)');getCustomerPage();" class="guest-dm fa fa-envelope">
                        <span ng-bind="UnreadDM" class="ng-binding msg-text" id="_top_msg">{{$unreadmsg}}</span>
                      </i>
                    </strong>
                    <strong>
                      <i role="button" onclick="PointToMoney();" class="guest-dm fa fa-refresh" style="background: #cca400;width:120px">
                        <span ng-bind="UnreadDM" class="ng-binding msg-text" id="_top_point">{{number_format(auth()->user()->deal_balance,0)}}</span> P
                      </i>
                    </strong>
                  </div>
                  <div class="balance-container">
                    <div class="reload-balance"></div>
                    <div class="guest-balance-container" onclick="navClick('page-popup');setTab('main-set','#page-popup &gt; div.ngdialog-content &gt; div.ngdialog-wallet-page.ngdialog-main-default-page.ng-scope &gt; ul &gt; li:nth-child(1)')">
                      <strong class="text-uppercase ng-scope">보유머니</strong>
                      <strong class="text-uppercase font-white pull-right ng-binding ng-hide">0</strong>
                      <strong class="text-uppercase font-white pull-right ng-binding ">
                        <span class="mbalance" id="_top_money">{{number_format(auth()->user()->balance,0)}}</span> 원 </strong>
                    </div>
                  </div>
                  <button type="button" class="btn btn-signup click-disable" onclick="navClick('page-popup');">마이 페이지</button>
                  <button type="button" class="btn btn-login ng-scope" ng-controller="LogoutController" onclick="logOut();">로그아웃</button>
                </form>
                @else
                
                <form class="ng-pristine ng-valid ng-scope">
                  <input type="text" placeholder="아이디" class="form-control guest-input ng-pristine ng-untouched ng-valid" ng-model="loginForm.nickname" id="sID2">
                  <input type="password" placeholder="비밀번호" class="form-control guest-input ng-pristine ng-untouched ng-valid" autocomplete="new-password" ng-model="loginForm.password" id="sPASS2">
                  <button type="button" class="btn btn-login sbmt-login2">로그인</button>
			
                  <button type="button" class="btn btn-signup click-disable" onclick="navClick('register-popup')">회원가입</button>
			
                </form>
                
                @endif
              </div>
            </div>

          </div>
          <div class="clearfix"></div>

          <div class="header-bottom">
            <div class="main-container">
              <div class="links">
                <ul class="list-inline click-disable">
                  <li onclick=
                  @auth
                    @if($unreadmsg > 0)
                  
                    "showAlert();"
                    @else                    
                    "navClick('casino-popup')"
                    @endif
                  @else
                    "navClick('login-popup')"
                  @endif
                   class="ng-scope">
                    <p ng-show="$index == 1" class="ng-hide">
                      <i class="fa fa-mobile"></i>
                    </p>
                    <p class="ng-binding" style="margin-bottom: 0px;">라이브카지노</p>
                    <p class="ng-binding" style="font-size: 10px;">LIVE CASINO</p>
                  </li>
				          <li onclick=
                  @auth
                    @if($unreadmsg > 0)
                    "showAlert();"
                    @else                    
                    "navClick('slots-popup')"
                    @endif
                    
                  @else
                    "navClick('login-popup')"
                  @endif
                   class="ng-scope">
                    <p ng-show="$index == 1" class="ng-hide">
                      <i class="fa fa-mobile"></i>
                    </p>
                    <p class="ng-binding" style="margin-bottom: 0px;">슬롯게임</p>
                    <p class="ng-binding" style="font-size: 10px;">SLOT GAME</p>
                  </li>
                  <li onclick=
                  @auth
                    {{$isMini = false}}
                    @foreach($categories AS $index=>$category)
                      @if ($category->type =='pball')
                        @if ($category->view == 0)
                            "swal('지원하지 않는 게임입니다.');"
                        @elseif ($category->status == 0)
                            "swal('점검중입니다');"
                        @else
                          @if($unreadmsg > 0)
                          "showAlert();"
                          @else                    
                          "minisGame('{{$category->href}}','{{$category->trans?$category->trans->trans_title:$category->title}}');"
                          @endif
                            
                        @endif
                        {{$isMini = true}}
                        @break
                      @endif
                    @endforeach
                    @if(!$isMini)
                      "swal('지원하지 않는 게임입니다.');"
                    @endif
                  @else
                    "navClick('login-popup')"
                  @endif
                   class="ng-scope">
                    <p ng-show="$index == 1" class="ng-hide">
                      <i classtartGameByProvider('nexus', 'bt1_sports')
                    </p>
                    <p class="ng-binding" style="margin-bottom: 0px;">미니게임</p>
                    <p class="ng-binding" style="font-size: 10px;">MINI GAME</p>
                  </li>
                  <li onclick=
                  @auth
                    {{$isSports = false}}
                    @foreach($categories AS $index=>$category)
                      @if ($category->type =='sports')
                        @if ($category->view == 0)
                            "swal('지원하지 않는 게임입니다.');"
                        @elseif ($category->status == 0)
                            "swal('점검중입니다');"
                        @else
                          @if($unreadmsg > 0)
                          "showAlert();"
                          @else                    
                          "startGameByProvider('bti', 'sports');"
                          @endif
                            
                        @endif
                        {{$isSports = true}}
                        @break
                      @endif
                    @endforeach
                    @if(!$isSports)
                      "swal('지원하지 않는 게임입니다.');"
                    @endif
                  @else
                    "navClick('login-popup')"
                  @endif
                   class="ng-scope">
                    <p ng-show="$index == 1" class="ng-hide">
                      <i class="fa fa-mobile"></i>
                    </p>
                    <p class="ng-binding" style="margin-bottom: 0px;">스포츠</p>
                    <p class="ng-binding" style="font-size: 10px;">SPORTS</p>
                  </li>
                </ul>
              </div>
              <div class="links">
                <ul class="list-inline click-disable">
                  <li onclick="
                  @auth
                    navClick('page-popup');
                  @else
                    navClick('login-popup');
                  @endif
                    setTab('main-set','#page-popup &gt; div.ngdialog-content &gt; div.ngdialog-wallet-page.ngdialog-main-default-page.ng-scope &gt; ul &gt; li:nth-child(1)')" class="ng-scope">
                    <p ng-show="$index == 1" class="ng-hide">
                      <i class="fa fa-mobile"></i>
                    </p>
                    <p class="ng-binding" style="margin-bottom: 0px;">마이페이지</p>
                    <p class="ng-binding" style="font-size: 10px;">MY PAGE</p>
                  </li>
                  @if(isset($logo) && $logo != 'poseidon02')
                  <li onclick="
                  @auth
                    navClick('page-popup');
                  @else
                    navClick('login-popup');
                  @endif
                  setTab('deposit-set','#page-popup &gt; div.ngdialog-content &gt; div.ngdialog-wallet-page.ngdialog-main-default-page.ng-scope &gt; ul &gt; li:nth-child(2)');mydepositlist();" class="ng-scope">
                    <p ng-show="$index == 1" class="ng-hide">
                      <i class="fa fa-mobile"></i>
                    </p>
                    <p class="ng-binding" style="margin-bottom: 0px;">입금 신청</p>
                    <p class="ng-binding" style="font-size: 10px;">DEPOSIT</p>
                  </li>
                  <li onclick="
                  @auth
                    navClick('page-popup');
                  @else
                    navClick('login-popup');
                  @endif

                  setTab('withdraw-set','#page-popup &gt; div.ngdialog-content &gt; div.ngdialog-wallet-page.ngdialog-main-default-page.ng-scope &gt; ul &gt; li:nth-child(3)');mywithdrawlist();" class="ng-scope">
                    <p ng-show="$index == 1" class="ng-hide">
                      <i class="fa fa-mobile"></i>
                    </p>
                    <p class="ng-binding" style="margin-bottom: 0px;">출금 신청</p>
                    <p class="ng-binding" style="font-size: 10px;">WITHDRAW</p>
                  </li>
                  @endif
                  <li onclick="
                  @auth
                    navClick('msg-popup');
                  @else
                    navClick('login-popup');
                  @endif
                  setTab('notice-set','#msg-popup > div.ngdialog-content > div.ngdialog-customer-page.ngdialog-main-default-page.ng-scope > ul > li:nth-child(1)');" class="ng-scope">
                    <p ng-show="$index == 1" class="ng-hide">
                      <i class="fa fa-mobile"></i>
                    </p>
                    <p class="ng-binding" style="margin-bottom: 0px;">공지사항</p>
                    <p class="ng-binding" style="font-size: 10px;">NOTICE</p>
                  </li>
                  @if(isset($logo) && $logo != 'poseidon02')
                  <li onclick="
                  @auth
                    navClick('msg-popup');
                  @else
                    navClick('login-popup');
                  @endif
                  setTab('customer-set','#msg-popup &gt; div.ngdialog-content &gt; div.ngdialog-customer-page.ngdialog-main-default-page.ng-scope &gt; ul &gt; li:nth-child(3)');getCustomerPage();" class="ng-scope">
                    <p ng-show="$index == 1" class="ng-hide">
                      <i class="fa fa-mobile"></i>
                    </p>
                    <p class="ng-binding" style="margin-bottom: 0px;">고객센터</p>
                    <p class="ng-binding" style="font-size: 10px;">CUSTOMER</p>
                  </li>
                  @endif
                </ul>
              </div>
            </div>
          </div>

        </div>
      </navigation-page>

<script>
  function showAlert() {
      let userConfirmed = confirm("읽지 않은 쪽지가 있습니다.");
      if (userConfirmed) {
        navClick('msg-popup');
        setTab('customer-set','#msg-popup &gt; div.ngdialog-content &gt; div.ngdialog-customer-page.ngdialog-main-default-page.ng-scope &gt; ul &gt; li:nth-child(3)');getCustomerPage();
      } else {
          console.log("취소 버튼 클릭됨!");
      }
  }
</script>
