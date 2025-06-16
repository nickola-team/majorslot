<!DOCTYPE html>
<html charset="utf-8">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>미투온</title>
    <meta name="Robots" content="all" />
    <meta name="Keywords" content="" />
    <meta name="Description" content="미투온은 모바일게임, 웹툰/웹소설, 연예 매니지먼트, 글로벌 OTT 드라마, K-뷰티 제작으로 글로벌 컨텐츠 문화를 이끌어나가는 종합 엔터테인먼트 기업입니다." />
    <meta name="Author" content="ME2ON Co., Ltd" />
    <meta name="Copyright" content="(c)ME2ON Co., Ltd" />
    <meta name="reply-to" content="" />
    <meta name="date" content="" />
    <meta property="og:title" content="ME2ON Co., Ltd" />
    <meta property="og:description" content="Global Leading Casual Gaming & Entertainment Company" />
    <link rel="icon" href="/frontend/me2on/Favicon_Me2on.ico">
    <link rel="stylesheet" type="text/css" href="/frontend/me2on/assets/css/swiper.min.css" />
    <link rel="stylesheet" type="text/css" href="/frontend/me2on/assets/css/_new/common.css?ver=1749860626" />
    <link rel="stylesheet" type="text/css" href="/frontend/me2on/assets/css/index.css?ver=1749860626" />

    <link rel="stylesheet" type="text/css" href="/frontend/me2on/assets/css/_new/style_pc.css?ver=1749860626" media="screen and (min-width:1400px)">

    <link rel="stylesheet" type="text/css" href="/frontend/me2on/assets/css/style_tablet.css?ver=1749860626" media="screen and (min-width:813px) and (max-width:1399px)" />
    <link rel="stylesheet" type="text/css" href="/frontend/me2on/assets/css/style_mobile.css?ver=1749860626" media="screen and (max-width:812px)" />

    <script type="text/javascript" src="/frontend/me2on/assets/js/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="/frontend/me2on/assets/js/jquery.easing.1.3.js"></script>
    <script type="text/javascript" src="/frontend/me2on/assets/js/_new/gsap.min.js"></script>
    <!-- 신규추가 스크립트 -->
    <script type="text/javascript" src="/frontend/me2on/assets/js/_new/ScrollTrigger.min.js"></script>
    <!-- 신규추가 스크립트 -->
    <script type="text/javascript" src="/frontend/me2on/assets/js/skrollr.min.js"></script>
    <script type="text/javascript" src="/frontend/me2on/assets/js/_new/common.js?ver=1749860626"></script>
    <script type="text/javascript" src="/frontend/me2on/assets/js/sub.js?ver=1749860626"></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/Swiper/4.5.0/js/swiper.min.js"></script>
    <script src='//www.youtube.com/iframe_api'></script>
    <!--<script src="https://cpwebassets.codepen.io/assets/common/stopExecutionOnTimeout-157cd5b220a5c80d4ff8e0e70ac069bffd87a61252088146915e8726e5d9f147.js"></script>-->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="//www.youtube.com/player_api"></script>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script language="javascript" type="text/javascript" src="/frontend/boss/V/customFunc.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <script language="javascript">
        let cnIsDesktop = true;

        function setCookie(name, value, expiredays) {
            var todayDate = new Date();
            todayDate.setDate(todayDate.getDate() + expiredays);
            document.cookie = name + "=" + escape(value) + "; path=/; expires=" + todayDate.toGMTString() + ";"
        }

        function pop_close(name, idx) {
            setCookie(name, "done", 1);
            $("#layer_pop" + idx).hide();
        }

        function pop_hide(idx) {
            $("#layer_pop" + idx).hide();
        }

        $(function() {
            var pop_move = false;
            // $(".main_layer_popup .pop_link").click(function() {
            //     var data = $(this).data("pop_idx");
            //     if (!pop_move) {
            //         pop_hide(data);
            //     }
            // })

            // $(".main_layer_popup").draggable({
            //     handle: "div.pop_bottom",
            //     cursor: "move"
            // });

            $(window).resize(function() {
                resetVideo("video-index2", cnIsDesktop);
            }).resize();
        });
    </script>


    <div id="wrap">
        <!-- 인덱스 신규 코드 -->
        <header id="header-new" class="doc-header">
            <div class="inner_head">
                <h1 class="doc-title">
                    <a href="/">
						<svg class="logo-path" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 820.83 181.4" width="200">
							<path style="fill:#fee745; stroke-width:0px;" d="M67.2,103.83l-18.19-23.75v76.04c0,15.92-11.37,24.51-24.25,24.51S0,172.3,0,156.13V27.03C0,10.61,12.38,1.01,25.77,1.01c8.84,0,18.19,4.29,24.51,12.38l34.86,44.72L120.26,13.39c6.32-8.08,15.41-12.38,24.25-12.38,13.39,0,26.02,9.6,26.02,26.02v129.1c0,16.17-11.37,24.51-24.76,24.51s-24.25-8.34-24.25-24.51v-76.04l-17.94,23.75c-5.81,7.83-11.62,10.36-18.44,10.36s-12.63-3.54-17.94-10.36Z"/>
							<path style="fill:#fee745; stroke-width:0px;" d="M210.45,179.63c-16.17,0-24.25-8.34-24.25-24.76V26.53c0-16.42,8.08-24.76,24.25-24.76h83.37c15.41,0,24.51,11.37,24.51,24.76s-9.09,24.76-24.51,24.76h-58.86v16.93h49.01c14.15,0,22.48,10.36,22.48,22.48s-8.34,22.49-22.48,22.49h-49.01v17.18h58.86c15.41,0,24.51,11.12,24.51,24.51s-9.09,24.76-24.51,24.76h-83.37Z"/>
							<path style="fill:#fff; stroke-width:0px;" d="M339.3,179.63c-4.29,0-6.32-2.53-6.32-7.33v-49.26c0-36.63,14.4-52.3,49.77-52.3h39.66c8.34,0,12.63-3.28,12.63-9.6s-4.29-9.85-12.63-9.85h-60.89c-15.41,0-23.5-8.34-23.75-24.76v-1.01c0-15.92,7.83-23.75,23.5-23.75h60.89c37.14,0,55.83,18.95,55.83,57.1s-11.62,53.31-49.01,53.31h-41.69c-7.83,0-11.62,3.03-11.62,9.35l.51,8.84h76.04c18.44,0,25.77,8.08,25.77,24.51s-8.59,24.76-26.02,24.76h-112.68Z"/>
							<path style="fill:#fee745; stroke-width:0px;" d="M579.81,0c50.28,0,90.95,40.42,90.95,90.7s-40.67,90.7-90.95,90.7-90.7-40.42-90.7-90.7S529.53,0,579.81,0ZM580.06,132.38c22.74,0,41.43-17.94,41.43-40.93s-18.7-41.43-41.43-41.43-41.18,18.44-41.18,41.43,18.44,40.93,41.18,40.93Z"/>
							<path style="fill:#fee745; stroke-width:0px;" d="M773.84,24.25c0-15.16,10.61-23.24,23.75-23.24,12.38,0,23.24,8.08,23.24,23.24v131.88c0,15.41-11.37,24.51-23.24,24.51-8.08,0-16.17-3.79-21.98-12.63l-45.98-68.46v57.85c0,15.16-10.61,23.24-23.75,23.24-12.38,0-22.99-8.08-22.99-23.24V25.26c0-15.16,11.12-24.25,22.99-24.25,8.34,0,16.42,3.54,22.23,12.38l45.73,68.47V24.25Z"/>
						</svg>
					</a>
                </h1>
                <nav class="doc-gnb">
                    <ul class="list_gnb">
                        <li><a href="/m11.php" class="link_gnb" onclick="return false;">COMPANY</a></li>
                        <li><a href="#" class="link_gnb" onclick="javascript:casinoGameStart('nexus-evo');">Game</a></li>
                        <li><a href="/m31.php" class="link_gnb" onclick="return false;">IR</a></li>
                        <li><a href="/board/board.php?bo_table=news" class="link_gnb" onclick="return false;">PR</a></li>
                        <li><a href="/m51.php" class="link_gnb" onclick="return false;">RECRUIT</a></li>
                    </ul>
                </nav>
                 @if (Auth::check())
                <div class="Header_login-area__TNY9b">
                    <div class="Header_login-before__kXFtM">
                    <strong class="guest_name" style="align-content: center;">{{auth()->user()->username}}</strong>
                    <strong class="text-uppercase ng-scope" style="align-content: center;">보유머니</strong>
                    <strong class="text-uppercase font-white pull-right ng-binding " style="align-content: center;">
                        <span class="mbalance" id="_top_money">{{number_format(auth()->user()->balance,0)}}</span> 원 </strong>
                    <a class="Header_log-in__1rpOs" data-testid="login-button" href="#"  onclick="logOut();" style="align-content: center;">로그아웃</a>
                    </div>
                </div>
                
                @else
                <div class="Header_nav-inner__right__JtRyq" style="padding-left: 100px;">
                <div class="Header_login-area__TNY9b">
                    <div class="Header_login-before__kXFtM">
                    <input type="text" placeholder="아이디" ng-model="loginForm.nickname" id="sID2" style="background-color: lavender; max-width:120px;
      padding: 7px;
      border: none;
      border-radius: 6px;
      outline: none;
      background: rgba(255, 255, 255, 0.2);
      color: #fff;
      font-size: 12px;
      transition: background 0.3s;">
                    <input type="password" placeholder="비밀번호" autocomplete="new-password" ng-model="loginForm.password" id="sPASS2" style="background-color: lavender; max-width:120px;
      padding: 7px;
      border: none;
      border-radius: 6px;
      outline: none;
      background: rgba(255, 255, 255, 0.2);
      color: #fff;
      font-size: 12px;
      transition: background 0.3s;">
                    <button type="button" class="btn btn-login Header_log-in__1rpOs sbmt-login2" data-testid="login-button" >로그인</button>
                    </div>
                </div>
                </div>
                @endif
                {{-- <div class="doc-lang">
                    <a href="/" target="" class="active" onclick="return false;">KOR</a>
                    <span></span>
                    <a href="/en/" target="" onclick="return false;">ENG</a>
                </div> --}}
                <div class="m-menu">
                    <button type="button">
						<span></span>
						<span></span>
						<span></span>
					</button>
                </div>
            </div>
            <div class="list-lnb">
                <div class="lnb-inner">
                    <ul>
                        <li><a href="/m11.php?tab=1#tab1" onclick="return false;">회사개요</a></li>
                        <li><a href="/m11.php?tab=2#tab2" onclick="return false;">CEO 인사말</a></li>
                        <li><a href="/m11.php?tab=3#tab3" onclick="return false;">VISION &amp; MISSION</a></li>
                        <li><a href="/m11.php?tab=4#tab4" onclick="return false;">연혁</a></li>
                        <li><a href="/m11.php?tab=5#tab5" onclick="return false;">오시는길</a></li>
                    </ul>
                    <ul></ul>
                    <!-- Game -->
                    <ul>
                        <li><a href="/m31.php" onclick="return false;">공시정보</a></li>
                        <li><a href="/m32.php" onclick="return false;">주가정보</a></li>
                        <li><a href="/m33.php" onclick="return false;">재무정보</a></li>
                        <li><a href="/board/board.php?bo_table=irdata" onclick="return false;">IR자료실</a></li>
                        <li><a href="/board/board.php?bo_table=notice" onclick="return false;">공고</a></li>
                    </ul>
                    <ul>
                        <li><a href="/board/board.php?bo_table=news" onclick="return false;">뉴스</a></li>
                        <li><a href="/board/board.php?bo_table=media" onclick="return false;">미디어</a></li>
                        <li><a href="/board/board.php?bo_table=gallery" onclick="return false;">갤러리</a></li>
                    </ul>
                    <ul>
                        <li><a href="/m51.php" onclick="return false;">인재상</a></li>
                        <li><a href="/m52.php" onclick="return false;">복리후생</a></li>
                        <li><a href="/board/board.php?bo_table=recruit" onclick="return false;">채용공고</a></li>
                    </ul>
                </div>
            </div>
            <section class="content-feature">
                <div class="inner_path">
                    <h3 class="screen_out">현재 페이지 위치</h3>
                    <div class="wrap_path"><a href="#">홈</a></div>
                    <div class="this-page">&nbsp; &gt; 메인</div>
                </div>
            </section>
        </header>
        <aside class="mob-menu">
            <div class="m-header">
                <h1 class="doc-title">
                    <a href="/" onclick="return false;">
						<svg class="logo-path" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 820.83 181.4" width="200">
							<path style="fill:#fee745; stroke-width:0px;" d="M67.2,103.83l-18.19-23.75v76.04c0,15.92-11.37,24.51-24.25,24.51S0,172.3,0,156.13V27.03C0,10.61,12.38,1.01,25.77,1.01c8.84,0,18.19,4.29,24.51,12.38l34.86,44.72L120.26,13.39c6.32-8.08,15.41-12.38,24.25-12.38,13.39,0,26.02,9.6,26.02,26.02v129.1c0,16.17-11.37,24.51-24.76,24.51s-24.25-8.34-24.25-24.51v-76.04l-17.94,23.75c-5.81,7.83-11.62,10.36-18.44,10.36s-12.63-3.54-17.94-10.36Z"/>
							<path style="fill:#fee745; stroke-width:0px;" d="M210.45,179.63c-16.17,0-24.25-8.34-24.25-24.76V26.53c0-16.42,8.08-24.76,24.25-24.76h83.37c15.41,0,24.51,11.37,24.51,24.76s-9.09,24.76-24.51,24.76h-58.86v16.93h49.01c14.15,0,22.48,10.36,22.48,22.48s-8.34,22.49-22.48,22.49h-49.01v17.18h58.86c15.41,0,24.51,11.12,24.51,24.51s-9.09,24.76-24.51,24.76h-83.37Z"/>
							<path style="fill:#fff; stroke-width:0px;" d="M339.3,179.63c-4.29,0-6.32-2.53-6.32-7.33v-49.26c0-36.63,14.4-52.3,49.77-52.3h39.66c8.34,0,12.63-3.28,12.63-9.6s-4.29-9.85-12.63-9.85h-60.89c-15.41,0-23.5-8.34-23.75-24.76v-1.01c0-15.92,7.83-23.75,23.5-23.75h60.89c37.14,0,55.83,18.95,55.83,57.1s-11.62,53.31-49.01,53.31h-41.69c-7.83,0-11.62,3.03-11.62,9.35l.51,8.84h76.04c18.44,0,25.77,8.08,25.77,24.51s-8.59,24.76-26.02,24.76h-112.68Z"/>
							<path style="fill:#fee745; stroke-width:0px;" d="M579.81,0c50.28,0,90.95,40.42,90.95,90.7s-40.67,90.7-90.95,90.7-90.7-40.42-90.7-90.7S529.53,0,579.81,0ZM580.06,132.38c22.74,0,41.43-17.94,41.43-40.93s-18.7-41.43-41.43-41.43-41.18,18.44-41.18,41.43,18.44,40.93,41.18,40.93Z"/>
							<path style="fill:#fee745; stroke-width:0px;" d="M773.84,24.25c0-15.16,10.61-23.24,23.75-23.24,12.38,0,23.24,8.08,23.24,23.24v131.88c0,15.41-11.37,24.51-23.24,24.51-8.08,0-16.17-3.79-21.98-12.63l-45.98-68.46v57.85c0,15.16-10.61,23.24-23.75,23.24-12.38,0-22.99-8.08-22.99-23.24V25.26c0-15.16,11.12-24.25,22.99-24.25,8.34,0,16.42,3.54,22.23,12.38l45.73,68.47V24.25Z"/>
						</svg>
					</a>
                </h1>
                <button type="button" class="close">
					<span></span>
					<span></span>
				</button>
            </div>
            <div class="m-nav">
                <dl>
                    <dt>COMPANY</dt>
                    <dd>
                        <ul>
                            <li><a href="/m11.php?tab=1#tab1" onclick="return false;">회사개요</a></li>
                            <li><a href="/m11.php?tab=2#tab2" onclick="return false;">CEO 인사말</a></li>
                            <li><a href="/m11.php?tab=3#tab3" onclick="return false;">VISION &amp; MISSION</a></li>
                            <li><a href="/m11.php?tab=4#tab4" onclick="return false;">연혁</a></li>
                            <li><a href="/m11.php?tab=5#tab5" onclick="return false;">오시는길</a></li>
                        </ul>
                    </dd>
                </dl>
                <dl>
                    <dt><a href="/m21.php" onclick="return false;">GAME</a></dt>
                </dl>
                <dl>
                    <dt>IR</dt>
                    <dd>
                        <ul>
                            <li><a href="/m31.php" onclick="return false;">공시정보</a></li>
                            <li><a href="/m32.php" onclick="return false;">주가정보</a></li>
                            <li><a href="/m33.php" onclick="return false;">재무정보</a></li>
                            <li><a href="/board/board.php?bo_table=irdata" onclick="return false;">IR자료실</a></li>
                            <li><a href="/board/board.php?bo_table=notice" onclick="return false;">공고</a></li>
                        </ul>
                    </dd>
                </dl>
                <dl>
                    <dt>PR</dt>
                    <dd>
                        <ul>
                            <li><a href="/board/board.php?bo_table=news" onclick="return false;">뉴스</a></li>
                            <li><a href="/board/board.php?bo_table=media" onclick="return false;">미디어</a></li>
                            <li><a href="/board/board.php?bo_table=gallery" onclick="return false;">갤러리</a></li>
                        </ul>
                    </dd>
                </dl>
                <dl>
                    <dt>RECRUIT</dt>
                    <dd>
                        <ul>
                            <li><a href="/m51.php" onclick="return false;">인재상</a></li>
                            <li><a href="/m52.php" onclick="return false;">복리후생</a></li>
                            <li><a href="/board/board.php?bo_table=recruit" onclick="return false;">채용공고</a></li>
                        </ul>
                    </dd>
                </dl>
                <div class="doc-lang">
                    <a href="/" target="" class="active" onclick="return false;">KOR</a>
                    <span></span>
                    <a href="/en/" target="" onclick="return false;">ENG</a>
                </div>
            </div>
            <div class="m-footer">
                <ul class="sns">
                    <li><a href="https://www.facebook.com/me2on/" target="_blank" onclick="return false;"><img src="/frontend/me2on/assets/images/common/sns_facebook.png" alt="미투온 페이스북"></a></li>
                    <li><a href="https://www.youtube.com/channel/UCFIjZGzd-doStj9T9voGq_w" target="_blank" onclick="return false;"><img src="/frontend/me2on/assets/images/common/sns_youtube.png" alt="미투온 유튜브"></a></li>
                    <li><a href="https://www.linkedin.com/company/me2on-group" target="_blank" onclick="return false;"><img src="/frontend/me2on/assets/images/common/sns_linkedin.png" alt="미투온 링크드인"></a></li>
                </ul>
                <p class="copy">COPYRIGHT (C) ME2ON Co.,Ltd. All Rights Reserved.</p>
            </div>
        </aside>
        <div class="index-visual">
            <video src="/frontend/me2on/assets/images/common/video-index2.mp4" autoplay="autoplay" muted="muted" loop="loop" playsinline="" id="vod_home" class="vod_home"></video>
            <div class="slogan over-text-wrap">
                <div>GLOBAL LEADING</div>
                <div>ENTERTAINMENT</div>
                <div>GROUP</div>
            </div>
        </div>
        <!-- // 인덱스 신규 코드 -->


        <!-- 기존 사이트 소스 -->
        <div class="btn_top"><img src="/frontend/me2on/assets/images/common/btn_top.png" alt="" /></div>
        <div id="footer">
            <div class="foot">
                <h1><img src="/frontend/me2on/assets/images/common/foot_logo.jpg"  alt="" /></h1>
                <div class="copy">COPYRIGHT (C) ME2ON Co.,Ltd. All Rights Reserved.</div>
                <div class="links">
                    <ul class="sns">
                        <li><a href="https://www.facebook.com/me2on/" target="_blank" onclick="return false;"><img src="/frontend/me2on/assets/images/common/sns_facebook2.png"
								alt="" /></a></li>
                        <li><a href="https://www.youtube.com/channel/UCFIjZGzd-doStj9T9voGq_w" target="_blank" onclick="return false;"><img
								src="/frontend/me2on/assets/images/common/sns_youtube2.png" alt="" /></a></li>
                        <li><a href="https://www.linkedin.com/company/me2on-group" target="_blank" onclick="return false;"><img
								src="/frontend/me2on/assets/images/common/sns_linkedin2.png" alt="" /></a></li>
                    </ul>
                    <div class="family"> 
                        <a>Family site</a>
                        <ul>
                            <li><a href="http://www.ghoststudio.net/" target="_blank" onclick="return false;">Ghost Studio</a></li>
                            <li><a href="https://www.memoriki.com/" target="_blank" onclick="return false;">Memoriki</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- //footer -->
        <!-- // 기존 사이트 소스 -->
    </div>
    <!-- //wrap -->

    <script>
var sID = '';
var sPASS = '';
var ajaxStart = false;
$(".show-pw").on('click', function() {
  let ac = $(this).hasClass('fa-eye-slash');
  if (ac) {
    $(this).removeClass('fa-eye-slash');
    $(this).addClass('fa-eye');
    $(this).siblings('input').attr('type', 'password');
  } else {
    $(this).removeClass('fa-eye');
    $(this).addClass('fa-eye-slash');
    $(this).siblings('input').attr('type', 'text');
  }
});
$(document).ready(function() {
  let uid = localStorage.getItem('sID');
  let upass = localStorage.getItem('sPASS');
  if ((uid && uid != '') && (upass && upass != '')) {
    $("#sID").val(uid);
    $("#sPASS").val(upass);
    $(".auto_login").prop('checked', true);
  }
  $(".remember-login").on('click', function() {
    $(".auto_login").click();
  });
});
var ip = "";
var provider = "";
var address = "";

$('#sPASS, #sID').keypress(function(e) {
  $(".sbmt-login").removeAttr('style');
  if (e.which == 13) {
    $(".sbmt-login").click();
  }
});
$(".sbmt-login").click(function() {
  var site_url = "yj-u.com";
  $(this).addClass('is-loading disabled');
  sID = $("#sID").val();
  sPASS = $("#sPASS").val();
  var csrf_token = $("#token").val();
  var location = localStorage.getItem('location');
  var provider = localStorage.getItem('provider');
  if (ajaxStart == true) {
    return false;
  } else {
    ajaxStart = true;
loginx();
  }
});
$("#sID").on('input', function() {
  $(this).removeClass('error');
  $(".error-text").text("");
});
$("#sPASS").on('input', function() {
  $(this).removeClass('error');
  $(".error-text").text("");
});
function loginbtnClick() {

  var site_url = "yj-u.com";
  $(this).addClass('is-loading disabled');
  sID = $("#sID2").val();
  sPASS = $("#sPASS2").val();
  var csrf_token = $("#token").val();
  var location = localStorage.getItem('location');
  var provider = localStorage.getItem('provider');
  if (ajaxStart == true) {
    return false;
  } else {
    ajaxStart = true;
loginx();
  }
};
$(".sbmt-login2").click(function() {

  var site_url = "yj-u.com";
  $(this).addClass('is-loading disabled');
  sID = $("#sID2").val();
  sPASS = $("#sPASS2").val();
  var csrf_token = $("#token").val();
  var location = localStorage.getItem('location');
  var provider = localStorage.getItem('provider');
  if (ajaxStart == true) {
    return false;
  } else {
    ajaxStart = true;
loginx();
  }
});

function loginx() {

  if (ajaxStart == false) {
    return false;
  }
  var site_url = "yj-u.com";
  var location = localStorage.getItem('location');
  var provider = localStorage.getItem('provider');
  //var sID = $("#sID2").val();
  //var sPASS = $("#sPASS2").val();
  var isRemembered = $(".auto_login").prop('checked');

  if (sID == '' || sPASS == '') {
    $("#sID2").addClass('error');
    $("#sPASS2").addClass('error');
    $(".sbmt-login").removeClass('is-loading disabled');
    ajaxStart = false;
  } else {
    var data = {
      'username': sID,
      'password': sPASS,
    };
    $.ajax({
      url: '/api/login',
      type: 'POST',
      data: data,
      dataType: "json",
      success: function(result) {
        $(".sbmt-login").removeClass('is-loading disabled');
        if (result.error==false) {
          if (isRemembered) {
            localStorage.setItem("sID", sID);
            localStorage.setItem("sPASS", sPASS);
          } else {
            localStorage.setItem("sID", "");
            localStorage.setItem("sPASS", "");
          }
          window.location.href = "/";
        } else {
          swal({
            title: "Oops!",
            text: result.msg,
            type: "error"
          }).then(function() {
            window.location.reload();
          });
        }
        ajaxStart = false;
      },
      error: function(xhr, status, error) {
        console.log("XHR:", xhr);
  console.log("Status:", status);
  console.log("Error:", error);
  alert("HTTP 상태: " + xhr.status + "\n응답 텍스트: " + xhr.responseText);
      }
    });
  }
}
</script>

</body>

</html>