<guest-page>
    @if (Auth::check()) 
    <div class="wallet-page">
        <div class="rigth-container-title">
            <div style="margin-bottom: 5px">
                <strong class="guest_name">{{auth()->user()->username}}</strong>
                <strong>
                    <i role="button" onclick="navClick('msg-popup');setTab('customer-set','#msg-popup &gt; div.ngdialog-content &gt; div.ngdialog-customer-page.ngdialog-main-default-page.ng-scope &gt; ul &gt; li:nth-child(3)','');getCustomerPage();" class="guest-dm fa fa-envelope">
                    <span ng-bind="UnreadDM" class="ng-binding msg-text" id="_top_msg">{{$unreadmsg}}</span>
                    </i>
                </strong>                
                <button type="button" class="logout ng-scope text-white" ng-controller="LogoutController" onclick="logOut();">로그아웃</button>
            </div>
        </div>
        <div class="my-wallet-container">
            <div class="col-xs-12">
                <strong class="text-uppercase">마이 포인트</strong>
                <strong class="text-uppercase pull-right">
                    <strong role="button" onclick="PointToMoney();" style="width:60px">
                    <span ng-bind="UnreadDM" class="ng-binding msg-text" id="_top_point">{{number_format(auth()->user()->deal_balance,0)}}</span> P
                    </strong>
                </strong>
            </div>
            <div class="col-xs-12">
                <strong class="text-uppercase">보유 금액</strong>
                <strong class="text-uppercase pull-right" ng-show="totalBalance =='Loading'" ng-bind="totalBalance">{{number_format(auth()->user()->balance,0)}}</span> 원 </strong>
            </div>
            
            
            <div class="clearfix"></div>
        </div>
        <ul class="wallet-button">
            <li class="btn-primary-bordered" href="javascript:;" onclick="
                  @auth
                    navClick('page-popup');
                  @else
                    navClick('login-popup');
                  @endif
                    setTab('main-set','#page-popup &gt; div.ngdialog-content &gt; div.ngdialog-wallet-page.ngdialog-main-default-page.ng-scope &gt; ul &gt; li:nth-child(1)','')">마이 월렛</li>
            <li class="btn-primary-bordered" href="javascript:;" onclick="
                  @auth
                    navClick('page-popup');
                  @else
                    navClick('login-popup');
                  @endif
                  setTab('deposit-set','#page-popup &gt; div.ngdialog-content &gt; div.ngdialog-wallet-page.ngdialog-main-default-page.ng-scope &gt; ul &gt; li:nth-child(2)','');mydepositlist();">입금 신청</li>
            <li class="btn-primary-bordered" href="javascript:;" onclick="
                  @auth
                    navClick('page-popup');
                  @else
                    navClick('login-popup');
                  @endif

                  setTab('withdraw-set','#page-popup &gt; div.ngdialog-content &gt; div.ngdialog-wallet-page.ngdialog-main-default-page.ng-scope &gt; ul &gt; li:nth-child(3)','');mywithdrawlist();">출금 신청</li>            
            <li class="btn-primary-bordered" href="javascript:;" onclick="
                  @auth
                    navClick('msg-popup');
                  @else
                    navClick('login-popup');
                  @endif
                  setTab('notice-set','#msg-popup > div.ngdialog-content > div.ngdialog-customer-page.ngdialog-main-default-page.ng-scope > ul > li:nth-child(1)','고객 센터');">고객 센터</li>
            <li class="btn-primary-bordered" onclick="navClick('msg-popup');setTab('notice-set','#msg-popup &gt; div.ngdialog-content &gt; div.ngdialog-customer-page.ngdialog-main-default-page.ng-scope &gt; ul &gt; li:nth-child(1)','공지사항');">공지 사항</li>
        </ul>

        {{--<table class="history-table table" id="noticelist">
            <tbody>
                @foreach ($noticelist as $ntc)
                <tr ng-repeat="announce in filteredPage" class="ng-scope">
                    <td ng-bind="announce.num" class="width12 text-center ng-binding">{{$ntc->id}}</td>
                    <td class="text-left width70">
                    <a href="javascript:getNotice('notice{{$ntc->id}}')" class="ng-binding">{{$ntc->title}}</a>
                    </td>
                    <td class="width15 text-center ng-binding">{{date('Y-m-d', strtotime($ntc->date_time))}}</td>
                </tr>
                @endforeach

            </tbody>
        </table>--}}
        <div class="user-balance-container" style="margin-top:15px;">
            <div class="balance-overflow">
                <div class="walletCat" style="text-align: center;">
                    <h5 class="walletCat__title">공지</h5>
                </div>
                <table  class="table h5 scrollbar" >
                    <tbody>
                    @foreach ($noticelist as $ntc)
                        <tr ng-repeat="agentGsp in agentGspList |  orderBy:'OrderNumber'| limitTo: rightPanelLimit" ng-if="agentGsp.category == walletCategoryValues.category">
                            <td class="text-left width80">
                            <a href="javascript:;" onclick="navClick('msg-popup');setTab('notice-set','#msg-popup &gt; div.ngdialog-content &gt; div.ngdialog-customer-page.ngdialog-main-default-page.ng-scope &gt; ul &gt; li:nth-child(1)','공지사항');getNotice('notice{{$ntc->id}}');">{{$ntc->title}}</a>
                            </td>
                            <td class="width15 text-center">{{date('Y-m-d', strtotime($ntc->date_time))}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
    @else
    <div class="login-page">
        <div class="rigth-container-title" style="padding: 10px 20px;">
            <span>멤버 로그인</span>
        </div>
        <div class="login-form">
            <form class="ng-pristine ng-valid ng-scope">
            <input type="text" class="form-control  guest-input ng-pristine ng-untouched ng-valid" ng-model="loginForm.nickname" id="sID2" placeholder="아이디">
            <input type="password" class="form-control guest-input ng-pristine ng-untouched ng-valid" autocomplete="new-password" ng-model="loginForm.password" placeholder="비밀번호" id="sPASS2">
            <button type="button" class="btn btn-block btn-black btn-primary-bordered sbmt-login2" style="margin-top: 20px">로그인</button>
            <button type="button" class="btn btn-block btn-primary btn-signup click-disable" onclick="navClick('register-popup')">회원가입</button>
            </form>
        </div>
    </div>
    @endif
</guest-page>