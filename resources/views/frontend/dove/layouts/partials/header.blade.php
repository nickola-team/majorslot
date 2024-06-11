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
            <div data-v-e77ddfb0="" class="dropdown">
                <button data-v-e77ddfb0="" class="button text" style="background: transparent;" onclick="showProfile()">
                    <div data-v-e77ddfb0="" class="user-info column">
                        <div data-v-e77ddfb0="" class="row" style="flex-direction: row;">
                            <span data-v-e77ddfb0="" class="text">{{auth()->user()->username}}</span>
                        </div>
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
                                                    <span data-v-e77ddfb0="" class="text" style="margin-left: 5px;">{{$unreadmsg}}</span>
                                                    </span>
                                                </button>
                                            </div> <!---->
                                        </div>
                                    </div> 
                                    <!--
                                    <div data-v-3b098160="" class="row" style="flex-direction: row;">
                                        <div data-v-db5cba90="" data-v-3b098160="" class="row" style="flex-direction: row;">
                                            <div class="row" style="width: 100%; flex-direction: row;">
                                                <button data-v-3b098160="">
                                                    <span data-v-3b098160="" class="text" style="display: table-cell;">
                                                        <img data-v-3b098160="" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IS0tIFVwbG9hZGVkIHRvOiBTVkcgUmVwbywgd3d3LnN2Z3JlcG8uY29tLCBHZW5lcmF0b3I6IFNWRyBSZXBvIE1peGVyIFRvb2xzIC0tPgo8c3ZnIHdpZHRoPSI2MHB4IiBoZWlnaHQ9IjYwcHgiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTYuNzMgMTkuN0M3LjU1IDE4LjgyIDguOCAxOC44OSA5LjUyIDE5Ljg1TDEwLjUzIDIxLjJDMTEuMzQgMjIuMjcgMTIuNjUgMjIuMjcgMTMuNDYgMjEuMkwxNC40NyAxOS44NUMxNS4xOSAxOC44OSAxNi40NCAxOC44MiAxNy4yNiAxOS43QzE5LjA0IDIxLjYgMjAuNDkgMjAuOTcgMjAuNDkgMTguMzFWNy4wNEMyMC41IDMuMDEgMTkuNTYgMiAxNS43OCAySDguMjJDNC40NCAyIDMuNSAzLjAxIDMuNSA3LjA0VjE4LjNDMy41IDIwLjk3IDQuOTYgMjEuNTkgNi43MyAxOS43WiIgc3Ryb2tlPSIjZmZmZmZmIiBzdHJva2Utd2lkdGg9IjEuNSIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+Cjwvc3ZnPg==" class="margin-right-5" style="width: 16px; height: 16px;">베팅로그
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    -->
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
                <svg data-v-e77ddfb0="" xmlns="http://www.w3.org/2000/svg" width="120px" height="40px" viewBox="0 0 175 60" version="1.1">
                    <g id="#fdd000ff">
                        <path fill="#fdd000" opacity="1.00" d=" M 18.86 0.00 L 19.18 0.00 C 27.32 4.94 34.66 12.48 36.56 22.11 C 37.25 24.94 36.16 28.09 37.75 30.69 C 38.67 29.12 39.61 27.56 40.55 26.01 C 46.12 33.80 47.48 45.02 41.98 53.21 C 43.76 44.98 43.12 34.91 35.52 29.74 C 36.51 20.32 28.72 12.50 20.88 8.58 C 20.49 5.66 19.93 2.75 18.86 0.00 Z"></path> 
                        <path fill="#fdd000" opacity="1.00" d=" M 15.09 30.50 C 19.28 35.37 25.69 37.13 30.30 41.43 C 32.09 41.04 33.90 40.58 35.74 40.87 C 36.05 41.21 36.69 41.89 37.00 42.23 C 33.59 40.97 30.99 43.77 29.27 46.31 C 29.02 44.11 28.06 41.99 26.06 40.88 C 21.82 38.17 16.42 35.90 15.09 30.50 Z"></path>
                    </g> 
                    <g id="#f08300ff">
                        <path fill="#f08300" opacity="1.00" d=" M 20.88 8.58 C 28.72 12.50 36.51 20.32 35.52 29.74 C 43.12 34.91 43.76 44.98 41.98 53.21 C 39.85 56.69 35.65 57.68 32.56 60.00 L 31.23 60.00 C 32.72 57.66 35.03 55.76 35.55 52.90 C 36.56 50.02 36.46 45.43 40.57 45.31 C 39.34 44.33 38.14 43.32 37.00 42.23 C 36.69 41.89 36.05 41.21 35.74 40.87 C 33.90 40.58 32.09 41.04 30.30 41.43 C 25.69 37.13 19.28 35.37 15.09 30.50 C 15.00 29.07 14.92 27.64 14.85 26.21 C 11.28 31.74 8.88 38.63 10.89 45.18 C 11.79 48.72 14.33 51.45 16.95 53.84 C 12.78 53.13 8.94 50.92 7.08 46.98 C 7.48 52.16 9.67 58.10 14.96 60.00 L 13.67 60.00 C 10.17 58.57 6.57 56.81 4.29 53.66 C -0.07 48.00 -0.30 39.94 2.57 33.57 C 5.41 27.07 11.22 22.60 14.79 16.56 C 15.26 18.58 15.74 20.60 16.27 22.60 C 19.67 18.78 21.13 13.63 20.88 8.58 Z"></path>
                    </g> 
                    <g id="#ffffffff">
                        <path fill="#ffffff" opacity="1.00" d=" M 58.14 17.81 C 56.80 16.01 57.25 12.09 59.97 12.04 C 66.66 12.25 73.76 10.95 80.05 13.88 C 86.47 16.78 91.00 23.46 91.32 30.50 C 93.73 26.28 98.14 23.31 103.01 22.89 C 108.29 22.52 113.60 22.28 118.90 22.53 C 121.60 22.63 123.71 24.65 125.31 26.65 C 128.69 31.29 131.10 36.63 134.91 40.96 C 137.46 43.64 141.47 42.76 144.05 40.65 C 141.35 44.74 139.14 49.24 135.65 52.73 C 133.26 55.02 129.31 53.96 127.61 51.37 C 125.21 47.84 123.42 43.93 121.46 40.15 C 121.05 47.32 115.05 53.45 107.92 54.06 C 99.87 55.51 91.04 50.20 89.62 41.91 C 86.94 49.00 79.59 53.63 72.15 53.96 C 68.72 54.05 65.30 54.02 61.87 53.99 C 59.29 54.12 57.15 51.47 57.50 48.97 C 57.61 41.37 57.26 33.76 57.67 26.18 C 59.30 23.68 63.11 23.99 64.74 26.29 C 64.81 31.85 64.67 37.42 64.79 42.97 C 64.63 45.24 66.57 47.47 68.93 47.03 C 73.98 47.44 79.21 45.01 81.77 40.57 C 85.36 34.56 84.12 25.90 78.40 21.62 C 74.07 18.13 68.17 18.80 62.98 18.77 C 61.35 18.65 59.39 19.17 58.14 17.81 M 102.18 32.20 C 97.24 34.84 97.67 43.29 103.05 45.13 C 107.59 47.04 112.80 42.95 112.18 38.10 C 112.34 33.10 106.47 29.73 102.18 32.20 Z"></path> 
                        <path fill="#ffffff" opacity="1.00" d=" M 157.76 22.71 C 165.41 19.96 174.30 26.14 175.00 34.22 L 175.00 36.60 C 174.49 39.36 172.60 42.09 169.64 42.45 C 166.20 42.75 162.70 42.80 159.26 42.42 C 155.34 41.50 155.11 35.13 158.96 33.94 C 161.25 32.80 163.31 34.86 165.26 35.75 C 165.38 35.13 165.61 33.89 165.73 33.26 C 162.80 29.59 156.69 30.32 154.50 34.39 C 151.73 38.54 154.99 44.69 159.91 44.94 C 163.37 45.87 168.27 43.60 170.56 47.23 C 172.58 49.78 170.23 53.90 167.12 53.91 C 164.39 54.09 161.65 54.06 158.92 53.89 C 152.99 53.41 147.69 49.23 145.49 43.76 C 146.57 40.38 148.82 37.52 150.45 34.40 C 152.86 30.49 154.81 26.24 157.76 22.71 Z"></path> 
                        <path fill="#ffffff" opacity="1.00" d=" M 141.15 23.20 C 145.33 22.09 149.73 22.66 154.00 22.72 C 152.38 26.13 150.85 29.67 148.32 32.52 C 145.92 35.18 141.15 34.63 139.29 31.60 C 137.40 28.92 138.34 24.86 141.15 23.20 Z"></path>
                    </g>
                </svg>
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


    