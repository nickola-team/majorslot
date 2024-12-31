<div id="myinfo-popup" class="ngdialog ngdialog-theme-default ngdialog-custom ngdialog-wallet ng-scope ng-hide" role="alertdialog" aria-labelledby="ngdialog2-aria-labelledby">
      <div class="ngdialog-overlay"></div>
      <div class="ngdialog-content" role="document">
        <div class="ngdialog-wallet-container">
          <div class="ngdialog__heading">
            <div class="pull-left">
              <h5 class="ngdialog__title" id="ngdialog2-aria-labelledby" tabindex="-1" style="outline: 0px;">
                <strong>{{auth()->user()->username}}<i role="button" onclick="navClick('msg-popup');setTab('customer-set','#msg-popup &gt; div.ngdialog-content &gt; div.ngdialog-customer-page.ngdialog-main-default-page.ng-scope &gt; ul &gt; li:nth-child(3)');getCustomerPage();" class="guest-dm fa fa-envelope">
                    <span ng-bind="UnreadDM" class="ng-binding msg-text cc-text" id="_top_msg">{{$unreadmsg}}</span>
                  </i>
                </strong>
				<strong>
				  <i role="button" onclick="PointToMoney();" class="guest-dm fa fa-refresh" style="background: #cca400;width: 120px;">
					<span ng-bind="UnreadDM" class="ng-binding msg-text" id="_top_point">{{number_format(auth()->user()->deal_balance)}}</span> P
				  </i>
				</strong>
              </h5>
            </div>
          </div>
          <div class="my-wallet">
            <div class="text-left">
              <span class="text-center">보유 중인 게임 머니</span>
              <strong class="balance-amount ng-binding" style="float: right;">
                <span class="mbalance" id="_top_money">{{number_format(auth()->user()->balance)}}</span> 원 </strong>
            </div>
            <div class="pull-right">
              <!--      <span class="get-balance" ng-click="getBalance()"><i class="glyphicon glyphicon-refresh"></i> 새로고침</span>-->
            </div>
          </div>
          
          
          <div class="ngdialog__content ng-scope" ng-if="aff_user =='N'">

            {{-- <div class="btn-group btn-group-justified" role="group">
              <div class="btn-group" role="group">
                <button type="button" class="btn btn-black ng-scope" onclick="navClick('page-popup');mydepositlist();setTab('deposit-set','#page-popup > div.ngdialog-content > div.ngdialog-wallet-page.ngdialog-main-default-page.ng-scope > ul > li:nth-child(1)')" translate="">입금신청</button>
              </div>
              <div class="btn-group" role="group">
                <button type="button" class="btn btn-black ng-scope" onclick="navClick('page-popup');setTab('withdraw-set','#page-popup &gt; div.ngdialog-content &gt; div.ngdialog-wallet-page.ngdialog-main-default-page.ng-scope &gt; ul &gt; li:nth-child(3)');mywithdrawlist();" translate="">출금신청</button>
              </div>
            </div> --}}
 
            <div class="btn-group btn-group-justified" role="group">
              <div class="btn-group" role="group">
                <button type="button" class="btn btn-black ng-scope" onclick="navClick('msg-popup');setTab('notice-set','#msg-popup > div.ngdialog-content > div.ngdialog-customer-page.ngdialog-main-default-page.ng-scope > ul > li:nth-child(1)');" translate="">공지사항</button>
              </div>
              <div class="btn-group" role="group">
                <button type="button" class="btn btn-black ng-scope" onclick="navClick('msg-popup');setTab('customer-set','#msg-popup &gt; div.ngdialog-content &gt; div.ngdialog-customer-page.ngdialog-main-default-page.ng-scope &gt; ul &gt; li:nth-child(3)');getCustomerPage();" translate="">
                  <span translate="" class="ng-scope">고객센터</span>
                  <span class="badge ng-binding cc-text" id="_my_msg">0</span>
                </button>
              </div>
			</div>
            <div class="btn-group btn-group-justified" role="group">
              <div class="btn-group" role="group">
                <button type="button" class="btn btn-black ng-scope" onclick="navClick('msg-popup');setTab('event-set','#msg-popup &gt; div.ngdialog-content &gt; div.ngdialog-customer-page.ngdialog-main-default-page.ng-scope &gt; ul &gt; li:nth-child(2)')" translate="">이벤트</button>
              </div>
              <div class="btn-group" role="group">
                <button type="button" class="btn btn-black ng-scope" onclick="swal('관리자에 문의하세요');" translate="">비밀번호 변경</button>
              </div>
            </div>
            <button type="button" class="btn btn-black btn-block ng-scope" onclick="logOut()" translate="">로그아웃</button>
          </div>
          <!-- end ngIf: aff_user =='N' -->
        </div>
        <div class="ngdialog-close"></div>
      </div>
    </div>