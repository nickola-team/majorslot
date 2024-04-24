<anouncement-mobile><div class="announcement-wrapper">
  <button class="announcement__btn">공지사항</button>
  <div class="announcement-container marquee-parent">
    <div class="marquee-child">
        @foreach ($noticelist as $ntc)
        <div class="announcement ng-scope" ng-repeat="{{$ntc}} in {{$noticelist}} | limitTo: 9" onclick="navClick('msg-popup');setTab('notice-set','#msg-popup &gt; div.ngdialog-content &gt; div.ngdialog-customer-page.ngdialog-main-default-page.ng-scope &gt; ul &gt; li:nth-child(1)','공지사항');getNotice('notice{{$ntc->id}}');" class="ng-binding">
            <h1 class="announcement__subject ng-binding">{{$ntc->title}}</h1>
            <p class="announcement__date ng-binding">{{date('Y-m-d', strtotime($ntc->date_time))}}</p>
            <span style="color: #999999; font-weight: 400; position: relative;">|</span>
        </div>
        @endforeach
    </div>
  </div>
</div>
</anouncement-mobile>

@if(Auth::check())
<div class="user-container ng-scope">
  <div class="pull-left">
    <strong>{{auth()->user()->username}}</strong>
    <i role="button" onclick="navClick('msg-popup');setTab('customer-set','#msg-popup &gt; div.ngdialog-content &gt; div.ngdialog-customer-page.ngdialog-main-default-page.ng-scope &gt; ul &gt; li:nth-child(3)','고객센터');getCustomerPage();" class="guest-dm fa fa-envelope"> 
        <span ng-bind="UnreadDM" class="ng-binding">{{$unreadmsg}}</span>
    </i>
  </div>
  <div class="pull-right realtime__btn-container">    
    <div class="player-balance mobile_point_space" onclick="PointToMoney();">
      <p ng-show="totalBalance =='Loading'" ng-bind="totalBalance" class="ng-binding ng-hide" id="_top_point"></p>
      <p ng-show="totalBalance !='Loading'" class="ng-binding">{{number_format(auth()->user()->deal_balance,0)}} <span style="color: #02a6e2;font-weight: 700">P</span></p>
    </div>

    <div class="player-balance" ng-click="displayWallet()">
      <p ng-show="totalBalance =='Loading'" ng-bind="totalBalance" class="ng-binding ng-hide"></p>
      <p ng-show="totalBalance !='Loading'" class="ng-binding">{{number_format(auth()->user()->balance,0)}} <span style="color: #02a6e2;font-weight: 700">원</span></p>
    </div>
  </div> 
</div>
@endif

<gamebutton-page>
    <div class="gamebuttons-page">
        <div ng-repeat="gamebutton in gamebuttons" class="game-buttons jackpot-open" ng-if=" gamebutton.category=='jackpot'">
            <jackpot-page>
                <div class="odometer-container" style="bottom: 0; right: 0;">
                <h1 class="odometer__heading">Progressive Jackpot</h1>
                    <div class="container-jackpot">
                        <div class="currency-sign">
                            <img src="/frontend/newheaven/mobile/common/images/odometer/won-sign.png">
                        </div>
                        <div class="jackpot-odometer jackpot"></div>
                    </div>
                </div>
            </jackpot-page>
        </div>
        
        <div ng-repeat="gamebutton in gamebuttons" ng-if="gamebutton.category == 'casino' " class="game-buttons casino-open" onclick=@auth "navClick('casino-popup')" @else "navClick('login-popup')" @endif>
            <img class="gamebutton__img" ng-src="/frontend/newheaven/mobile/common/images/main/mobile-game-button-1.jpg" src="/frontend/newheaven/mobile/common/images/main/mobile-game-button-1.jpg">
        </div>
        <div id="halfwidth" ng-repeat="gamebutton in gamebuttons" ng-if="gamebutton.category != 'casino' &amp;&amp; gamebutton.category != 'jackpot' " class="game-buttons " onclick=@auth "navClick('slots-popup')" @else "navClick('login-popup')"
        @endif>
            <img class="gamebutton__img" ng-src="/frontend/newheaven/mobile/common/images/main/mobile-game-button-3.jpg" src="/frontend/newheaven/mobile/common/images/main/mobile-game-button-3.jpg">
        </div>
        <div id="halfwidth" ng-repeat="gamebutton in gamebuttons" ng-if="gamebutton.category != 'casino' &amp;&amp; gamebutton.category != 'jackpot' " class="game-buttons" onclick=@auth
                      {{$isSports = false}}
                      @foreach($categories AS $index=>$category)
                          @if ($category->type =='sports')
                              @if ($category->status == 0)
                                  "swal('점검중입니다');"
                              @elseif ($category->view == 0)
                                  "swal('지원하지 않는 게임입니다.');"
                              @else
                                  "startGameByProvider('bti', 'sports')"
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
                    @endif>
            <img class="gamebutton__img" ng-src="/frontend/newheaven/mobile/common/images/main/mobile-game-button-5.jpg" src="/frontend/newheaven/mobile/common/images/main/mobile-game-button-5.jpg">
        </div>
        <div id="halfwidth" ng-repeat="gamebutton in gamebuttons" ng-if="gamebutton.category != 'casino' &amp;&amp; gamebutton.category != 'jackpot' " class="game-buttons mini-games-open" onclick=@auth
                    @php
                    $isMini = false;
                    @endphp
                    @foreach($categories AS $index=>$category)
                        @if ($category->type =='pball')
                            @if ($category->view == 0)
                                "swal('지원하지 않는 게임입니다.');"
                            @elseif ($category->status == 0)
                                "swal('점검중입니다');"
                            @else
                                "minisGame('{{$category->href}}','{{$category->trans?$category->trans->trans_title:$category->title}}');"
                            @endif
                                @php
                                    $isMini = true;
                                @endphp
                            @break
                        @endif
                    @endforeach
                    @if(!$isMini)
                        "swal('지원하지 않는 게임입니다.');"
                    @endif
                @else
                "navClick('login-popup')"
                @endif>
            <img class="gamebutton__img" ng-src="/frontend/newheaven/mobile/common/images/main/mobile-game-button-4.jpg" src="/frontend/newheaven/mobile/common/images/main/mobile-game-button-4.jpg">
        </div>
        <div id="halfwidth" ng-repeat="gamebutton in gamebuttons" ng-if="gamebutton.category != 'casino' &amp;&amp; gamebutton.category != 'jackpot' " class="game-buttons " onclick=@auth
                    @php
                    $isMini = false;
                    @endphp
                    @foreach($categories AS $index=>$category)
                        @if ($category->type =='pball')
                            @if ($category->view == 0)
                                "swal('지원하지 않는 게임입니다.');"
                            @elseif ($category->status == 0)
                                "swal('점검중입니다');"
                            @else
                                "minisGame('{{$category->href}}','{{$category->trans?$category->trans->trans_title:$category->title}}');"
                            @endif
                                @php
                                    $isMini = true;
                                @endphp
                            @break
                        @endif
                    @endforeach
                    @if(!$isMini)
                        "swal('지원하지 않는 게임입니다.');"
                    @endif
                @else
                "navClick('login-popup')"
                @endif>
            <img class="gamebutton__img" ng-src="/frontend/newheaven/mobile/common/images/main/mobile-game-button-2.jpg" src="/frontend/newheaven/mobile/common/images/main/mobile-game-button-2.jpg">
        </div>        
        
        
    </div>
</gamebutton-page>



<customerservice-page>
<div class="realtime-event-container transaction-page" ng-init="activeTab = 1">
  <div class="realtime__btn-container">
    <button class="btn btn-plain outline active" id="notice_cont" onclick="setMsgNotice('event-ticker','notice_cont','promotion-ticker','promotion_cont');">공지사항</button>
    <button class="btn btn-plain outline" id="promotion_cont" onclick="setMsgNotice('promotion-ticker','promotion_cont','event-ticker','notice_cont');">고객센터</button>
  </div>
  <div class="transaction-container">
    <div class="realtime-event transaction-table">    
        <ul class="realtime-event__list transaction-list event-ticker" id="event-ticker" ng-show="activeTab == 1" style="height: 160px; overflow: hidden;">
        
            @foreach ($noticelist as $ntc)
                <li class="realtime-event__list__item ng-scope" style="margin-top: 0px;">
                    <div class=" ng-binding" onclick="navClick('msg-popup');setTab('notice-set','#msg-popup &gt; div.ngdialog-content &gt; div.ngdialog-customer-page.ngdialog-main-default-page.ng-scope &gt; ul &gt; li:nth-child(1)','공지사항');getNotice('notice{{$ntc->id}}');" style="padding: 10px 20px;">{{$ntc->title}}</div>
                    <div class="realtime-event__list__item__date ng-binding">{{date('Y-m-d', strtotime($ntc->date_time))}}</div>
                </li>
            @endforeach 
        
        </ul>

        <ul class="realtime-event__list transaction-list promotion-ticker ng-hide" id="promotion-ticker" ng-show="activeTab == 2" style="height: 160px; overflow: hidden;">
        
            @foreach($msgs as $msg)
            <li class="realtime-event__list__item ng-scope" ng-repeat="{{$msg}} in {{$msgs}} | limitTo: 20" style="margin-top: 0px;">
                <div class=" ng-binding" ng-bind="event.Subject" onclick="navClick('msg-popup');setTab('customer-set','#msg-popup > div.ngdialog-content > div.ngdialog-customer-page.ngdialog-main-default-page.ng-scope > ul > li:nth-child(3)','고객센터');getCustomerPage();" style="padding: 10px 20px;">{{$msg->title}}</div>
                <div class="realtime-event__list__item__date ng-binding" ng-bind="formatDate(event.WriteDate) | date:'yyyy-MM-dd'">{{date('Y-m-d', strtotime($msg->created_at))}}</div>
            </li>
            @endforeach
        </ul>
    </div>
  </div>
  
</div>
</customerservice-page>
<div class="clearfix"></div>


<div class="footer main">
    <button class="pull-left btn btn-custom-shape bottom-left" onclick=@if (!Auth::check()) "navClick('login-popup')"
            @else "navClick('page-popup');mydepositlist();setTab('deposit-set','#page-popup > div.ngdialog-content > div.ngdialog-wallet-page.ngdialog-main-default-page.ng-scope > ul > li:nth-child(1)','')"
            @endif><strong>입금
            신청</strong></button>
    @if (isset($telegram) && $telegram!='')
    <span class="btn btn-primary btn-live footer-btn live-chat" style="color: #ffffff; _opacity: 0;"
        onclick="window.open('https://t.me/{{$telegram??'Boss텔레'}}');"><img
            src="/frontend/boss/assets/images/telegram-logo.png" height="100%" border="0"
            alt=""><strong></strong></span>
    @endif
    <button class="pull-right btn btn-custom-shape bottom-right" onclick=@if (!Auth::check()) "navClick('login-popup')"
            @else "navClick('page-popup');setTab('withdraw-set','#page-popup &gt; div.ngdialog-content &gt; div.ngdialog-wallet-page.ngdialog-main-default-page.ng-scope &gt; ul &gt; li:nth-child(3)','');mywithdrawlist();"
            @endif><strong>출금
            신청</strong></button>
</div>


<script>
    $(document).ready(function(){
        $('.jackpot-odometer').jOdometer({
            increment: 24,
            counterStart: 39888441,
            counterEnd: false,
            numbersImage: '/frontend/newheaven/mobile/common/images/odometer/odometer-small.png',
            spaceNumbers: -1,
            formatNumber: true,
            widthNumber: 20,
            heightNumber: 44
        });
    });

    setTimeout(function () {
        $('#event-ticker').newsTicker({
        row_height: 32,
        max_rows: 5,
        direction: 'up',
        autostart: 1,
        pauseOnHover: 1,
        delay : 10000
        });
        $('#promotion-ticker').newsTicker({
        row_height: 32,
        max_rows: 5,
        direction: 'up',
        autostart: 1,
        pauseOnHover: 1,
        delay : 10000
        });
  });
</script>
