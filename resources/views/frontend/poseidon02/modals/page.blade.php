

<div id="page-popup" class="ngdialog ngdialog-theme-default ngdialog-main-default ngdialog-wallet ng-scope ng-hide">
        <div class="ngdialog-overlay"></div>
        <div class="ngdialog-content" role="document">
          <div class="ngdialog-wallet-page ngdialog-main-default-page ng-scope" ng-controller="WalletController" ng-init="setTab(selectWalletTab)">
            <div class="ngdialog__heading">
              <h4 class="ngdialog__title ng-scope" translate="" tabindex="-1" style="outline: 0px;">마이페이지</h4>
            </div>
            @if( $detect->isMobile() || $detect->isTablet() ) 
            @else
            <ul class="ngdialog-main-nav list-inline clearfix mr-auto">
              <li onclick="setTab('main-set',this);">
                <span class="main-nav__label ng-scope" translate="">내 프로필</span>
              </li>              
              <li onclick="setTab('change-password-set',this);">
                <span class="main-nav__label ng-scope" translate="">비밀번호 변경</span>
              </li>
            </ul>
            @endif
            <div id="main-set" class="ngdialog-main-default__content withdraw ng-scope set-content font-white ng-hide">
              <div class="withdraw manual">
                <form novalidate="" ng-submit="processForm();" class="ngdialog-wallet__form ng-pristine ng-invalid ng-invalid-required ng-valid-minlength ng-valid-maxlength ng-valid-have-special-char ng-valid-special-char-c ng-valid-no-match">
                  <div class="ngdialog-wallet__form">
                    <fieldset>
                      <label>
                        <span translate="" class="ng-scope">아이디</span>
                      </label>
                      <div class="col-xs-5">
                        <input type="text" class="form-control ng-pristine ng-untouched ng-valid" disabled="" value="{{auth()->user()->username}}">
                      </div>
                    </fieldset>
                    
                  </div>
                </form>
              </div>
            </div>
            <script type="text/javascript"></script>
            <style type="text/css">
              #deposit-set .btn-yellow.processBtn {
                height: 40px;
                width: 200px;
                margin: 20px auto 10px;
                display: inline-block;
              }

              .btn-pointr.active {
                background: #fff;
                color: #000;
              }

              .btn-pointr {
                color: #fff;
                float: left;
                cursor: pointer;
                padding: 10px;
                border: 1px solid #fff;
                width: 50%;
                max-width: 400px;
                margin: 10px 0;
                line-height: normal;
              }

              .btn-pointr:first-child {
                border-radius: 10px 0px 0px 10px;
              }

              .btn-pointr:last-child {
                border-radius: 0px 10px 10px 0px;
              }

              @media screen and (max-width: 700px) {
                .btn-pointr {
                  width: 100%;
                  margin-bottom: 0px;
                }

                .btn-pointr:first-child {
                  border-radius: 10px 10px 0px 0px;
                }

                .btn-pointr:last-child {
                  border-radius: 0px 0px 10px 10px;
                  margin-bottom: 10px;
                }
              }
            </style>
            <div id="deposit-set" class="ngdialog-main-default__content deposit font-white ng-scope">
              <div class="deposit manual">
                <div class="alert alert-danger">
                  <p>
                    <strong translate="" class="ng-scope">수표입금시 입금처리 절대 되지 않습니다</strong>
                  </p>
                  <!-- <p><strong>IDN 포커는 타 게임과 머니이동이 불가능합니다. 포커를 플레이하기 원하시면 IDN 포커 게임 선택후 입금을 진행해주시기 바랍니다.</strong></p> -->
                </div>
                <form novalidate="" ng-submit="processForm();" class="ng-pristine ng-invalid ng-invalid-required ng-valid-maxlength">
                  <div class="ngdialog-wallet__form">

                    <fieldset style="position: relative">
                      <label>
                        <span translate="" class="ng-scope">입금하실 금액</span>
                        <span>*</span>
                      </label>
                      <div class="col-xs-3">
                        <input type="text" class="form-control text-right wonText bg-dark ng-pristine ng-untouched ng-valid ng-valid-maxlength money" placeholder="0" value="0" id="charge_money" onkeyup="changeChargeMoney(this);numChk(this);" onchange="numChk(this)" onclick="this.value=''">
                        <p class="won-text deposit">원</p>
                        <strong class="mini-notice ng-scope" translate=""></strong>
                      </div>
                      <div class="col-xs-6 side-note no-padding">
                        <span>
                          <button type="button" class="btn btn-sm font-bold btn-wallet-amount amountBtn" onclick="addMoneyDeposit(10000)">1만원</button>
                          <button type="button" class="btn btn-sm font-bold btn-wallet-amount amountBtn" onclick="addMoneyDeposit(50000)">5만원</button>
                          <button type="button" class="btn btn-sm font-bold btn-wallet-amount amountBtn" onclick="addMoneyDeposit(100000)">10만원</button>
                          <button type="button" class="btn btn-sm font-bold btn-wallet-amount amountBtn" onclick="addMoneyDeposit(500000)">50만원</button>
                          <button type="button" class="btn btn-sm font-bold btn-wallet-amount amountBtn" onclick="addMoneyDeposit(1000000)">100만원</button>
                          <button type="button" class="btn btn-sm font-bold btn-wallet-clear font-white border-grey amountBtn ng-scope" onclick="resetMoneyDeposit()" translate="">Clear</button>
                        </span>
                      </div>
                    </fieldset>
                    <fieldset>
                      <label>
                        <span translate="" class="ng-scope">입금자명</span>
                        <span>*</span>
                      </label>
                      <div class="col-xs-3">
                        <input type="text" class="form-control ng-pristine ng-untouched ng-valid" placeholder="이름은 한글 2-5자리 입니다" disabled="disabled" value="{{mb_substr(auth()->user()->recommender, 0, 2) . '*'}}">
                      </div>
                    </fieldset>
                  </div>
                  <div class="alert alert-danger">
                    <p>
                      <strong translate="" class="ng-scope">23:50 ~ 00:30, 휴일 다음 첫 영업일 새벽에는 은행점검으로 인해 계좌이체가 지연될 수 있습니다</strong>
                    </p>
                    <p>
                      <strong translate="" class="ng-scope">위 시간 이외에도 몇몇 은행은 추가적 점검시간이 따로 있으니 이점 유념하시기 바랍니다</strong>
                    </p>
                  </div>
                  <div class="text-center">
                    <button type="button" class="btn btn-theme btn-block btn-yellow processBtn ng-scope btn-charge" onclick="depositRequest();">입금신청</button>
                    <button type="button" class="btn btn-theme btn-block btn-yellow processBtn ng-scope" onclick="autorequest();">계좌문의</button>
                  </div>
                </form>
              </div>
              <font size="" color="#ff00ff"><b>*최근 1주일</b></font>
              <table class="history-table table" id="mydeposit">
                
              </table>
            </div>
<!-- 출금신청-->
            <div id="withdraw-set" class="ngdialog-main-default__content withdraw font-white ng-scope ng-hide">
              <div class="withdraw manual">
                <div>
                  <p>
                  </p>
                  <!-- <p><strong>IDN 포커는 타 게임과 머니이동이 불가능합니다. 포커를 플레이하기 원하시면 IDN 포커 게임 선택후 입금을 진행해주시기 바랍니다.</strong></p> -->
                </div>
                <form novalidate="" ng-submit="processForm();" class="ng-pristine ng-valid-maxlength ng-invalid ng-invalid-required">
                  <div class="ngdialog-wallet__form">
                    <fieldset style="position: relative">
                      <label>
                        <span translate="" class="ng-scope">보유머니</span>
                      </label>
                      <div class="col-xs-4">
                        <strong class="text-uppercase font-white pull-right ng-binding ">
                                    <h4><span class="mbalance" id="_my_money">{{number_format(auth()->user()->balance)}}</span> 원 </h4>
                        </strong>
                      </div>
                    </fieldset>
                    <fieldset style="position: relative;">
                      <label>
                        <span translate="" class="ng-scope">출금하실 금액</span>
                        <span>*</span>
                      </label>
                      <div class="col-xs-3">
                        <input type="text" class="form-control bg-dark text-right wonText ng-pristine ng-untouched ng-valid ng-valid-maxlength exchange_money" onkeyup="numChk(this);" onchange="numChk(this)" onclick="this.value=''" value="0" id="exchange_money">
                        <p class="won-text withdraw">원</p>
                        <strong class="mini-notice ng-scope" translate=""></strong>
                      </div>
                      <div class="col-xs-6 no-padding side-note">
                        <span class="ng-scope">
                          <button type="button" class="btn btn-sm font-bold btn-wallet-amount amountBtn" onclick="addMoney(10000)">1만원</button>
                          <button type="button" class="btn btn-sm font-bold btn-wallet-amount amountBtn" onclick="addMoney(50000)">5만원</button>
                          <button type="button" class="btn btn-sm font-bold btn-wallet-amount amountBtn" onclick="addMoney(100000)">10만원</button>
                          <button type="button" class="btn btn-sm font-bold btn-wallet-amount amountBtn" onclick="addMoney(500000)">50만원</button>
                          <button type="button" class="btn btn-sm font-bold btn-wallet-amount amountBtn" onclick="addMoney(1000000)">100만원</button>
                          <button type="button" class="btn btn-sm font-bold btn-wallet-clear font-white border-grey amountBtn ng-scope" onclick="resetMoney()" translate="">Clear</button>
                        </span>
                      </div>
                    </fieldset>
                  </div>
                  <div class="alert alert-danger">
                    <p>
                      <strong translate="" class="ng-scope">입금자명과 출금자명이 다를경우 본인확인 요청이 있을 수 있습니다</strong>
                    </p>
                  </div>
                  <div class="text-center">
                    <button type="button" class="btn btn-theme btn-yellow btn-block processBtn ng-scope btn-exchange" onclick="withdrawRequest();">출금하기</button>
                  </div>
                </form>
              </div>
            <font size="" color="#ff00ff"><b>*최근 1주일</b></font>
              <table class="history-table table" id="mywithdraw">
                  
              </table>
            </div>

            <div id="change-password-set" class="ngdialog-main-default__content font-white ng-scope ng-hide">
              <div class="change-password">
                  <fieldset>
                    <div class="alert alert-danger">
                      <p>
                        <strong translate="" class="ng-scope">관리자에 문의하세요</strong>
                      </p>
                    </div>
                  </fieldset>
              </div>
            </div>
          </div>
          <div class="ngdialog-close"></div>
        </div>
      </div>