<div data-v-e77ddfb0="" data-v-525011a5="" class="header column">
    @auth()
    <div data-v-e77ddfb0="" class="header-container row" style="flex-direction: row;">
        <div data-v-e77ddfb0="" class="amount row" style="flex-direction: row;">
            <span data-v-e77ddfb0="" class="text" style="margin-right: 5px; opacity: 0.7;">보유금액
                <span data-v-e77ddfb0="" class="text" style="opacity: 0.7; margin-left: 5px;">₩</span>
            </span> 
            <span data-v-e77ddfb0="" class="cash text">{{number_format(auth()->user()->balance,0)}}</span>
        </div> 
        <div data-v-e77ddfb0="" class="quick-btn row" style="flex-direction: row;">
            <div data-v-5290ad82="" data-v-e77ddfb0="" class="row" style="flex-direction: row;">
                <div class="row" style="width: 100%; flex-direction: row;">
                <button data-v-e77ddfb0="" class="deposit button" onclick='openDepositModal(<?php echo json_encode(mb_substr(auth()->user()->recommender, 0, 2)); ?>);'>
                    <span data-v-e77ddfb0="" class="text">
                    <img data-v-e77ddfb0="" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IS0tIFVwbG9hZGVkIHRvOiBTVkcgUmVwbywgd3d3LnN2Z3JlcG8uY29tLCBHZW5lcmF0b3I6IFNWRyBSZXBvIE1peGVyIFRvb2xzIC0tPgo8c3ZnIHdpZHRoPSI4MDBweCIgaGVpZ2h0PSI4MDBweCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8cGF0aCBkPSJNMTkgOFY4QzIwLjEwNDYgOCAyMSA4Ljg5NTQzIDIxIDEwVjE4QzIxIDE5LjEwNDYgMjAuMTA0NiAyMCAxOSAyMEg2QzQuMzQzMTUgMjAgMyAxOC42NTY5IDMgMTdWN0MzIDUuMzQzMTUgNC4zNDMxNSA0IDYgNEgxN0MxOC4xMDQ2IDQgMTkgNC44OTU0MyAxOSA2VjhaTTE5IDhIN00xNyAxNEgxNiIgc3Ryb2tlPSIjZmZmZmZmIiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPgo8L3N2Zz4=" class="margin-right-5" style="width: 16px; height: 16px;" >입금 </span>
                </button>
                </div>
                <!---->
            </div>
            <div data-v-43e90682="" data-v-e77ddfb0="" class="row" style="flex-direction: row;">
                <div class="row" style="width: 100%; flex-direction: row;" onclick='openWithdrawModal(<?php echo json_encode(number_format(auth()->user()->balance)); ?>);'>
                <button data-v-e77ddfb0="" class="withdraw button">
                    <span data-v-e77ddfb0="" class="text">
                    <img data-v-e77ddfb0="" src="/frontend/dove/assets/img/withdraw-icon.9e0a7e8.svg" class="margin-right-5" style="width: 16px; height: 16px;" >출금 </span>
                </button>
                </div>
                <!---->
            </div>
        </div>
        <div data-v-e77ddfb0="" class="spacer"></div> 
        <div data-v-e77ddfb0="" class="account row" style="flex-direction: row;">
            <button data-v-e77ddfb0="" class="button text row" style="background: transparent;" onclick="openRequestPop()">
                <span data-v-e77ddfb0="" class="text">
                    <img id="letterimg" src="/frontend/todayslot/images/letter_on.gif" width="18" height="18" border="0">
                </span>
                <span data-v-e77ddfb0="" class="text" style="margin-right: 10px;">{{$unreadmsg}}</span>
            </button> 
            <div data-v-e77ddfb0="" class="dropdown">
                <button data-v-e77ddfb0="" class="button text" style="background: transparent;" onclick="showProfile()">
                    <div data-v-e77ddfb0="" class="user-info row">
                        <span data-v-e77ddfb0="" class="text">{{auth()->user()->username}}</span>
                    </div> 
                    <div data-v-e77ddfb0="" class="user-icon row" style="flex-direction: row;">
                        <span data-v-e77ddfb0="" class="text">
                            <img data-v-e77ddfb0="" src="/frontend/dove/assets/img/1.6781ad4.png" style="height: 18px;">
                        </span> 
                        <span data-v-e77ddfb0="" class="margin-left-10 text">
                            <i data-v-e56d064c="" data-v-e77ddfb0="" class="fa-solid fa-chevron-down"></i>
                        </span>
                    </div>
                </button> 
                <div class="dropdown-menu show" id="profile-drop" style="display: none;">
                    <div class="column" style="width: 100%;">
                        <div data-v-3b098160="" data-v-e77ddfb0="" class="card column">
                            <div data-v-3b098160="" class="account-detail column">
                                <div data-v-3b098160="" class="user-name row" style="flex-direction: row;">
                                    <div data-v-3b098160="" class="level-wrap row" style="flex-direction: row;">
                                        <img data-v-3b098160="" src="/frontend/dove/assets/img/1.6781ad4.png" style="height: 18px;">
                                    </div> 
                                    <div data-v-3b098160="" class="row" style="flex-direction: row; flex-grow: 2;">
                                        <div data-v-3b098160="" class="column" style="align-items: flex-start;">
                                            <span data-v-3b098160="" class="text-level-6 text">{{auth()->user()->username}}</span> 
                                        </div> 
                                        <div data-v-3b098160="" class="spacer"></div> 
                                        <div data-v-3b098160="" class="logout column" style="justify-content: center;">
                                            <button data-v-3b098160="" class="logout-btn button text" style="background: transparent;" onclick="logOut();">
                                                <span data-v-3b098160="" class="text" style="font-weight: bold;">
                                                    <i data-v-e56d064c="" data-v-3b098160="" class="margin-right-5 fa-regular fa-right-from-bracket"></i>로그아웃
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div> 
                                <div data-v-3b098160="" class="money-detail row" style="flex-direction: row; margin-top: 0px;">
                                    <div data-v-3b098160="" class="column">
                                        <span data-v-3b098160="" class="text">보유금액</span> 
                                        <span data-v-3b098160="" class="text">{{number_format(auth()->user()->balance,0)}} 원</span>
                                    </div> 
                                    <div data-v-3b098160="" class="column">
                                        <span data-v-3b098160="" class="text">보유포인트</span> 
                                        <span data-v-3b098160="" class="text">{{number_format(auth()->user()->deal_balance,0)}}</span>
                                    </div>
                                </div>
                            </div> 
                            <div data-v-3b098160="" class="account-navigation column">
                                <div data-v-3b098160="" class="row" style="flex-direction: row;">
                                    <div data-v-3b098160="" class="row" style="flex-direction: row;">
                                        <div data-v-5290ad82="" data-v-3b098160="" class="row" style="flex-direction: row;">
                                            <div class="row" style="width: 100%; flex-direction: row;">
                                                <button data-v-3b098160="" class="deposit button" onclick='openDepositModal(<?php echo json_encode(mb_substr(auth()->user()->recommender, 0, 2)); ?>);'>
                                                    <span data-v-3b098160="" class="text" style="display: table-cell;">
                                                        <img data-v-3b098160="" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IS0tIFVwbG9hZGVkIHRvOiBTVkcgUmVwbywgd3d3LnN2Z3JlcG8uY29tLCBHZW5lcmF0b3I6IFNWRyBSZXBvIE1peGVyIFRvb2xzIC0tPgo8c3ZnIHdpZHRoPSI4MDBweCIgaGVpZ2h0PSI4MDBweCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8cGF0aCBkPSJNMTkgOFY4QzIwLjEwNDYgOCAyMSA4Ljg5NTQzIDIxIDEwVjE4QzIxIDE5LjEwNDYgMjAuMTA0NiAyMCAxOSAyMEg2QzQuMzQzMTUgMjAgMyAxOC42NTY5IDMgMTdWN0MzIDUuMzQzMTUgNC4zNDMxNSA0IDYgNEgxN0MxOC4xMDQ2IDQgMTkgNC44OTU0MyAxOSA2VjhaTTE5IDhIN00xNyAxNEgxNiIgc3Ryb2tlPSIjZmZmZmZmIiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPgo8L3N2Zz4=" class="margin-right-5" style="width: 16px; height: 16px;">입금신청
                                                    </span>
                                                </button>
                                            </div> <!---->
                                        </div>
                                    </div> 
                                    <div data-v-3b098160="" class="row" style="flex-direction: row;">
                                        <div data-v-43e90682="" data-v-3b098160="" class="row" style="flex-direction: row;">
                                            <div class="row" style="width: 100%; flex-direction: row;">
                                                <button data-v-3b098160="" class="withdrawal button" onclick='openWithdrawModal(<?php echo json_encode(number_format(auth()->user()->balance)); ?>);'>
                                                    <span data-v-3b098160="" class="text" style="display: table-cell;">
                                                        <img data-v-3b098160="" src="/frontend/dove/assets/img/withdraw-icon.9e0a7e8.svg" class="margin-right-5" style="width: 16px; height: 16px;">출금신청
                                                    </span>
                                                </button>
                                            </div> <!---->
                                        </div>
                                    </div>
                                </div> 
                                <div data-v-3b098160="" class="row" style="flex-direction: row;">
                                    <div data-v-3b098160="" class="row" style="flex-direction: row;">
                                        <div data-v-1f1727bf="" data-v-3b098160="" class="row" style="flex-direction: row;">
                                            <div class="row" style="width: 100%; flex-direction: row;">
                                                <button data-v-3b098160="" class="exchange button" onclick="PointToMoney();">
                                                    <span data-v-3b098160="" class="text" style="display: table-cell;">
                                                        <img data-v-3b098160="" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IS0tIFVwbG9hZGVkIHRvOiBTVkcgUmVwbywgd3d3LnN2Z3JlcG8uY29tLCBHZW5lcmF0b3I6IFNWRyBSZXBvIE1peGVyIFRvb2xzIC0tPgo8c3ZnIHdpZHRoPSI4MDBweCIgaGVpZ2h0PSI4MDBweCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8cGF0aCBkPSJNOCAxMEgyMEwxNiA2IiBzdHJva2U9IiNmZmZmZmYiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+CjxwYXRoIGQ9Ik0xNiAxNEw0IDE0TDggMTgiIHN0cm9rZT0iI2ZmZmZmZiIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiLz4KPC9zdmc+" class="margin-right-5" style="width: 16px; height: 16px;">포인트전환
                                                    </span>
                                                </button>
                                            </div> <!---->
                                        </div>
                                    </div> 
                                    <div data-v-3b098160="" class="row" style="flex-direction: row;">
                                        <a data-v-3b098160="" href="#" class="" onclick="openProfileModal('{{auth()->user()->username}}', '{{number_format(auth()->user()->balance,0)}}', '{{number_format(auth()->user()->deal_balance,0)}}');">
                                            <span data-v-3b098160="" class="text" style="display: table-cell;">
                                                <img data-v-3b098160="" src="/frontend/dove/assets/img/mypage-icon.6ac2d7a.svg" class="margin-right-5" style="width: 16px; height: 16px;">마이페이지
                                            </span>
                                        </a>
                                    </div>
                                </div> 
                                <div data-v-3b098160="" class="row" style="flex-direction: row;">
                                    <div data-v-3b098160="" class="row" style="flex-direction: row;">
                                        <div data-v-47d63ae2="" data-v-3b098160="" class="row" style="flex-direction: row;">
                                            <div class="row" style="width: 100%; flex-direction: row;">
                                                <button data-v-3b098160="" class="message button" onclick="openRequestPop();">
                                                    <span data-v-3b098160="" class="text" style="display: table-cell;">
                                                        <img data-v-3b098160="" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IS0tIFVwbG9hZGVkIHRvOiBTVkcgUmVwbywgd3d3LnN2Z3JlcG8uY29tLCBHZW5lcmF0b3I6IFNWRyBSZXBvIE1peGVyIFRvb2xzIC0tPgo8c3ZnIGZpbGw9IiNmZmZmZmYiIHdpZHRoPSI4MDBweCIgaGVpZ2h0PSI4MDBweCIgdmlld0JveD0iMCAwIDE5MjAgMTkyMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KICAgIDxwYXRoIGQ9Ik0wIDE2OTQuMjM1aDE5MjBWMjI2SDB2MTQ2OC4yMzVaTTExMi45NDEgMzc2LjY2NFYzMzguOTRIMTgwNy4wNnYzNy43MjNMOTYwIDExMTEuMjMzbC04NDcuMDU5LTczNC41N1pNMTgwNy4wNiA1MjYuMTk4djk1MC41MTNsLTM1MS4xMzQtNDM4Ljg5LTg4LjMyIDcwLjQ3NSAzNzguMzUzIDQ3Mi45OThIMTc0LjA0MmwzNzguMzUzLTQ3Mi45OTgtODguMzItNzAuNDc1LTM1MS4xMzQgNDM4Ljg5VjUyNi4xOThMOTYwIDEyNjAuNzY4bDg0Ny4wNTktNzM0LjU3WiIgZmlsbC1ydWxlPSJldmVub2RkIi8+Cjwvc3ZnPg==" class="margin-right-5" style="width: 16px; height: 16px;">메세지
                                                    
                                                    </span>
                                                </button>
                                            </div> <!---->
                                        </div>
                                    </div> 
                                    
                                    <div data-v-3b098160="" class="row" style="flex-direction: row;">
                                        <div data-v-db5cba90="" data-v-3b098160="" class="row" style="flex-direction: row;">
                                            <div class="row" style="width: 100%; flex-direction: row;">
                                                <button data-v-3b098160="" onclick="openBetHistoryPanel();">
                                                    <span data-v-3b098160="" class="text" style="display: table-cell;">
                                                        <img data-v-3b098160="" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IS0tIFVwbG9hZGVkIHRvOiBTVkcgUmVwbywgd3d3LnN2Z3JlcG8uY29tLCBHZW5lcmF0b3I6IFNWRyBSZXBvIE1peGVyIFRvb2xzIC0tPgo8c3ZnIHdpZHRoPSI2MHB4IiBoZWlnaHQ9IjYwcHgiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTYuNzMgMTkuN0M3LjU1IDE4LjgyIDguOCAxOC44OSA5LjUyIDE5Ljg1TDEwLjUzIDIxLjJDMTEuMzQgMjIuMjcgMTIuNjUgMjIuMjcgMTMuNDYgMjEuMkwxNC40NyAxOS44NUMxNS4xOSAxOC44OSAxNi40NCAxOC44MiAxNy4yNiAxOS43QzE5LjA0IDIxLjYgMjAuNDkgMjAuOTcgMjAuNDkgMTguMzFWNy4wNEMyMC41IDMuMDEgMTkuNTYgMiAxNS43OCAySDguMjJDNC40NCAyIDMuNSAzLjAxIDMuNSA3LjA0VjE4LjNDMy41IDIwLjk3IDQuOTYgMjEuNTkgNi43MyAxOS43WiIgc3Ryb2tlPSIjZmZmZmZmIiBzdHJva2Utd2lkdGg9IjEuNSIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+Cjwvc3ZnPg==" class="margin-right-5" style="width: 16px; height: 16px;">베팅로그
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div data-v-e77ddfb0="" class="header-container row" style="flex-direction: row;">
        <button data-v-e77ddfb0="" class="navigation-btn button" onclick="openNav()">
            <i data-v-e56d064c="" data-v-e77ddfb0="" class="fa-regular fa-bars fa-2x"></i>
        </button> 
        <div data-v-e77ddfb0="" class="spacer"></div> 
        @auth()
        <div data-v-e77ddfb0="" class="row" style="flex-direction: row;">
            <a data-v-e77ddfb0="" href="/" aria-current="page" class="router-link-exact-active router-link-active">
                <img src="/frontend/{{$logo}}/logo/{{$logo}}.png"> 
            </a>
        </div> 
        <div data-v-e77ddfb0="" class="spacer"></div>
        @else
        <div data-v-e77ddfb0="" class="row" style="flex-direction: row;">
            <div data-v-6194c674="" data-v-e77ddfb0="" class="row" style="flex-direction: row;">
                <div class="row" style="width: 100%; flex-direction: row;">
                    <button data-v-e77ddfb0="" class="signin button text" style="background: transparent;" onclick='openLoginModal("{{$logo}}",<?php echo json_encode(\VanguardLTE\User::$values["banks"]); ?>);'>
                        <span data-v-e77ddfb0="" class="text">로그인</span>
                    </button>
                </div> <!---->
            </div> 
            <div data-v-06860046="" data-v-e77ddfb0="" class="row" style="flex-direction: row;">
                <div class="row" style="width: 100%; flex-direction: row;">
                    <button data-v-e77ddfb0="" class="signup button" onclick='openRegisterModal("{{$logo}}",<?php echo json_encode(\VanguardLTE\User::$values["banks"]); ?>);'>
                        <span data-v-e77ddfb0="" class="text">회원가입</span>
                    </button>
                </div> <!---->
            </div>
        </div>
        @endif
    </div>
</div> 

<!-- <?php
	$banks = \VanguardLTE\User::$values['banks'];
?> -->


    