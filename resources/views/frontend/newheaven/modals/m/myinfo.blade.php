<div id="myinfo-popup" class="ngdialog ngdialog-theme-default ngdialog-custom ngdialog-wallet ng-scope ng-hide" role="alertdialog" aria-labelledby="ngdialog2-aria-labelledby">
  <div class="ngdialog-overlay"></div>
  <div class="ngdialog-content" role="document">
    <div class="ngdialog-wallet-container">
      <div class="ngdialog__header">
        <div class="ngdialog__header__title">
          <h1 translate="" id="ngdialog4-aria-labelledby" class="ng-scope">내 계정</h1>
        </div>
        <div class="ngdialog-close-container">
          <a class="ngdialog-close-btn" href="" ng-click="closeThisDialog(0)"><i class="fa fa-times"></i></a>
        </div>
      </div>

      <div class="my-wallet user-info">
        <div class="text-center">
          <strong>{{auth()->user()->username}}</strong><i role="button" onclick="navClick('msg-popup');setTab('customer-set','#msg-popup &gt; div.ngdialog-content &gt; div.ngdialog-customer-page.ngdialog-main-default-page.ng-scope &gt; ul &gt; li:nth-child(3)','');getCustomerPage();" class="guest-dm fa fa-envelope"> <span ng-bind="UnreadDM" class="ng-binding">{{$unreadmsg}}</span></i>
        </div>
        <div class="pull-right">
          <strong>
            <i role="button" onclick="PointToMoney();" class="guest-dm fa fa-refresh" style="background: #cca400;width: 120px;">
            <span ng-bind="UnreadDM" class="ng-binding msg-text" id="_top_point">{{number_format(auth()->user()->deal_balance)}}</span> P
            </i>
          </strong>
        </div>
      </div>
      <div class="clearfix"></div>
      <div class="balance__items--total-balance balance__items" style="background: #f7f7f7;">
        <div class="balance__content">
          <p class="balance__content__gspName ng-scope" translate="">보유 중인 게임 머니</p>
          <div class="balance__content__amount">            
            <span class="mbalance" id="_top_money">{{number_format(auth()->user()->balance)}}</span> 원 
          </div>
        </div>
      </div>
          
          
      <div class="ngdialog__content ng-scope" ng-if="aff_user =='N'">

        <div class="btn-group btn-group-justified" role="group">
          <div class="btn-group" role="group">
            <button type="button" class="btn btn-black ng-scope" onclick="navClick('page-popup');mydepositlist();setTab('deposit-set','#page-popup > div.ngdialog-content > div.ngdialog-wallet-page.ngdialog-main-default-page.ng-scope > ul > li:nth-child(1)','')" translate="">입금신청</button>
          </div>
          <div class="btn-group" role="group">
            <button type="button" class="btn btn-black ng-scope" onclick="navClick('page-popup');setTab('withdraw-set','#page-popup &gt; div.ngdialog-content &gt; div.ngdialog-wallet-page.ngdialog-main-default-page.ng-scope &gt; ul &gt; li:nth-child(3)','');mywithdrawlist();" translate="">출금신청</button>
          </div>
        </div>
  
        <div class="btn-group btn-group-justified" role="group">
          <div class="btn-group" role="group">
            <button type="button" class="btn btn-black ng-scope" onclick="navClick('msg-popup');setTab('notice-set','#msg-popup > div.ngdialog-content > div.ngdialog-customer-page.ngdialog-main-default-page.ng-scope > ul > li:nth-child(1)','공지사항');" translate="">공지사항</button>
          </div>
          <div class="btn-group" role="group">
            <button type="button" class="btn btn-black ng-scope" onclick="navClick('msg-popup');setTab('customer-set','#msg-popup &gt; div.ngdialog-content &gt; div.ngdialog-customer-page.ngdialog-main-default-page.ng-scope &gt; ul &gt; li:nth-child(3)','고객센터');getCustomerPage();" translate="">
              <span translate="" class="ng-scope">고객센터</span>
              <span class="badge ng-binding cc-text" id="_my_msg">0</span>
            </button>
          </div>
        </div>
        <div class="btn-group btn-group-justified" role="group">
          <div class="btn-group" role="group">
            <button type="button" class="btn btn-black ng-scope" onclick="navClick('msg-popup');setTab('event-set','#msg-popup &gt; div.ngdialog-content &gt; div.ngdialog-customer-page.ngdialog-main-default-page.ng-scope &gt; ul &gt; li:nth-child(2)','이벤트')" translate="">이벤트</button>
          </div>
          <div class="btn-group ng-scope ng-hide" role="group" ng-controller="SettlementController" ng-init="loadSettlement();" ng-show="aff_user=='Y'">
            <button type="button" class="btn btn-black" ng-click="displaySettlement();"><span translate="" class="ng-scope">정산지급 목록</span><span class="badge ng-binding" ng-bind="settlementCount">0</span></button>
          </div>
        </div>
        <button type="button" class="btn btn-black btn-block ng-scope" onclick="logOut()" translate="">로그아웃</button>
      </div>
          
    </div>
  </div>
</div>