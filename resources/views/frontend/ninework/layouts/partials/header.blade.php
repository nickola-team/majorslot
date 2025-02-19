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
            <div class="logo-container" style="cursor: pointer;z-index: 999999; left:45%" onclick="window.location.href='/';">
              <div class="content">
                <img src="/frontend/{{$logo??'boss'}}/LOGO.png?0.1" style="width:70%;padding-top:10px;">
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
                    "navClick('casino-popup')"
                  @else
                    "navClick('login-popup')"
                  @endif
                   class="ng-scope">
                    <p ng-show="$index == 1" class="ng-hide">
                      <i class="fa fa-mobile"></i>
                    </p>
                    <p class="ng-binding" style="margin-bottom: 0px;font-size:20px;font-weight:bold;">라이브카지노</p>
                    <p class="ng-binding" style="font-size: 10px; font-weight:bold;">LIVE CASINO</p>
                  </li>
				          <li onclick=
                  @auth
                    "navClick('slots-popup')"
                  @else
                    "navClick('login-popup')"
                  @endif
                   class="ng-scope">
                    <p ng-show="$index == 1" class="ng-hide">
                      <i class="fa fa-mobile"></i>
                    </p>
                    <p class="ng-binding" style="margin-bottom: 0px;font-size:20px;font-weight:bold;">슬롯게임</p>
                    <p class="ng-binding" style="font-size: 10px;font-weight:bold;">SLOT GAME</p>
                  </li>                  
                  <!-- <li onclick=
                  @auth
                    {{$isSports = false}}
                    @foreach($categories AS $index=>$category)
                      @if ($category->type =='sports')
                        @if ($category->view == 0)
                            "swal('지원하지 않는 게임입니다.');"
                        @elseif ($category->status == 0)
                            "swal('점검중입니다');"
                        @else
                            "startGameByProvider('nexus', 'bt1_sports');"
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
                  </li> -->
                  <li onclick=
                  @auth
                  "holdemOpen('holdem-popup')"                    
                  @else
                    "navClick('login-popup')"
                  @endif
                   class="ng-scope">
                    <p ng-show="$index == 1" class="ng-hide">
                      <i class="fa fa-mobile"></i>
                    </p>
                    <p class="ng-binding" style="margin-bottom: 0px;font-size:20px;font-weight:bold;">와일드 홀덤</p>
                    <p class="ng-binding" style="font-size: 10px;font-weight:bold;">WILD HOLDEM</p>
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
                    <p class="ng-binding" style="margin-bottom: 0px;font-weight:bold;">마이페이지</p>
                    <p class="ng-binding" style="font-size: 10px;font-weight:bold;">MY PAGE</p>
                  </li>
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
                    <p class="ng-binding" style="margin-bottom: 0px;font-weight:bold;">입금 신청</p>
                    <p class="ng-binding" style="font-size: 10px;font-weight:bold;">DEPOSIT</p>
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
                    <p class="ng-binding" style="margin-bottom: 0px;font-weight:bold;">출금 신청</p>
                    <p class="ng-binding" style="font-size: 10px;font-weight:bold;">WITHDRAW</p>
                  </li>
			
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
                    <p class="ng-binding" style="margin-bottom: 0px;font-weight:bold;">공지사항</p>
                    <p class="ng-binding" style="font-size: 10px;font-weight:bold;">NOTICE</p>
                  </li>
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
                    <p class="ng-binding" style="margin-bottom: 0px;font-weight:bold;">고객센터</p>
                    <p class="ng-binding" style="font-size: 10px;font-weight:bold;">CUSTOMER</p>
                  </li>
                </ul>
              </div>
            </div>
          </div>

        </div>
      </navigation-page>

<script>
  function holdemOpen(id){
    $("#" + id).removeClass('ng-hide');

      $.ajax({
      type: "POST",
      url: "/api/getgamelist",
      data: {category : 'card'},
      cache: false,
      async: true,
      success: function (data, status) {
        if (data.error) {
          alert(data.msg);
          return;
        }
        if (data.games.length > 0) {
          var htmldoc = ``;
          for (i=0;i<data.games.length;i++)
          {
            if (data.games[i].provider)
            {
              htmldoc += `<a href="#" onclick="startGameByProvider('${data.games[i].provider}', '${data.games[i].gamecode}');" class="hg-btn"><div class="img-cont"><img class="main-img" src="${data.games[i].icon}" alt="" style="height: 135px;width: 155px;"></div><div class="foot"><p>${data.games[i].title}</p></div>`;
            }
            else
            {
              htmldoc += `<a href="#" onclick="startGameByProvider(null, '${data.games[i].name}');"  class="hg-btn"><div class="img-cont"><img class="main-img" src="/frontend/Default/ico/${data.games[i].name}.jpg" alt="" style="height: 135px;width: 155px;"></div><div class="foot"><p>${data.games[i].title}</p></div>`;
            }
                      htmldoc += `<div class="overlay">
                              <p><i class="glyphicon glyphicon-log-in"></i> 게임하기</p>
                          </div></a>`;
          }
                  
          $('#holdemprovider-title').html('와일드 홀덤');
          $('#holdemgame-list-area').html(htmldoc);
        }
        else
        {
          alert('게임이 없습니다');
        }
        },
            complete: function() {
              $('.loading').hide();
              navClick('holdem-popup');
          }
    });
  }
</script>
