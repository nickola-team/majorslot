
<div class="game-buttons click-disable ng-scope live" onclick=
@auth
"javascript:casinoGameStart('gvo');"
@else
"navClick('login-popup')"
@endif
>
    <span ng-bind="mainButton.title" class="ng-binding">라이브 카지노</span>
    <span ng-bind="mainButton.subTitle" class="ng-binding">세계 최고의 카지노 게임들을 만나보세요.</span>
</div>
<div class="game-buttons click-disable ng-scope others" onclick=
@auth
"navClick('slots-popup')"
@else
"navClick('login-popup')"
@endif
>
    <span ng-bind="mainButton.title" class="ng-binding">슬롯게임</span>
    <span ng-bind="mainButton.subTitle" class="ng-binding">최고의 슬롯게임에서 잭팟을 도전하세요.</span>
</div>

<div class="game-buttons click-disable ng-scope mini" onclick=
@auth
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
@endif
>
    <span ng-bind="mainButton.title" class="ng-binding">미니게임</span>
    <span ng-bind="mainButton.subTitle" class="ng-binding">최고의 미니게임에서 행운에 도전하세요.</span>
</div>

<div class="game-buttons click-disable ng-scope comming">
    <span ng-bind="mainButton.title" class="ng-binding">핫게임</span>
    <span ng-bind="mainButton.subTitle" class="ng-binding">핫게임 기대해 주세요.</span>
</div>



<div class="game-buttons click-disable ng-scope slot" style="width: 100%;cursor: default;">
    <div class="odometer-container clearfix ng-scope">
        <img src="/frontend/boss/V/won-sign.png" alt="">
        <div class="jackpot-odometer">
        </div>
    </div>
</div>
<coupon-page>
    <div class="jackpot-container">
        <div class="jackpot-content">
        <!-- <form ng-controller="CouponController" class="ng-pristine ng-valid ng-scope"><div class="form-group"><select class="form-control click-disable ng-pristine ng-untouched ng-valid" name="GameCode" ng-click="displayLogin()" ng-model="transfer.fromWallet" ng-options="agentGsp.gspNo as agentGsp.gspName for agentGsp in agentGspList | filter:{gspNo:'!1070'} | filter:{transferEnable:true}| filter:maintenanceFilter | filter:combinedMaintenanceByCurrency | orderBy:'OrderNumber'"><option value="" translate="" class="" selected="selected">게임을 선택해주세요</option></select></div><div class="form-group"><input type="text" class="form-control ng-pristine ng-untouched ng-valid" placeholder="IP당 쿠폰은 1회만 사용 가능합니다." name="CouponCode" ng-model="CouponCode"></div><div class="form-group margin-right-55"><button ng-if="!loggedIn" class="btn btn-sm btn-yellow click-disable ng-scope" ng-click="displayLogin();">확인</button></div><div class="form-group btn-coupon"><button class="btn btn-sm btn-yellow click-disable margin-right-10" ng-click="displayCustomer(4); setTab(4);">1:1 문의 게시판</button></div></form> -->
        </div>
    </div>
</coupon-page>