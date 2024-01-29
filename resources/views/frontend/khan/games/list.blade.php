@extends('frontend.khan.layouts.app')
@section('page-title', $title)

@section('content')

@if (session('status'))
    <script>
        alert('{{ session('status') }}');
    </script>
@endif
<!-- 팝업메시지 -->
@if ($notice != null)
<div class="modal fade in" id="liveperson" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document" style="padding-right: 16px; width: 1024px !important; margin-top: 58px;">
        <div class="modal-content noticecontent">
            <div class="modal-body noticebox">
                <?php echo $notice->content ?>
            </div>
            <div class="modal-footer noticefooter">
                <label >
                    <input type="checkbox" id="hide-today" style="margin-right: 5px;">오늘 하루동안 이 창을 열지 않음
                </label>
                &nbsp;&nbsp;
                <a style="color: red;font-weight:bold; cursor: pointer; margin-right: 10px;" id="banner-close">닫기</a>
            </div>
        </div>
        
    </div>
</div>
@endif
<!-- 퀵위치 랭킹-->
{{-- <div class="quick1_wrap">
    <div class="quick1_box">
        <div class="quick1_title"><img src="/frontend/khan/khan/images/today_withdraw.png"></div>
        <div class="quick1">
            <table cellpadding="0" cellspacing="0" width="260" class="myTable" id="realtimeWithdraw">
                <tr>
                    <td valign="bottom" style="height:40px"><span class="quick_font1">09-18</span></td>
                    <td valign="bottom"><span class="quick_font2">홍*완</span></td>
                    <td valign="bottom" align="right"><span class="quick_font3">sex***</span></td>
                    <td valign="bottom" align="right"><span class="quick_font5">1,000,000</span></td>
                </tr>
                <tr>
                    <td valign="bottom" style="height:40px"><span class="quick_font1">09-18</span></td>
                    <td valign="bottom"><span class="quick_font2">김*민</span></td>
                    <td valign="bottom" align="right"><span class="quick_font3">ttl***</span></td>
                    <td valign="bottom" align="right"><span class="quick_font5">250,000</span></td>
                </tr>
                <tr>
                    <td valign="bottom" style="height:40px"><span class="quick_font1">09-18</span></td>
                    <td valign="bottom"><span class="quick_font2">김*정</span></td>
                    <td valign="bottom" align="right"><span class="quick_font3">ksj***</span></td>
                    <td valign="bottom" align="right"><span class="quick_font5">1,970,000</span></td>
                </tr>
                <tr>
                    <td valign="bottom" style="height:40px"><span class="quick_font1">09-18</span></td>
                    <td valign="bottom"><span class="quick_font2">유*진</span></td>
                    <td valign="bottom" align="right"><span class="quick_font3">a10***</span></td>
                    <td valign="bottom" align="right"><span class="quick_font5">130,000</span></td>
                </tr>
                <tr>
                    <td valign="bottom" style="height:40px"><span class="quick_font1">09-18</span></td>
                    <td valign="bottom"><span class="quick_font2">이*민</span></td>
                    <td valign="bottom" align="right"><span class="quick_font3">nhm***</span></td>
                    <td valign="bottom" align="right"><span class="quick_font5">300,000</span></td>
                </tr>
                <tr>
                    <td valign="bottom" style="height:40px"><span class="quick_font1">09-18</span></td>
                    <td valign="bottom"><span class="quick_font2">이*호</span></td>
                    <td valign="bottom" align="right"><span class="quick_font3">ksm***</span></td>
                    <td valign="bottom" align="right"><span class="quick_font5">130,000</span></td>
                </tr>
                <tr>
                    <td valign="bottom" style="height:40px"><span class="quick_font1">09-18</span></td>
                    <td valign="bottom"><span class="quick_font2">류*용</span></td>
                    <td valign="bottom" align="right"><span class="quick_font3">bun***</span></td>
                    <td valign="bottom" align="right"><span class="quick_font5">430,000</span></td>
                </tr>
                <tr>
                    <td valign="bottom" style="height:40px"><span class="quick_font1">09-18</span></td>
                    <td valign="bottom"><span class="quick_font2">허*훈</span></td>
                    <td valign="bottom" align="right"><span class="quick_font3">zke***</span></td>
                    <td valign="bottom" align="right"><span class="quick_font5">350,000</span></td>
                </tr>
                <tr>
                    <td valign="bottom" style="height:40px"><span class="quick_font1">09-18</span></td>
                    <td valign="bottom"><span class="quick_font2">권*찬</span></td>
                    <td valign="bottom" align="right"><span class="quick_font3">ma1***</span></td>
                    <td valign="bottom" align="right"><span class="quick_font5">250,000</span></td>
                </tr>
            </table>
        </div>
    </div>
</div>

<div class="quick2_wrap">
    <div class="quick2_box">
        <div class="quick2_title"><img src="/frontend/khan/khan/images/today_deposit.png"></div>
        <div class="quick2">
            <table cellpadding="0" cellspacing="0" width="260" id="realtimeDeposit" class="myTable">
                <tr>
                    <td valign="bottom" style="height:40px"><span class="quick_font1">09-18</span></td>
                    <td valign="bottom"><span class="quick_font2">한*님</span></td>
                    <td valign="bottom" align="right"><span class="quick_font3">ymh***</span></td>
                    <td valign="bottom" align="right"><span class="quick_font5">410,000</span></td>
                </tr>
                <tr>
                    <td valign="bottom" style="height:40px"><span class="quick_font1">09-18</span></td>
                    <td valign="bottom"><span class="quick_font2">전*원</span></td>
                    <td valign="bottom" align="right"><span class="quick_font3">wjs***</span></td>
                    <td valign="bottom" align="right"><span class="quick_font5">100,000</span></td>
                </tr>
                <tr>
                    <td valign="bottom" style="height:40px"><span class="quick_font1">09-18</span></td>
                    <td valign="bottom"><span class="quick_font2">배*민</span></td>
                    <td valign="bottom" align="right"><span class="quick_font3">br0***</span></td>
                    <td valign="bottom" align="right"><span class="quick_font5">40,000</span></td>
                </tr>
                <tr>
                    <td valign="bottom" style="height:40px"><span class="quick_font1">09-18</span></td>
                    <td valign="bottom"><span class="quick_font2">윤*구</span></td>
                    <td valign="bottom" align="right"><span class="quick_font3">yhg***</span></td>
                    <td valign="bottom" align="right"><span class="quick_font5">500,000</span></td>
                </tr>
                <tr>
                    <td valign="bottom" style="height:40px"><span class="quick_font1">09-18</span></td>
                    <td valign="bottom"><span class="quick_font2">윤*기</span></td>
                    <td valign="bottom" align="right"><span class="quick_font3">whd***</span></td>
                    <td valign="bottom" align="right"><span class="quick_font5">300,000</span></td>
                </tr>
                <tr>
                    <td valign="bottom" style="height:40px"><span class="quick_font1">09-18</span></td>
                    <td valign="bottom"><span class="quick_font2">최*광</span></td>
                    <td valign="bottom" align="right"><span class="quick_font3">kca***</span></td>
                    <td valign="bottom" align="right"><span class="quick_font5">100,000</span></td>
                </tr>
                <tr>
                    <td valign="bottom" style="height:40px"><span class="quick_font1">09-18</span></td>
                    <td valign="bottom"><span class="quick_font2">배*민</span></td>
                    <td valign="bottom" align="right"><span class="quick_font3">br0***</span></td>
                    <td valign="bottom" align="right"><span class="quick_font5">40,000</span></td>
                </tr>
                <tr>
                    <td valign="bottom" style="height:40px"><span class="quick_font1">09-18</span></td>
                    <td valign="bottom"><span class="quick_font2">이*균</span></td>
                    <td valign="bottom" align="right"><span class="quick_font3">Sms***</span></td>
                    <td valign="bottom" align="right"><span class="quick_font5">100,000</span></td>
                </tr>
                <tr>
                    <td valign="bottom" style="height:40px"><span class="quick_font1">09-18</span></td>
                    <td valign="bottom"><span class="quick_font2">이*태</span></td>
                    <td valign="bottom" align="right"><span class="quick_font3">yig***</span></td>
                    <td valign="bottom" align="right"><span class="quick_font5">150,000</span></td>
                </tr>
            </table>
        </div>
    </div>
</div> --}}

<!-- 로그인 -->
<div class="login_wrap">
    <div class="login_box">
        @if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
            <div class="after_login">
                <div onclick="openMenu('myinfo');" class="menu_pop_open">
                <i class="fa fa-user"></i>
                    <p>{{ Auth::user()->username }}</p>
                </div>
                <div onclick="openMenu('moneyhistory');" class="menu_pop_open">
                    <i class="fas fa-won-sign"></i>
                    <p id="cur_money">{{ number_format(Auth::user()->balance,2) }} 원</p>
                </div>
                <div onclick="openMenu('bonusdeal');" class="menu_pop_open">
                    <i class="fas fa-won-sign"></i>
                    <p id="cur_deal">{{ number_format(Auth::user()->deal_balance,2) }} 원</p>
                </div>
                <div onclick="getBalance();">
                    <i class="fa fa-recycle"></i>
                </div>
                <div onclick="logoutProc();">
                    <i class="fa fa-ban"></i>
                    <p>로그아웃</p>
                </div>
            </div>
        @else
        <input type="hidden" value="<?= csrf_token() ?>" name="_token" id="_token">
            <div class="login">
                <ul>
                    <li>
                        <input type="text" name="username" id="username" class="input_login" placeholder="아이디">
                    </li>
                    <li>
                        <input type="password" name="password" id="password" class="input_login" placeholder="비밀번호">
                    </li>
                    <li>
                        <span class="login_btn1" onclick="loginProc();">로그인</span>
                    </li>
                    <li><a href="#" class="join_pop_open"><span class="login_btn2">회원가입</span></a></li>
                </ul>
            </div>
        @endif
    </div>
</div>
<!-- 잭팟 슬라이드 -->

<div class="jackpot_left_wrap">
    <div class="grand_jackpot_box">
        <p class="jackpot_sum" id="grandjp">10,000,000</p>
    </div>
    <div class="major_jackpot_box">
    <p class="jackpot_sum" id="majorjp">1,000,000</p>
    </div>
</div>
<div class="jackpot_right_wrap">
    <div class="minor_jackpot_box">
        <p class="jackpot_sum" id="minorjp">100,000</p>
    </div>
    <div class="mini_jackpot_box">
    <p class="jackpot_sum" id="minijp">10,000</p>
    </div>
</div>

<!-- 광고 슬라이드 -->

<div class="slideshow2_wrap">
    <div class="slideshow2_wrap_center">
        <div id="number_slideshow" class="number_slideshow">
            <ul>
                <li><a><img src="/frontend/khan/khan/images/slideshow1.jpg"></a></li>
                <li><a><img src="/frontend/khan/khan/images/slideshow1.jpg"></a></li>
<!--                <li><a><img src="/frontend/khan/khan/images/slideshow2.png"></a></li> -->
            </ul>
<!--            <ul class="number_slideshow_nav">
                <li><a href="#"></a></li>
                <li><a href="#"></a></li>
            </ul> -->
        </div>
    </div>
</div>

<!-- 인기게임 슬라이드 -->

<div class="hot_wrap">
    <div class="hot_box">
        <p class="title">
            <span>라이브 카지노</span>
        </p>
    </div>
</div>

<div class="hot_wrap">
    <div class="hot_box">
        <div class="carousel slide" id="myCarousel" style="background:black;padding:2px;">
            <div class="carousel-inner">
                <?php
                    if (isset($livegames) && count($livegames) % 4 > 0)
                    {
                        $len = count($livegames);
                        $remainCount = 4 - $len % 4;
                        for ($i=0;$i<$remainCount;$i++)
                        {
                            $livegames[] = $livegames[$i];
                        }
                    }
                ?>
                @forelse ($livegames as $hot)
                    @if ($loop->index % 4 == 0)
                    <div class="item {{$loop->index==0?'active':''}}">
                    @endif
                        <div class="col-xs-3">
                            <div class="parent" onclick="">
                            <div class="child">
                                @if (isset($hot['provider']))
                                    @if (isset($hot['icon']))
                                        <img src="{{ $hot['icon']}}" style="width: 100%; height: 100%;">
                                    @else
                                        <img src="/frontend/Default/ico/{{ $hot['provider']}}/{{ $hot['gamecode']}}_{{ $hot['name']}}.jpg" style="width: 100%; height: 100%;">
                                    @endif
                                    @if (Auth::check())
                                        <a href="#" onclick="startGameByProvider('{{$hot['provider']}}','{{$hot['gamecode']}}');">
                                    @else
                                        <a href="#none" onclick="Swal.fire('로그인 하여 주세요.');">
                                    @endif
                                    <p>{{$hot['title']}}</p>
                                    </a>
                                @else
                                    <img src="/frontend/Default/ico/{{ $hot['name']}}.jpg" style="width: 100%; height: 100%;">
                                    @if (Auth::check())
                                        <a href="#" onclick="startGame('{{$hot['name']}}');">
                                    @else
                                        <a href="#none" onclick="Swal.fire('로그인 하여 주세요.');">
                                    @endif
                                    <p>{{$hot['title']}}</p>
                                    </a>
                                    
                                @endif
                            </div>
                            </div>  
                        </div>
                    @if ($loop->index % 4 == 3)
                    </div>
                    @endif
                @empty
                    <p>인기게임이 없습니다</p>
                @endforelse
            </div>
            <a class="left carousel-control" href="#myCarousel" style="width:10px;" data-slide="prev"> <i class="glyphicon glyphicon-chevron-left"> </i> </a>
            <a class="right carousel-control" href="#myCarousel" style="width:10px;" data-slide="next"> <i class="glyphicon glyphicon-chevron-right"> </i> </a>
        </div>
    </div>
</div>

<!-- 공지사항 -->
{{--
<div class="notice_wrap">
    <div class="notice_box">
        <div class="notice">
            <MARQUEE id="NewsMarQuee" onmouseover="stop();" style="margin-top: 0px; width:1036px; height:36px;" onmouseout="start();" scrollAmount=6 scrollDelay=5 direction=left>
                <a style="cursor:pointer;margin-right:200px" onclick="return false;">
                    <span class="title">* 게임 도중 쿠폰 및 콤프 사용 제재안내...</span>
                </a>
                <a style="cursor:pointer;margin-right:200px" onclick="return false;">
                    <span class="title">* 허위충전 제재안내</span>
                </a>
                <a style="cursor:pointer;margin-right:200px" onclick="return false;">
                    <span class="title">* ans9***회원님 1800만원 게임내역...</span>
                </a>
                <a style="cursor:pointer;margin-right:200px" onclick="return false;">
                    <span class="title">* ans9***회원님 1800만원 축하드립니다....</span>
                </a>
                <a style="cursor:pointer;margin-right:200px" onclick="return false;">
                    <span class="title">* 주말시범이벤트 중단안내</span>
                </a>
            </MARQUEE>
        </div>
    </div>
</div>
--}}


<!--슬롯리스트-->
<div class="hot_wrap">
    <div class="hot_box">
        <p class="title">
            <span><strong>SLOT GAMES</strong></span>
        </p>
    </div>
</div>
<div class="main_slot_wrap">
    <div class="sc-inner">
    @if ($categories && count($categories))
        @foreach($categories AS $index=>$category)
                @if(!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check())
                <a href="javascript:;" onclick="openGroup('tab{{ $category->id }}', '{{ $category->href }}');" class="slot_pop_open slot-btn">
                        <div class="inner">
                            <div class="category">
                                <div class="gameicon">
                                    <img width="100%" height="65%" src="/frontend/khan/categories/{{ $category->title.'.jpg' }}" />
                                    <div style="background:black;">
                                    <span style="font-size:16px;">
                                    @if ($category->trans)
                                        {{ $category->trans->trans_title }}
                                    @else
                                        {{ $category->title }}
                                    @endif
                                    </span>
                                    </div>
                                </div>
                                <div class="gameborder">
                                    <img width="100%" height="100%" src="/frontend/khan/khan/images/border.png" />
                                </div>
                            </div>
                            
                        </div>
                    </a>
                @else
                    <a href="#none" onclick="Swal.fire('로그인 하여 주세요.');" class="slot-btn">
                        <div class="inner">
                            <div class="category">
                                <div class="gameicon">
                                    <img width="100%" height="65%" src="/frontend/khan/categories/{{ $category->title.'.jpg' }}" />
                                    <div style="background:black;">
                                        <span style="font-size:16px">
                                        @if ($category->trans)
                                            {{ $category->trans->trans_title }}
                                        @else
                                            {{ $category->title }}
                                        @endif
                                        </span>
                                    </div>
                                </div>
                                <div class="gameborder">
                                    <img width="100%" height="100%" src="/frontend/khan/khan/images/border.png" />
                                </div>
                            </div>
                            
                        </div>
                    </a>
                @endif
        @endforeach
        <?php
            $comingSoon = (intval((count($categories)-1)/4) + 1 ) * 4 - count($categories);
        ?>
        @for ($i=0;$i<$comingSoon;$i++)
            <a href="#none" onclick="Swal.fire('준비중입니다.');" class="slot-btn">
                <div class="inner">
                    <div class="category">
                        <div class="gameicon">
                            <img width="100%" height="65%" src="/frontend/khan/categories/coming_soon.png" />
                            <div style="background:black;">
                                <span style="font-size:16px;">
                                    준비중
                                </span>
                            </div>
                        </div>
                        <div class="gameborder">
                            <img width="100%" height="100%" src="/frontend/khan/khan/images/border.png" />
                        </div>
                    </div>
                    
                </div>
            </a>
        @endfor
    @endif
    </div>
</div>

@stop

@section('popup')
<!-- 회원가입 -->
<div id="join_pop" class="fadeIn222 popup_none">
    <div class="popup_wrap">
        <div class="close_box"><a href="#" class="join_pop_close"><img src="/frontend/khan/khan/images/popup_close.png"></a></div>
        <div class="popupbox">
            <form id="frm_join" name="frm_join">
                <input type="hidden" name="TOKEN" value="">
                <input type="hidden" name="CmdMode" value="92DEC5EABAB6BF16">
                <input type="hidden" name="gender" value="M">
                <div class="title1">
                    회원가입
                </div>
                <div class="contents_in_2">
                    <div class="con_box10">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="write_title_top">
                            <tbody>
                                <tr>
                                    <td class="write_title">아이디</td>
                                    <td class="write_td"></td>
                                    <td class="write_basic"><input class="input1" name="memberid" type="text" id="memberid" onblur="CheckId(this);"> <a href="javascript:fn_Check_id();"><span class="btn1_1">중복확인</span></a></td>
                                </tr>
                                <tr>
                                    <td class="write_title">이름</td>
                                    <td class="write_td"></td>
                                    <td class="write_basic"><input class="input1" name="membername" type="text" id="membername" onblur="CheckHangul(this);"><span> ( 입금과 출금시 사용하시는 실제 예금주명으로 기입하여 주시기 바랍니다 )</span></td>
                                </tr>
                                <tr>
                                    <td class="write_title">비밀번호</td>
                                    <td class="write_td"></td>
                                    <td class="write_basic"><input class="input1" name="memberpw" type="password" id="memberpw" placeholder="6 ~ 16자리 (영문, 숫자)"></td>
                                </tr>
                                <tr>
                                    <td class="write_title">비밀번호 확인</td>
                                    <td class="write_td"></td>
                                    <td class="write_basic"><input class="input1" name="confirmpw" type="password" id="confirmpw"></td>
                                </tr>
                                <tr>
                                    <td class="write_title">은행선택</td>
                                    <td class="write_td"></td>
                                    <td class="write_basic">
                                        <select class="input1" name="bankname" id="bankname" onchange="directBankName(this);">
                                            <option value="">은행명</option>
                                            <option value="직접입력">직접입력</option>
                                            <option value="카카오">카카오</option>
                                            <option value="카카오페이증권">카카오페이증권</option>
                                            <option value="K 뱅크">K 뱅크</option>
                                            <option value="우체국">우체국</option>
                                            <option value="새마을">새마을</option>
                                            <option value="국민">국민</option>
                                            <option value="신한">신한</option>
                                            <option value="우리">우리</option>
                                            <option value="씨티">씨티</option>
                                            <option value="제일">제일</option>
                                            <option value="농협">농협</option>
                                            <option value="신협">신협</option>
                                            <option value="수협">수협</option>
                                            <option value="경남">경남</option>
                                            <option value="광주">광주</option>
                                            <option value="대구">대구</option>
                                            <option value="부산">부산</option>
                                            <option value="전북">전북</option>
                                            <option value="제주">제주</option>
                                        </select>
                                        <span id="banknmId" style="display: none;"><input class="input1" name="banknm" type="text" id="banknm" maxlength="20" placeholder="은행명직접입력"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="write_title">계좌번호</td>
                                    <td class="write_td"></td>
                                    <td class="write_basic">
                                        <input class="input1" name="accountnumber" type="text" id="accountnumber" onkeypress="return digit_check(event)" placeholder="계좌번호를 입력하세요">
                                        <span>( 띄어쓰기와 - 없이 숫자로만 기입하여 주시기 바랍니다 )</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="write_title">핸드폰</td>
                                    <td class="write_td"></td>
                                    <td class="write_basic">
                                        <select class="input1" name="mobile" id="mobile">
                                            <option value="010" selected="">010</option>
                                            <option value="011">011</option>
                                            <option value="016">016</option>
                                            <option value="017">017</option>
                                            <option value="018">018</option>
                                            <option value="019">019</option>
                                        </select>
                                        - <input class="input1" size="8" type="tel" name="mobile2" id="mobile2" maxlength="4"> - <input class="input1" size="8" type="tel" name="mobile3" id="mobile3" maxlength="4">
                                        <a href="javascript:fn_PhoneCheck();"><span class="btn1_1">인증번호전송</span></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="write_title">인증번호</td>
                                    <td class="write_td"></td>
                                    <td class="write_basic"><input class="input1" name="anum" type="text" id="anum" maxlength="4" onkeypress="return digit_check(event)"></td>
                                </tr>
                                <tr>
                                    <td class="write_title">지인추천 ID</td>
                                    <td class="write_td"></td>
                                    <td class="write_basic">
                                        <input class="input1" name="recommend_id" type="text" id="recommend_id">
                                        <span>
                                            &nbsp;&nbsp;
                                            지인 ID가 없으면 입력 안하셔도 됩니다
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="write_title">추천코드</td>
                                    <td class="write_td"></td>
                                    <td class="write_basic">
                                        <input class="input1" name="recomcode_id" type="text" id="recomcode_id">
                                        <span>
                                            &nbsp;&nbsp;
                                            추천코드가 없으면 입력 안하셔도 됩니다
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="con_box10">
                        <div class="btn_wrap_center">
                            <ul>
                                <li><a href="#none" onclick="join_check('N');return false;"><span class="btn3_1">회원가입완료</span></a></li>
                            </ul>
                        </div>
                    </div>
                    <form id="frm_phone_auth" name="frm_phone_auth" method="post" action="">
                        <input type="hidden" name="token" value="">
                        <input type="hidden" name="CmdMode">
                        <input type="hidden" name="actionFlag" value="go">
                        <input type="hidden" name="rphone">
                        <input type="hidden" name="testflag" value="">
                        <input type="hidden" name="homeUrl" maxlength="4" value="345ha.com">
                    </form>
                    <iframe name="ifrm_phone_auth" src="about:blank" width="100%;" height="200px;" frameborder="0" scrolling="auto" style="display:none;border:1px solid red;"></iframe>
                </div>
            </form>
        </div>
    </div>
</div>

@if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
    <!-- My페이지, 입금신청, 출금신청, 입금/출금내역, 1:1문의, FAQ, 공지사항 -->
    <div id="menu_pop" class="fadeIn222 popup_none">
        <div class="popup_wrap">
            <div class="close_box"><a href="#" class="menu_pop_close"><img src="/frontend/khan/khan/images/popup_close.png"></a></div>
            <div class="popupbox">
                <div class="popup_tab_wrap">
                    <ul class="popup_tab">
                        <li class="myinfo"><a href="javascript:;" onclick="openMenu('myinfo');"><span>My페이지</span></a></li>
                        <li class="deposit"><a href="javascript:;" onclick="openMenu('deposit');"><span>충전신청</span></a></li>
                        <li class="withdraw"><a href="javascript:;" onclick="openMenu('withdraw');"><span>환전신청</span></a></li>
                        <li class="bonusdeal"><a href="javascript:;" onclick="openMenu('bonusdeal');"><span>보너스전환</span></a></li>
                        <li class="moneyhistory"><a href="javascript:;" onclick="openMenu('moneyhistory');"><span>충/환전내역</span></a></li>
                        <li class="memo"><a href="javascript:;" onclick="openMenu('memo');"><span>쪽지함</span></a></li>
                        <li class="question"><a href="javascript:;" onclick="openMenu('question');"><span>1:1문의</span></a></li>
                        <li class="faq"><a href="javascript:;" onclick="openMenu('faq');"><span>FAQ</span></a></li>
                        <li class="noti"><a href="javascript:;" onclick="openMenu('noti');"><span>공지사항</span></a></li>
                    </ul>
                </div>
                <div class="popup_content_wrap">
                    <div id="myinfo" class="content">
                        <div class="title1">
                            My페이지
                        </div>
                        <div class="con_box10">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="write_title_top">
                                <tr>
                                    <td class="write_title">아이디</td>
                                    <td class="write_td"></td>
                                    <td class="write_basic"><span>{{ Auth::user()->username }}</span></td>
                                </tr>
                                <tr>
                                    <td class="write_title">이름</td>
                                    <td class="write_td"></td>
                                    <td class="write_basic"><span>{{ Auth::user()->username }}</span></td>
                                </tr>
                                <tr>
                                    <td class="write_title">기존비밀번호</td>
                                    <td class="write_td"></td>
                                    <td class="write_basic"><input id="cur_pwd" class="input1" type="password" placeholder="6 ~ 16자리 (영문, 숫자)"></td>
                                </tr>
                                <tr>
                                    <td class="write_title">새비밀번호</td>
                                    <td class="write_td"></td>
                                    <td class="write_basic"><input id="new_pwd" class="input1" type="password" placeholder="6 ~ 16자리 (영문, 숫자)"></td>
                                </tr>
                                <tr>
                                    <td class="write_title">비밀번호 재입력</td>
                                    <td class="write_td"></td>
                                    <td class="write_basic"><input id="new_pwd_confirm" class="input1" type="password"></td>
                                </tr>
                                <tr>
                                    <td class="write_title">전화번호</td>
                                    <td class="write_td"></td>
                                    <td class="write_basic"><span>{{ Auth::user()->phone }}</span></td>
                                </tr>
                                <tr>
                                    <td class="write_title">은행명</td>
                                    <td class="write_td"></td>
                                    <td class="write_basic"><span></span></td>
                                </tr>
                                <tr>
                                    <td class="write_title">계좌번호</td>
                                    <td class="write_td"></td>
                                    <td class="write_basic"><span></span></td>
                                </tr>
                                <tr>
                                    <td class="write_title">예금주</td>
                                    <td class="write_td"></td>
                                    <td class="write_basic"><span></span></td>
                                </tr>
                            </table>
                        </div>
                        <div class="con_box10">
                            <div class="btn_wrap_center">
                                <ul>
                                    <li><button class="btn3_1" onclick="updateMyInfo();">수정하기</button></li>
                                    <li><button class="btn3_2 menu_pop_close">취소하기</button></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div id="deposit" class="content popup_none">
                        <div class="title1">
                        충전신청
                        </div>
                        <div class="con_box10">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="write_title_top">
                                <tr>
                                    <td class="write_title">회원정보</td>
                                    <td class="write_td"></td>
                                    <td class="write_basic"><span class="username"></span></td>
                                </tr>
                                <tr>
                                    <td class="write_title">입금자명</td>
                                    <td class="write_td"></td>
                                    <td class="write_basic"><input class="input1 refundname" type="text"  placeholder="실시간상담으로 신청" /></td>
                                </tr>
                                <tr>
                                    <td class="write_title">입금금액</td>
                                    <td class="write_td"></td>
                                    <td class="write_basic">
                                        <input class="tmp_money" type="hidden" value="0" />
                                        <table>
                                            <tr>
                                                <td>
                                                    <input class="money input1" type="text" value="0" placeholder="최소단위는 3만원이상입니다 (수표절대금지)" onKeyUp="changeMoney('deposit');numChk(this);" onChange="numChk(this);" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <button class="btn1_1" onclick="addMoney('deposit', 10000);">1만원</button>
                                                    <button class="btn1_1" onclick="addMoney('deposit', 30000);">3만원</button>
                                                    <button class="btn1_1" onclick="addMoney('deposit', 50000);">5만원</button>
                                                    <button class="btn1_1" onclick="addMoney('deposit', 100000);">10만원</button>
                                                    <button class="btn1_1" onclick="addMoney('deposit', 500000);">50만원</button>
                                                    <button class="btn1_1" onclick="resetMoney('deposit');">정정하기</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div style="margin-top: 5px; color: #ff0000; text-align: left;">※ 충전계좌 문의는 1:1문의에서 신청</div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="con_box10">
                            <div class="btn_wrap_center">
                                <ul>
                                    <li><button class="btn3_1" onclick="deposit();">충전하기</button></li>
                                    <li><button class="btn3_2 menu_pop_close">취소하기</button></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div id="withdraw" class="content popup_none">
                        <div class="title1">
                            환전신청
                        </div>
                        <div class="con_box10">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="write_title_top">
                                <tr>
                                    <td class="write_title">보유금액</td>
                                    <td class="write_td"></td>
                                    <td class="write_basic"><span class="cur_money">0 원</span></td>
                                </tr>
                                <tr>
                                    <td class="write_title">환전금액</td>
                                    <td class="write_td"></td>
                                    <td class="write_basic">
                                        <input class="tmp_money" type="hidden" value="0" />
                                        <table>
                                            <tr>
                                                <td>
                                                    <input class="money input1" type="text" value="0" placeholder="최소단위는 3만원이상입니다 (수표절대금지)" onKeyUp="changeMoney('withdraw');numChk(this);" onChange="numChk(this);" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <button class="btn1_1" onclick="addMoney('withdraw', 10000);">1만원</button>
                                                    <button class="btn1_1" onclick="addMoney('withdraw', 30000);">3만원</button>
                                                    <button class="btn1_1" onclick="addMoney('withdraw', 50000);">5만원</button>
                                                    <button class="btn1_1" onclick="addMoney('withdraw', 100000);">10만원</button>
                                                    <button class="btn1_1" onclick="addMoney('withdraw', 500000);">50만원</button>
                                                    <button class="btn1_1" onclick="resetMoney('withdraw');">정정하기</button>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="write_title">환전비밀번호</td>
                                    <td class="write_td"></td>
                                    <td class="write_basic"><input class="input1 refundpassword" type="password" value="" /></td>
                                </tr>
                            </table>
                        </div>
                        <div class="con_box10">
                            <div class="btn_wrap_center">
                                <ul>
                                    <li><button class="btn3_1" onclick="withdraw();">환전하기</button></li>
                                    <li><button class="btn3_2 menu_pop_close">취소하기</button></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div id="bonusdeal" class="content popup_none">
                        <div class="title1">
                            보너스전환
                        </div>
                        <div class="con_box10">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="write_title_top">
                                <tr>
                                    <td class="write_title">보너스금액</td>
                                    <td class="write_td"></td>
                                    <td class="write_basic"><span class="cur_deal" id="cur_deal">{{auth()->user()->deal_balance}} 원</span></td>
                                </tr>
                                <tr>
                                    <td class="write_title">전환금액</td>
                                    <td class="write_td"></td>
                                    <td class="write_basic">
                                        <input class="tmp_money" type="hidden" value="0" />
                                        <table>
                                            <tr>
                                                <td>
                                                    <input class="money input1" type="text" value="0" placeholder="최소단위는 3만원이상입니다 (수표절대금지)" onKeyUp="changeMoney('bonusdeal');numChk(this);" onChange="numChk(this);" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <button class="btn1_1" onclick="addMoney('bonusdeal', 10000);">1만원</button>
                                                    <button class="btn1_1" onclick="addMoney('bonusdeal', 30000);">3만원</button>
                                                    <button class="btn1_1" onclick="addMoney('bonusdeal', 50000);">5만원</button>
                                                    <button class="btn1_1" onclick="addMoney('bonusdeal', 100000);">10만원</button>
                                                    <button class="btn1_1" onclick="addMoney('bonusdeal', 500000);">50만원</button>
                                                    <button class="btn1_1" onclick="resetMoney('bonusdeal');">정정하기</button>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="con_box10">
                            <div class="btn_wrap_center">
                                <ul>
                                    <li><button class="btn3_1" onclick="convertDeal();">전환하기</button></li>
                                    <li><button class="btn3_2 menu_pop_close">취소하기</button></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div id="moneyhistory" class="content popup_none">
                        <div class="title1">
                        충/환전내역
                        </div>
                        <div class="con_box10">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td class="td_title">구분</td>
                                    <td class="td_title">금액</td>
                                    <td class="td_title">상태</td>
                                    <td class="td_title">요청날짜</td>
                                    <td class="td_title">처리날짜</td>
                                </tr>
                                <tbody class="list"></tbody>
                            </table>
                        </div>
                        <div class="con_box10">
                            <div class="btn_wrap_center">
                                <ul>
                                    <li><button class="btn3_1" onclick="delMoneyHistory();">충환전내역 전체삭제</button></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 슬롯리스트 -->
    <div id="slot_pop" class="fadeIn222 popup_none">
        <div class="popup_wrap">
            <div class="close_box"><a href="#" class="slot_pop_close"><img src="/frontend/khan/khan/images/popup_close.png"></a></div>
            <div class="popupbox">
                <div class="popup_tab_wrap">
                    <ul class="popup_tab">
                        @if ($categories && count($categories))

                            @foreach($categories AS $index=>$category)
                                
                                <li class="tab{{ $category->id }}">
                                    <a href="javascript:;" onclick="openGroup('tab{{ $category->id }}', '{{ $category->href }}');">
                                        <span>
                                        @if ($category->trans)
                                            {{ $category->trans->trans_title }}
                                        @else
                                            {{ $category->title }}
                                        @endif
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
                <div class="popup_content_wrap">
                </div>
            </div>
        </div>
    </div>

    <script>
    function startGame(gamename) {
        // alert(game_id);
        
        //location.href = "/game/" + gamename;
        window.open("/game/" + gamename, gamename, "width=1280, height=720, left=100, top=50");
        
    }
    function startGameByProvider(provider, gamecode) {
        var formData = new FormData();
        formData.append("provider", provider);
        formData.append("gamecode", gamecode);
        $.ajax({
        type: "POST",
        url: "/api/getgamelink",
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        async: false,
        success: function (data) {
            if (data.error) {
                alert(data.msg + data.data);
                return;
            }
            window.open(data.data.url, "game", "width=1280, height=720, left=100, top=50");
        }
        });
        
    }
    </script>
@endif
    <script>
        $( document ).ready(function() {
            $("#myCarousel").carousel({
                interval: 5000
            })
            $("#banner-close").click(function() {
                if ($("#hide-today").is(":checked") == true)
                {
                    @if (auth()->check())
                    $.cookie('hide-today_user', 'done', { expires: 1 });
                    @else
                    $.cookie('hide-today', 'done', { expires: 1 });
                    @endif
                }
                $('#liveperson').modal('hide');
            });
            @if (auth()->check())
            if ($.cookie('hide-today_user') != 'done') {
            @else
            if ($.cookie('hide-today') != 'done') {
            @endif
                $('#liveperson').modal('show');
            }
            console.log( "ready!" );
            var odometers = [ "#minijp", "#minorjp", "#majorjp", "#grandjp"];
            var updateTime = 1000;
            var apiUrl="/jpstv.json";
            var timeout;
            var updateJackpots = function (callback) {
                if (true) {
                    $.ajax({
                        url: apiUrl,
                        type: "GET",
                        data: {'cmd':'jackpotShow', 'id': 
                            @if (Auth::check())
                                {{auth()->user()->shop_id}} },
                            @else
                            0},
                            @endif
                        dataType: 'json',
                        success: function (data) {
                            var jackpots=data.content;
                            var communities=undefined;
                            var won=[];
                            if (Array.isArray(jackpots) && jackpots.length > 0) {
                                /*jackpots.sort(function(a,b) {
                                    return jackpotsSort[a.name]-jackpotsSort[b.name];
                                }); */
                                jackpots.forEach(function (item, index) {
                                    /*
                                    if (item['type']!==undefined) {
                                        index=jackpotsSort[item['type']];
                                    } else {
                                        index=jackpotsSort[item['name']];
                                    }
                                    
                                    if (item['dateTie']!==undefined) {
                                        if (savedJackpots[item['dateTie']]===undefined) {
                                            savedJackpots[item['dateTie']]=true;
                                            won.push({'jackpot_id':index,'event_id':event_id++,credit:item['jackpot'],winner:String(item['user'])});
                                        }
                                        return;
                                    } */

                                    //$(odometers[index]).text(item['jackpot'].toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ","));    
                                    var comma_separator_number_step = $.animateNumber.numberStepFactories.separator(',');
                                    $(odometers[index]).animateNumber(
                                        {
                                            number: item['jackpot'],
                                            numberStep: comma_separator_number_step
                                        },
                                        {
                                            easing: 'swing',
                                            duration: 1000
                                        }
                                    );

                                });
                            } else {
                                return false;
                            }
                            /*
                            if (won.length > 0) {
                                won.forEach(function (item) {
                                    if (jackpot_events_played.indexOf(item['event_id']) == -1) {
                                        hit_play_queue.push(item['jackpot_id']);
                                        jackpot_events_played.push(item['event_id']);
                                        jackpot_win_data.push({credit: item['credit'], winner: item['winner']});
                                    }
                                });
                            } 

                            if (init) {
                                $('#password-wrapper').hide();
                                $('#jackpot-wrapper').show();
                                init = false;
                            }*/
                            timeout = setTimeout(updateJackpots, updateTime);
                            if (callback != null) callback();
                        },
                        error: function () {
                            timeout = setTimeout(updateJackpots, updateTime);
                            if (callback != null) callback();
                        }
                    });
                } else {
                    clearTimeout(timeout);
                }
            };

            timeout = setTimeout(updateJackpots, updateTime);
        });
    </script>
@stop