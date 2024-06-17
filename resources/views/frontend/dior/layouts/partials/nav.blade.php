
    <div data-v-556f8885="" data-v-525011a5="" class="navigation-list column">
        <div data-v-556f8885="" class="logo-wrap row" style="flex-direction: row;">
            <a data-v-556f8885="" href="/" aria-current="page" class="router-link-exact-active router-link-active">
                <img src="/frontend/{{$logo}}/logo/{{$logo}}.png"> 
            </a>
        </div> 
        <div data-v-556f8885="" class="nav column slideInLeft">
            <div data-v-556f8885="" class="division row" style="flex-direction: row; margin-bottom: 20px; margin-top: 0px;"></div> 
            <a data-v-556f8885="" href="#" class="" onclick="showContent('live_content');">
                <img data-v-556f8885="" src="/frontend/dove/assets/img/casino.bb962cb.svg" class="margin-right-10" style="width: 22px; height: 22px;"> 
                <div data-v-556f8885="" class="column">
                    <span data-v-556f8885="" class="text-level-7 title-text text">라이브카지노</span> 
                    <span data-v-556f8885="" class="text-level-11 text" style="opacity: 0.6;">Live Casino</span>
                </div>
            </a> 
            <a data-v-556f8885="" href="#" class="" onclick="showContent('slot_content');">
                <img data-v-556f8885="" src="/frontend/dove/assets/img/slot.c14a0e0.svg" class="margin-right-10" style="width: 22px; height: 22px;"> 
                <div data-v-556f8885="" class="column">
                    <span data-v-556f8885="" class="text-level-7 title-text text">슬롯머신</span> 
                    <span data-v-556f8885="" class="text-level-11 text" style="opacity: 0.6;">Slot Games</span>
                </div>
            </a> 
            <!-- <a data-v-556f8885="" href="#" class="" onclick=
                @auth
                {{$isSports = false}}
                    @foreach($categories AS $index=>$category)
                        @if ($category->type =='sports')
                            @if ($category->status == 0)
                                "swal2('점검중입니다', 'warning');"
                            @elseif ($category->view == 0)
                                "swal2('지원하지 않는 게임입니다.', 'warning');"
                            @else
                                "startGameByProvider('bti', 'sports')"
                            @endif 
                                {{$isSports = true}} 
                                @break
                        @endif
                    @endforeach
                    @if(!$isSports)
                        "swal2('지원하지 않는 게임입니다.', 'warning');"
                    @endif
                @else "swal2('로그인후 이용가능합니다.', 'error')" @endif>
                <img data-v-556f8885="" src="/frontend/dove/assets/img/sports.png" class="margin-right-10" style="width: 22px; height: 22px;"> 
                <div data-v-556f8885="" class="column">
                    <span data-v-556f8885="" class="text-level-7 title-text text">스포츠</span> 
                    <span data-v-556f8885="" class="text-level-11 text" style="opacity: 0.6;">Sports</span>
                </div>
            </a>  -->
            <a data-v-556f8885="" href="#" class="" onclick=@auth
            @php
            $isMini = false;
            @endphp
                @foreach($categories AS $index=>$category)
                    @if ($category->type =='pball')
                        @if ($category->view == 0)
                            "swal2('지원하지 않는 게임입니다.', 'warning');"
                        @elseif ($category->status == 0)
                            "swal2('점검중입니다', 'warning');"
                        @else
                            "slotGame('{{$category->href}}','{{$category->trans?$category->trans->trans_title:$category->title}}');"
                        @endif
                            @php
                                $isMini = true;
                            @endphp
                        @break
                    @endif
                @endforeach
                @if(!$isMini)
                    "swal2('지원하지 않는 게임입니다.', 'warning');"
                @endif
            @else "swal2('로그인후 이용가능합니다.', 'error')" @endif>
                <img data-v-556f8885="" src="/frontend/dove/assets/img/pball.png" class="margin-right-10" style="width: 22px; height: 22px;">
                <div data-v-556f8885="" class="column">
                    <span data-v-556f8885="" class="text-level-7 title-text text">파워볼</span> 
                    <span data-v-556f8885="" class="text-level-11 text" style="opacity: 0.6;">Mini Game</span>
                </div>
            </a> 
            <div data-v-556f8885="" class="division row" style="flex-direction: row; margin-bottom: 20px;"></div> 
            <a data-v-556f8885="" href="#" class="" onclick=@auth 'openDepositModal(<?php echo json_encode(mb_substr(auth()->user()->recommender, 0, 2)); ?>);' @else "swal2('로그인후 이용가능합니다.', 'error')" @endif>
            <img data-v-e77ddfb0="" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IS0tIFVwbG9hZGVkIHRvOiBTVkcgUmVwbywgd3d3LnN2Z3JlcG8uY29tLCBHZW5lcmF0b3I6IFNWRyBSZXBvIE1peGVyIFRvb2xzIC0tPgo8c3ZnIHdpZHRoPSI4MDBweCIgaGVpZ2h0PSI4MDBweCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8cGF0aCBkPSJNMTkgOFY4QzIwLjEwNDYgOCAyMSA4Ljg5NTQzIDIxIDEwVjE4QzIxIDE5LjEwNDYgMjAuMTA0NiAyMCAxOSAyMEg2QzQuMzQzMTUgMjAgMyAxOC42NTY5IDMgMTdWN0MzIDUuMzQzMTUgNC4zNDMxNSA0IDYgNEgxN0MxOC4xMDQ2IDQgMTkgNC44OTU0MyAxOSA2VjhaTTE5IDhIN00xNyAxNEgxNiIgc3Ryb2tlPSIjZmZmZmZmIiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPgo8L3N2Zz4=" class="margin-right-10" style="width: 22px; height: 22px;">
                <div data-v-556f8885="" class="column">
                    <span data-v-556f8885="" class="text-level-7 title-text text">입금신청</span> 
                    <span data-v-556f8885="" class="text-level-11 text" style="opacity: 0.6;">Deposit</span>
                </div>
            </a> 
            <a data-v-556f8885="" href="#" class="" onclick=@auth 'openWithdrawModal(<?php echo json_encode(number_format(auth()->user()->balance)); ?>);' @else "swal2('로그인후 이용가능합니다.', 'error')" @endif>
                <img data-v-556f8885="" src="/frontend/dove/assets/img/withdraw-icon.9e0a7e8.svg" class="margin-right-10" style="width: 22px; height: 22px;"> 
                <div data-v-556f8885="" class="column">
                    <span data-v-556f8885="" class="text-level-7 title-text text">출금신청</span> 
                    <span data-v-556f8885="" class="text-level-11 text" style="opacity: 0.6;">Withdraw</span>
                </div>
            </a> 
            <div data-v-556f8885="" class="division row" style="flex-direction: row; margin-bottom: 20px;"></div> 
            <a data-v-556f8885="" href="#" class="" onclick=@auth "openNoticePanel()" @else "swal2('로그인후 이용가능합니다.', 'error')" @endif>
                <img data-v-556f8885="" src="/frontend/dove/assets/img/notice.d792102.svg" class="margin-right-10" style="width: 22px; height: 22px;"> 
                <div data-v-556f8885="" class="column">
                    <span data-v-556f8885="" class="text-level-7 title-text text">공지사항</span> 
                    <span data-v-556f8885="" class="text-level-11 text" style="opacity: 0.6;">Notice</span>
                </div>
            </a> 
            <a data-v-556f8885="" href="#" class="" onclick=@auth "openRequestPop()" @else "swal2('로그인후 이용가능합니다.', 'error')" @endif>
                <img data-v-556f8885="" src="/frontend/dove/assets/img/livechat.7cf9419.svg" class="margin-right-10" style="width: 22px; height: 22px;"> 
                <div data-v-556f8885="" class="column">
                    <span data-v-556f8885="" class="text-level-7 title-text text">1:1문의</span> 
                    <span data-v-556f8885="" class="text-level-11 text" style="opacity: 0.6;">Inquiry</span>
                </div>
            </a> 
            <div data-v-556f8885="" class="division row" style="flex-direction: row; margin-bottom: 20px;"></div> 
        </div> <!----> 
        <a data-v-556f8885="" href="{{ $telegram }}" target=@if($telegram=="#")""@else"_blank"@endif class="telegram">
            <img data-v-556f8885="" src="/frontend/dove/assets/img/telegram.png">
        </a> 
    </div>
