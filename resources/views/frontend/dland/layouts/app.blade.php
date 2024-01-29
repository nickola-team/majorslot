
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="icon" href="/frontend/dland/favicon.ico" >
    <meta
      name="viewport"
      content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=0"
    />
    <meta name="theme-color" content="#000000" />
    <!-- <meta name="description" content="룰루 SLOT" /> -->
    <!-- <link rel="apple-touch-icon" href="/frontend/dland/logo192.png" /> -->
    <!-- <link rel="manifest" href="https://www.dland33.com/manifest.json" /> -->
    <script src="/frontend/dland/js/jquery-1.12.3.min.js"></script>
    <script src="/frontend/dland/js/TweenMax.min.js"></script>
    <script src="/frontend/dland/js/common.js"></script>
    <script src="/frontend/dland/js/fund.js"></script>
  	<script src="/frontend/dland/js/action.js"></script>

    <title>@yield('page-title')</title>
    <script type="text/javascript">
      var filter = "win16|win32|win64|mac";
      function isMobile() {
        var e = navigator.userAgent;
        return (
          null !=
            e.match(
              /iPhone|iPod|Android|Windows CE|BlackBerry|Symbian|Windows Phone|webOS|Opera Mini|Opera Mobi|POLARIS|IEMobile|lgtelecom|nokia|SonyEricsson/i
            ) || null != e.match(/LG|SAMSUNG|Samsung/)
        );
      }
      if (isMobile()) {
        var cssId = "myCss",
          head = document.getElementsByTagName("head")[0];
        ((link = document.createElement("link")).id = cssId),
          (link.rel = "stylesheet"),
          (link.type = "text/css"),
          (link.href = "/css/indexmo.css"),
          (link.media = "all"),
          head.appendChild(link);
      } else {
        var link;
        head = document.getElementsByTagName("head")[0];
        function repeat() {
          TweenMax.set($(".bs-logo > img").eq(1), {
            transformOrigin: "50% 100%",
            scaleY: 0,
          }),
            TweenMax.to($(".bs-logo > img").eq(1), 0.6, {
              startAt: { transformOrigin: "50% 100%", scaleY: 0 },
              scaleY: 1,
              ease: Back.easeOut,
            });
        }
        ((link = document.createElement("link")).id = cssId),
          (link.rel = "stylesheet"),
          (link.type = "text/css"),
          (link.href = "/frontend/dland/css/common.css"),
          (link.media = "all"),
          head.appendChild(link),
          ((link = document.createElement("link")).id = cssId),
          (link.rel = "stylesheet"),
          (link.type = "text/css"),
          (link.href = "/frontend/dland/css/layout.css"),
          (link.media = "all"),
          head.appendChild(link),
          ((link = document.createElement("link")).id = cssId),
          (link.rel = "stylesheet"),
          (link.type = "text/css"),
          (link.href = "/frontend/dland/css/animations.css"),
          (link.media = "all"),
          head.appendChild(link),
          ((link = document.createElement("link")).id = cssId),
          (link.rel = "stylesheet"),
          (link.type = "text/css"),
          (link.href = "/frontend/dland/css/slot.css"),
          (link.media = "all"),
          head.appendChild(link),
          ((link = document.createElement("link")).id = cssId),
          (link.rel = "stylesheet"),
          (link.type = "text/css"),
          (link.href = "/frontend/dland/css/custom.css"),
          (link.media = "all"),
          head.appendChild(link),
          setInterval(function () {
            repeat();
          }, 5e3);
        var browser = navigator.userAgent.toLowerCase();
        (("Netscape" == navigator.appName &&
          -1 != navigator.userAgent.search("Trident")) ||
          -1 != browser.indexOf("msie") ||
          -1 != browser.indexOf("edge")) &&
          (alert(
            "크롬에 최적화되어습니다. 크롬브라우저를  이용해주시길 바랍니다."
          ),
          (window.location.href = "https://www.google.com/intl/ko/chrome/"));
      }
    </script>
    <link rel="stylesheet" type="text/css" href="/frontend/dland/css/common.css" media="all" />
    <link rel="stylesheet" type="text/css" href="/frontend/dland/css/layout.css" media="all" />
    <link id="undefined" rel="stylesheet" type="text/css" href="/frontend/dland/css/animations.css" media="all" />
    <link id="undefined" rel="stylesheet" type="text/css" href="/frontend/dland/css/slot.css" media="all" />
    <link id="undefined" rel="stylesheet" type="text/css" href="/frontend/dland/css/custom.css" media="all" />
    <script type="text/javascript" src="/frontend/dland/jq/slideshow5/jquery.cycle2.min.js" ></script>
    <script type="text/javascript" src="/frontend/dland/jq/slideshow5/jquery.cycle2.carousel.min.js" ></script>
    <script type="text/javascript" src="/frontend/dland/js/common.js" ></script>
    <script type="text/javascript" src="/frontend/dland/js/jquery.newsTicker.js" ></script>
    <script type="text/javascript" src="/frontend/dland/js/showid.js" ></script>
    <link rel="stylesheet" href="/frontend/dland/css/logo.css" />
    
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>


    <style data-jss="" data-meta="makeStyles">
      .jss10 {
        display: flex;
      }
      .jss11 {
        color: #000;
        z-index: 1201;
        background-color: #fff;
      }
      .jss12 {
        overflow: auto;
      }
      .jss13 {
        flex-grow: 1;
      }
      .jss14 {
        display: inline;
        padding: 10px;
        flex-grow: 1;
        font-size: 16px;
        align-self: flex-end;
      }
      .jss15 {
        font-size: 18px;
      }
    </style>
    <style data-jss="" data-meta="makeStyles">
      .jss1 {
        display: flex;
      }
      .jss2 {
        padding-top: 110px;
      }
      .jss3 {
        color: #000;
        z-index: 1201;
        background-color: #fff;
      }
      .jss4 {
        width: 240px;
        flex-shrink: 0;
      }
      .jss5 {
        width: 240px;
      }
      .jss6 {
        overflow: auto;
      }
      .jss7 {
        padding: 24px;
        flex-grow: 1;
      }
      .jss8 {
        display: inline;
        padding: 10px;
        flex-grow: 1;
        font-size: 16px;
        align-self: flex-end;
      }
      .jss9 {
        font-size: 18px;
      }

      body{
        color: #FFC107;
      }

      .body{
        color: #FFC107;
      }

      .MuiPaper-root {
        background-color: #000;
        color: #FFC107;
      }

      .popup-content {
        width: 100%;
      }

      .MuiToolbar-gutters{
        padding :0px
      }

      .popup-content {
        background: none;
      } 

      .react-confirm-alert-overlay {
        z-index:99999
      }
    </style>
    <style data-styled="active" data-styled-version="5.1.0"></style>
  </head>

<body>

<div id="root">
  <input type="hidden" value="<?= csrf_token() ?>" name="_token" id="_token">
      <div>
        <div>
          <div id="header_wrap">
            <div class="util_wrap">
              <div class="header_box">
                <div class="util_left">
                  <img
                    src="/frontend/dland/images/top_icon.png"
                  />&nbsp;&nbsp;&nbsp;필독! 입금신청시 계좌 확인 필수 입니다.
                </div>
                <a
                  href="{{Request::root()}}"
                  class="bs-logo"
                  style="display: block"
                  ><img src="/frontend/dland/images/logo_02.png"
                /></a>
                <div class="util_right">
                @if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
                  <div class="my_a">
                    <div style="margin: 0px 0px 10px; display: inline-block; float:right;">
                      <span style="
                          line-height: 15px;
                          display: table;
                          float: left;
                          padding-top: 5px;
                          font-weight: bold;
                          font-size: 15px;">
                        <img src="/frontend/dland/images/icon4_1.png" style="width: 24px" />
                      </span>
                      &nbsp;&nbsp;
                      <span class="font07">{{ Auth::user()->username }}</span>
                      님&nbsp;&nbsp;잔고 :
                      <span class="font05" id="myWallet">{{ number_format(Auth::user()->balance,2) }} 원</span>
                      <a onclick="getBalance();"><img src="/frontend/dland/images/icon_re.png" class="icon_re"></a>
                    </div>
                    <br />
                    <div style="margin: 0px 0px 10px; display: inline-block;float:right;">
                      보너스 :
                      <span class="font05" id="myBonus">{{ number_format(Auth::user()->deal_balance,2) }} 원</span>
                      <a onclick="showDealOut();"><img src="/frontend/dland/images/icon_bonus.png" class="icon_re"></a>
                    </div>
                    <br />
                    <div style="margin: 0px 0px 10px; display: inline-block;float:right;">
                    <a class="fade_1_open" data-popup-ordinal="6" id="open_75076113" onclick="showProfilePopup();">
                      <img src="/frontend/dland/images/top_btn_003.png" />
                    </a>
                    <a class="fade_3_open" data-popup-ordinal="0" id="open_55563334" onclick="showProfileEditorPopup();">
                      <img src="/frontend/dland/images/top_btn_005.png" />
                    </a>
                    <a onclick="goLogout();"><img src="/frontend/dland/images/top_btn_004.png" /></a>
                    </div>
                  </div>
                @else
                  <div class="my">
                    <a class="fade_2_open" data-popup-ordinal="0" onclick="showLoginPopup()"><img src="/frontend/dland/images/top_btn_001.png"></a>
                    <a class="fade_3_open" data-popup-ordinal="0" onclick="showRegisterPopup()"><img src="/frontend/dland/images/top_btn_002.png"></a>
                  </div>
                @endif

                </div>
              </div>
            </div>
            <div class="gnb_wrap">
              <div class="gnb">
                <ul>
                  <li>
                    <a onclick="showDepositPopup();"><img src="/frontend/dland/images/gnb01.png" /> 입금신청</a>
                  </li>
                  <li>
                    <a onclick="showWithdrawPopup();"><img src="/frontend/dland/images/gnb02.png" /> 출금신청</a>
                  </li>
                  <li>
                    <a onclick="showNotificationPopup();"><img src="/frontend/dland/images/gnb07.png" /> 공지사항</a>
                  </li>
                  <li>
                    <a onclick="showContactPopup();"><img src="/frontend/dland/images/gnb05.png" /> 고객센터</a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="visual_ling_2"></div>
          <div class="visual_wrap">
          </div>
          <div class="visual_ling_2"></div>
          @if ($livegames && count($livegames))
          <div class="popular-title">
            <p>라이브 게임</p>  
          </div>

          <div style="width: 100%; display:flex;justify-content:center;float:left;">
            <div class="visual_ling_2" style="max-width: 1440px;"></div>
          </div>
          <div style="float:left;width: 100%; text-align: -webkit-center;">
            <div class="slider-wrapper">
              <ul class="lightSlider">
                @foreach($livegames AS $index=>$game)
                  <li onClick="startGameByProvider('{{$game['provider']}}', '{{$game['gamecode']}}');">
                    <a style="cursor: pointer">
                      <img 
                        src="{{$game['icon']}}"
                        id="xImag"
                        width="390"
                        height="225"
                        />
                    </a>
                  </li>
                @endforeach
              </ul>
            </div>
          </div>
          <div style="width: 100%; display:flex;justify-content:center;float:left;">
            <div class="visual_ling_2" style="max-width: 1440px;"></div>
          </div>
          @endif
          <div class="popular-title">
            <p>슬롯 게임</p>  
          </div>
          <div style="width: 100%; display:flex;justify-content:center;float:left;">
            <div class="visual_ling_2" style="max-width: 1440px;"></div>
          </div>
          <div id="contents_wrap">
            <div class="sc-inner">

	  		<div id="popup"></div>
        

			@if ($categories && count($categories))
				@foreach($categories AS $index=>$category)
          @if(!(isset ($errors) && count($errors) > 0) && !Session::get('success', false))

					<a class="slot-btn gl-title-click" onclick="showGamesPopup('{{ $category->trans->trans_title }}', '{{ $category->href }}', 0)">
						<div class="inner">
						<img
							class="slot-bg"
							src="/frontend/dland/images/slot-bg.png"
							style="opacity: 100"
						/>
						 <div class="hover-bg">
							<span></span><span></span><span></span><span></span>
						</div> 
						 <div class="slot-cont">
							<img class="slot-img" src="/frontend/dland/images/slots/{{ $category->title.'.png' }}" />
						</div> 
						</div>
					</a>

					@endif
				@endforeach
        <?php
            $comingSoon = (intval(count($categories)/6) + 1 ) * 6 - count($categories);
        ?>
        @for ($i=0;$i<$comingSoon;$i++)
        <a class="slot-btn gl-title-click" onclick="alert('준비중입니다.');">
						<div class="inner">
						<img
							class="slot-bg"
							src="/frontend/dland/images/slot-bg.png"
							style="opacity: 100"
						/>
						 <div class="hover-bg">
							<span></span><span></span><span></span><span></span>
						</div> 
						 <div class="slot-cont">
							<img class="slot-img" src="/frontend/dland/images/slots/coming_soon.png" />
						</div> 
						</div>
					</a>
        @endfor
			@endif
			
            </div>
          </div>
          <div id="footer">
            <img src="/frontend/dland/images/partner.png" /><br /><br />Copyright ⓒ
            2020~2021 대박랜드 All rights reserved.
          </div>
         </div>
         <div>
        @if ($notice != null)
        <div class="pop01_popup1 draggable02" id="notification" style="position: absolute; top: 0px; left: 0px; z-index: 1000;">
          <div class="pop01_popup_wrap">
              <div class="pop01_popup_btn_wrap">
                  <ul>
                      <li><a href="#" onclick="closeNotification(false);"><span class="pop01_popup_btn">8시간동안 창을 열지 않음</span></a></li>
                      <li><a href="#" onclick="closeNotification(true);"><span class="pop01_popup_btn">닫기 X</span></a></li>
                  </ul>
              </div>
              <div class="pop01_popup_box">
                  <div class="pop01_popup_text" style="padding: 30px; width: 500px;">
                    <span class="pop01_popup_font1" style="border-bottom: 2px solid rgb(255, 255, 255); margin-bottom: 15px;"></span>
                    <span class="pop01_popup_font2">
                          <div>
                              <?php echo $notice->content ?>
                          </div>
                    </span>
                  </div>
              </div>
          </div>
        </div>
        </div>
        @endif
      </div>
    </div>

@yield('content')

@yield('popup')
<script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

<script type='text/javascript'>

	@if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
		var loginYN='Y';
		var currentBalance = {{ Auth::user()->balance }};
		var userName = "{{ Auth::user()->username }}";
	@else
		var loginYN='N';
		var currentBalance = 0;
		var userName = "";
	@endif

  $(document).ready(function() {
    var prevTime = localStorage.getItem("hide_notification");
    if (prevTime && Date.now() - prevTime < 8 * 3600 * 1000) {
      $("#notification").hide();
    }

    $(".lightSlider").slick({
      // normal options...
      infinite: true,
      slidesToShow: 3,
      slidesToScroll: 3,
      // variableWidth: true,
      autoplay: true,
      autoplaySpeed: 2000,
      });
  })
</script>

</body>
</html>