
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="icon" href="/frontend/winnersvi/img/favicon.ico" >
    <meta
      name="viewport"
      content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=0"
    />
    <meta name="theme-color" content="#000000" />
      <script type="text/javascript" src="/frontend/winnersvi/js/app.js"></script>
    <script type="text/javascript" src="/frontend/winnersvi/js/jquery.number.min.js"></script>
    <script type="text/javascript" src="/frontend/winnersvi/js/parsley.min.js"></script>
    <script type="text/javascript" src="/frontend/winnersvi/js/parsley.remote.min.js"></script>
    <script type="text/javascript" src="/frontend/winnersvi/js/lazyload.min.js"></script>
    <script type="text/javascript" src="/frontend/winnersvi/js/action.js"></script>

    <script src="/frontend/winnersvi/js/TweenMax.min.js"></script>
    <title>@yield('page-title')</title>
    <script type="text/javascript">
      var depositAccountRequested = false;
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
        
        var browser = navigator.userAgent.toLowerCase();
        (("Netscape" == navigator.appName &&
          -1 != navigator.userAgent.search("Trident")) ||
          -1 != browser.indexOf("msie") ||
          -1 != browser.indexOf("edge")) &&
          (alert(
            "Được tối ưu hóa cho Chrome. Tôi hy vọng bạn sẽ sử dụng Chrome Browser."
          ),
          (window.location.href = "https://www.google.com/intl/ko/chrome/"));
      }
    </script>

    <link rel="stylesheet" href="/frontend/winnersvi/css/font-icon.css">
    <link rel="stylesheet" href="/frontend/winnersvi/css/app.css">
    <link rel="stylesheet" href="/frontend/winnersvi/css/style7d93.css?v=2368">
    <link rel="stylesheet" href="/frontend/winnersvi/css/galaxy5358.css?v=1540">
    <link rel="stylesheet" href="/frontend/winnersvi/css/themes/default.min.css">


    <!-- Sweet-Alert  -->
    <script type="text/javascript" src="/frontend/winnersvi/js/sweetalert/sweetalert.min.js"></script>
    <script type="text/javascript" src="/frontend/winnersvi/js/sweetalert/jquery.sweet-alert.custom.js"></script>
    <link rel="stylesheet" href="/frontend/winnersvi/js/sweetalert/sweetalert.css" type="text/css">

    <script type="text/javascript" src="/frontend/winnersvi/js/menuafee.js?v=9337"></script>
	<script type="text/javascript" src="/frontend/winnersvi/js/maina5e0.js?v=3289"></script>
	<script type="text/javascript" src="/frontend/winnersvi/js/script838b.js?v=1433"></script>
   
    <style data-styled="active" data-styled-version="5.1.0"></style>
  </head>

<body marginwidth="0" marginheight="0">
@if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
<input type="hidden" name="is_sign_in" id="is_sign_in" value="1">
@else
<input type="hidden" name="is_sign_in" id="is_sign_in" value="0">
@endif
<div class="wrapper">
  <input type="hidden" value="<?= csrf_token() ?>" name="_token" id="_token">
      
  <div class="nav-main">
		<div class="center">
			<a href="{{url('/')}}" class="gs-logo">
				<div class="container">
					<img src="/frontend/winnersvi/img/logo.png">
				</div>
			</a>

      <div class="nav-cont">
        <div class="link-main">
          <ul class="bs-ul">
            @if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
            @if ($unreadmsg>0)
            <li class="deposit-link ">
               <a href="javascript:void(0);"  onclick="alert_error('Hãy kiểm tra tờ giấy');">
            @else
            <li class="deposit-link subpg-link">
              <a href="javascript:void(0);">
            @endif
            @else
            <li class="deposit-link ">
               <a href="javascript:void(0);"  onclick="alert_error('Tôi cần đăng nhập');">
            @endif
                <div class="icon-cont">
                  <img src="/frontend/winnersvi/img/icon/deposit.png">
                </div>
                <span>đăng ký gửi tiền</span>
              </a>
            </li>
           
            @if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
            @if ($unreadmsg>0)
            <li class="deposit-link ">
               <a href="javascript:void(0);"  onclick="alert_error('Hãy kiểm tra tờ giấy');">
            @else
            <li class="withdraw-link subpg-link">
              <a href="javascript:void(0);" >
            @endif
            @else
            <li class="withdraw-link ">
               <a href="javascript:void(0);"  onclick="alert_error('Tôi cần đăng nhập');">
            @endif
                <div class="icon-cont">
                  <img src="/frontend/winnersvi/img/icon/withdraw.png">
                </div>
                <span>đơn xin rút tiền</span>
              </a>
            </li>
           
           
              @if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
              <li class="notice-link subpg-link">
                <a href="javascript:void(0);">
              @else
              <li class="notice-link">
                <a href="javascript:void(0);"  onclick="alert_error('Tôi cần đăng nhập');">
              @endif
                <div class="icon-cont">
                  <img src="/frontend/winnersvi/img/icon/notice.png">
                </div>
                <span>thông báo</span>
              </a>
            </li>
            
              @if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
              <li class="mypage-link">
                <a href="javascript:void(0);">
              @else
              <li>
                <a href="javascript:void(0);"  onclick="alert_error('Tôi cần đăng nhập');">
              @endif
                <div class="icon-cont">
                  <img src="/frontend/winnersvi/img/icon/mypage.png">
                </div>
                <span>trang của tôi</span>
              </a>
              </li>
          </ul>
        </div>
        <div class="bal-container">
          
          @if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
          <div class="after-login active">
            <div class="mobile">
              <button class="logout-btn"><i class="icon icon-Exit"></i></button>
              <button class="mypg-btn">
                <span></span>
                <span></span>
                <span></span>
              </button>
            </div>
            <div class="desktop">
              <div class="al-inner">
                <div class="al-row">
                  <div class="al-cont my-page-btn" data-toggle="modal" data-target=".mypageModal">
                    <div class="labels">
                      <span class="inner">
                        <i class="icon icon-User"> :</i>
                      </span>
                    </div>
                    <div class="info">
                      <a href="javascript:void(0);"><p style="margin:0">{{ Auth::user()->username }}</p></a>
                    </div>
                  </div>
                  <div class="al-cont balance-btn" data-toggle="modal" data-target=".mypageModal">
                    <div class="labels">
                      <span class="inner">
                        tiền dự trữ :
                      </span>
                    </div>
                    <div class="info">
                      <p class="player-balance" style="margin:0"> {{ number_format(Auth::user()->balance,0) }}WON</p>
                    </div>
                  </div>
                  <div class="al-cont point-info-btn" data-toggle="modal" data-target=".mypageModal">
                    <div class="labels">
                      <span class="inner">
                        tiền thưởng :
                      </span>
                    </div>
                    <div class="info">
                      <p class="player-balance" style="margin:0"> {{ number_format(Auth::user()->deal_balance,0) }}WON</p>
                    </div>
                  </div>
                  <!-- <div class="al-cont point-info-btn" data-toggle="modal" data-target=".mypageModal">
                    <div class="labels">
                      <span class="inner">
                        <a href="javascript:void(0);">포인트 :</a>
                      </span>
                    </div>
                    <div class="info">
                      <p class="player-point" style="margin:0">로딩중···</p>
                    </div>
                  </div> -->
                </div>
                <div class="al-row">
                  <div class="al-cont prog-b">
                  </div>
                  <div class="al-cont btn-grp">
                    <button class="blue message message-btn" data-toggle="modal" data-target=".mypageModal">
                      <i class="fa fa-envelope"></i> lời nhắn
                      <span class="mess-count" style="animation: letter_anim 0s linear infinite;">{{$unreadmsg}}</span>
                    </button>
                    <button class="logout-btn red" onclick="goLogout();"><i class="fa fa-sign-out-alt"></i> sự đăng xuất</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @else
          <div class="before-login active">
            <button class="login" data-toggle="modal" data-target=".loginModal"><i class="icon icon-Starship"></i> sự đăng nhập</button>
            <button class="join" data-toggle="modal" data-target=".joinModal"><i class="icon icon-Planet"></i> gia nhập hội viên</button>
          </div>
          @endif
        </div>
      </div>
    </div>
	</div>
  <div class="banner-main">
    <div class="banner-deco">
      <div class="sky-container left">
        <div class="none"></div>
        <div class="none"></div>
        <div class="none"></div>
        <div class="star"></div>
        <div class="none"></div>
      </div>
      <img class="fireball-orange" src="/frontend/winnersvi/img/banner/fireball-orange.png">
      <!-- <img class="fireball-yellow" src="img/banner/fireball-yellow.png"> -->
      <!-- <img class="wolf" src="img/banner/wolf.png"> -->
      <!-- <img class="glow" src="img/banner/glow.png"> -->
      <img class="king" src="/frontend/winnersvi/img/banner/char-1eccb.png?3">
      <!-- <img class="robot" src="img/banner/robot.png"> -->
      <!-- <img class="diamond" src="img/banner/diamond.png"> -->
      <div class="sky-container">
        <div class="none"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
      </div>
      <img class="coins" src="/frontend/winnersvi/img/banner/coins.png">
      <!-- <img class="girl" src="img/banner/girl.png"> -->
      <img class="rich-man" src="/frontend/winnersvi/img/banner/char-2c81e.png?2">
      <!-- <img class="slot-machine" src="img/banner/slot-machine.png"> -->
      <div class="sky-container right">
        <div class="star"></div>
        <div class="none"></div>
        <div class="star"></div>
        <div class="none"></div>
        <div class="none"></div>
      </div>
    </div>
    <div class="banner-carousel carousel" data-ride="carousel" data-pause="false" data-interval="6000">
      <div class="carousel-inner">
        <div class="item">
          <div class="text-cont">
            <div class="inner">
              <p class="title">Chào mừng các bạn đến với </p>
              <p class="sub">WARRIOR</p>
            </div>
          </div>
        </div>
        <div class="item active">
          <div class="text-cont">
            <div class="inner">
              <p class="title">tỷ lệ thu hồi cao nhất châu Á </p>
              <p class="sub">Bonus tuyệt vời nhất trong ngành!</p>
            </div>
          </div>
        </div>
        <div class="item">
          <div class="text-cont">
            <div class="inner">
              <p class="title">Jackpot nổ mỗi ngày!</p>
              <p class="sub"> Cùng với WARRIOR!</p>
            </div>
          </div>
        </div>
       
      </div>
      <ol class="carousel-indicators">
        <li data-target=".banner-carousel" data-slide-to="0" class=""></li>
        <li data-target=".banner-carousel" data-slide-to="1" class="active"></li>
        <li data-target=".banner-carousel" data-slide-to="2" class=""></li>
        <li data-target=".banner-carousel" data-slide-to="3" class=""></li>
      </ol>
    </div>

  </div>


  <div class="page-content">
          
    <div style="margin-top:25px;height:50px;">
      <span class="a_style">WARRIOR'S CASINO</span>
    </div>

    <div class="slot-container">

    @if ($categories && count($categories))
				@foreach($categories AS $index=>$category)
					@if($category->title != "Hot" && $category->title != "Card" && $category->title != "Bingo" && $category->title != "Roulette" 
					&& $category->title != "Novomatic" && $category->title != "Keno" && $category->title != "Vision" && $category->title != "Wazdan")
          @if($category->provider=='gac' || $category->href=='gameplay')
          @if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
            @if ($unreadmsg>0)
            <a href="javascript:void(0);" class="slot-btn"  onclick="alert_error('Hãy kiểm tra tờ giấy');">
            @else
            <a href="javascript:void(0);" class="slot-btn" onclick=" getSlotGames('{{ $category->title }}', '{{ $category->href }}', 0)">
            @endif
          @else
          <a href="javascript:void(0);" class="slot-btn"  onclick="alert_error('Tôi cần đăng nhập');">
          @endif
            <div class="sb-inner">
              <div class="main-cont">
                <div class="inner">
                  <img class="main-img" src="/frontend/winnersvi/img/slot/{{ $category->title.'.jpg' }}">
                  <button class="start-text">Trò chơi bắt đầu</button>
                </div>
              </div>
              <button class="name-btn">
                {{ $category->title }}
              </button>
              <img class="plate" src="/frontend/winnersvi/img/bg/slot-plate.png">
            </div>
            <div class="icon-cont">
              <!-- <img src="/frontend/winnersvi/img/slot-icon2/{{ $category->title }}.png" /> -->
              @if($category->provider=='gac')
              <p class="text-red">CASINO</p>
              @else
              <p class="text-red">POWERBALL</p>
              @endif
            </div>
          </a>     
          @endif
					@endif
				@endforeach
        <!-- Slot games -->
        @if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
            @if ($unreadmsg>0)
            <a href="javascript:void(0);" class="slot-btn"  onclick="alert_error('请确认纸条');">
            @else
            <a href="javascript:void(0);" class="slot-btn" onclick=" getSlotGames('Slot Games', 'comp')">
            @endif
          @else
          <a href="javascript:void(0);" class="slot-btn"  onclick="alert_error('需要登陆');">
          @endif
            <div class="sb-inner">
              <div class="main-cont">
                <div class="inner">
                  <img class="main-img" src="/frontend/winnersvi/img/slot/Pragmatic Play.jpg">
                  <button class="start-text">Trò chơi bắt đầu</button>
                </div>
              </div>
              <button class="name-btn">
                SlotGames
              </button>
              <img class="plate" src="/frontend/winnersvi/img/bg/slot-plate.png">
            </div>
            <div class="icon-cont">
              <!-- <img src="/frontend/winnersvi/img/slot-icon2/Hot.png" /> -->
              <p class="text-red">HOT SLOT</p>
            </div>
          </a>
			@endif

    </div>

	</div>

  <div class="news-main">
	    <div class="deco-bg left"></div>
	    <div class="news-carousel carousel slide" data-ride="carousel" data-pause="false">
	        <div class="carousel-inner">
	        	<div class="item">
	        		<span>
	        			
	        		</span>
	        	</div>
	        	<div class="item active">
	        		<span>
	        			
	        		</span>
	        	</div>
	        </div>
	    </div>
	    <div class="deco-bg right"></div>
	</div>

  <div class="footer-main">
		<div class="company-logo">
      <div class="center">
        <img src="/frontend/winnersvi/img/slot-icon/Blueprint.png">
        <img src="/frontend/winnersvi/img/slot-icon/Booongo.png">
        <img src="/frontend/winnersvi/img/slot-icon/Endorphina.png">
        <img src="/frontend/winnersvi/img/slot-icon/GameArt.png">
        <img src="/frontend/winnersvi/img/slot-icon/Habanero.png">
        <img src="/frontend/winnersvi/img/slot-icon/Micro.png">
        <img src="/frontend/winnersvi/img/slot-icon/Platipus.png">
        <img src="/frontend/winnersvi/img/slot-icon/Playson.png">
        <img src="/frontend/winnersvi/img/slot-icon/Pragmatic.png">
        <img src="/frontend/winnersvi/img/slot-icon/RedRake.png">
        <img src="/frontend/winnersvi/img/slot-icon/Spinmatic.png">
        <img src="/frontend/winnersvi/img/slot-icon/Spinomenal.png">
        <img src="/frontend/winnersvi/img/slot-icon/Thunderkick.png">
      </div>
    </div>
    <div class="copyright-cont">
        <span>&copy; COPYRIGHT  WARRIOR  {{ now()->year }} ALL RIGHTS RESERVED</span>
    </div>
	</div>


</div>

<div class="loginModal modal fade in" role="dialog" style="display: none; padding-right: 17px;">
    <div class="bs-modal modal-dialog">
        <div class="bsm-cont">
            <button class="mdl-close-btn" data-dismiss="modal">sự đóng cửa</button>
            <div class="bsm-inner">
                <div class="mdl-head">
                    <div class="mdl-title">
                        <div class="active">
                            <p class="en">LOGIN</p>
                            <p class="kr">sự đăng nhập</p>
                        </div>
                    </div>
                </div>
                <div class="nav-mdl">
                    <div class="nav-cont"></div>
                </div>
                <div class="modal-body">
                    <div class="tab-mdl active">
                        <form id="loginForm" action="javascript:loginSubmit(this)" method="post" data-parsley-validate="true" novalidate="">
                            <div class="form-container">
                                <div class="form-group">
                                    <div class="infos">
                                        <input type="text" placeholder="ID" name="userid" data-parsley-required="required" data-parsley-required-message="Hạng mục nhập bắt buộc" autocomplete="off" data-parsley-id="7331"><ul class="parsley-errors-list" id="parsley-id-7331"></ul>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="infos">
                                        <input type="password" placeholder="mật khẩu" name="password" data-parsley-required="required" data-parsley-required-message="Hạng mục nhập bắt buộc" autocomplete="off" data-parsley-id="5272"><ul class="parsley-errors-list" id="parsley-id-5272"></ul>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-btn-foot">
                                <div class="btn-cont">
                                    <button type="submit" class="red over-style login-btn">
                                        <span class="os-cont"><i class="fa fa-lock-open"></i> sự đăng nhập</span>
                                    </button>
                                    <button type="reset" class="join-btn over-style">
                                    <span class="os-cont">
                                        <i class="fa fa-file-signature"></i> gia nhập hội viên
                                    </span>
                                    </button>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="joinModal modal fade" role="dialog">
	<div class="bs-modal modal-dialog">
		<div class="bsm-cont">
			<button class="mdl-close-btn" data-dismiss="modal">sự đóng cửa</button>
			<div class="bsm-inner">
				<div class="mdl-head">
					<div class="mdl-title">
						<div class="active">
							<p class="en">JOIN</p>
							<p class="kr">gia nhập hội viên</p>
						</div>
					</div>
				</div>
				<div class="nav-mdl">
					<div class="nav-cont"></div>
				</div>
				<div class="modal-body">
					<div class="tab-mdl active">
						<form id="joinForm" action="javascript:joinSubmit()" method="post" data-parsley-validate="" novalidate="">
							<div class="form-container">
								<div class="form-group">
									<div class="labels">
										<p>biệt danh</p>
									</div>
									<div class="infos">
										<input class="form-control " id="membername" data-parsley-trigger="input change" data-parsley-pattern="/^[ㄱ-ㅎ|가-힣|a-z|A-Z|0-9|\*]+$/" data-parsley-pattern-message="giá trị không hợp lệ" data-parsley-required="true" data-parsley-required-message="Hạng mục nhập bắt buộc" data-parsley-minlength="2" data-parsley-minlength-message="Hãy nhập nhiều hơn 2 ký tự" data-parsley-maxlength="12" data-parsley-maxlength-message="Hãy nhập ít hơn 12 ký tự" autocomplete="off" name="membername" type="text" value="" data-parsley-id="3942"><ul class="parsley-errors-list" id="parsley-id-3942"></ul>
									</div>
								</div>
								<div class="form-group">
									<div class="labels">
										<p>mật khẩu</p>
									</div>
									<div class="infos">
										<input class="form-control " id="memberpw" maxlength="12" data-parsley-trigger="change" data-parsley-required="true" data-parsley-required-message="Hạng mục nhập bắt buộc" data-parsley-maxlength="12" data-parsley-maxlength-message="Hãy nhập ít hơn 12 ký tự" data-parsley-minlength="6" data-parsley-minlength-message="Hãy nhập nhiều hơn 6 ký tự" autocomplete="off" name="memberpw" type="password" value="" data-parsley-id="8027"><ul class="parsley-errors-list" id="parsley-id-8027"></ul>
									</div>
								</div>
								<div class="form-group">
									<div class="labels">
										<p>xác nhận mật khẩu</p>
									</div>
									<div class="infos">
										<input class="form-control " data-parsley-equalto="#memberpw" data-parsley-equalto-message="Mật khẩu không khớp" maxlength="12" data-parsley-trigger="change" data-parsley-required="true" data-parsley-required-message="Hạng mục nhập bắt buộc" data-parsley-minlength="6" data-parsley-minlength-message="Hãy nhập nhiều hơn 6 ký tự" autocomplete="off" name="confirmpw" type="password" value="" data-parsley-id="5099" id="confirmpw"><ul class="parsley-errors-list" id="parsley-id-5099"></ul>
									</div>
								</div>
								<div class="form-group">
									<div class="labels">
										<p>số điện thoại</p>
									</div>
									<div class="infos">
										<input class="form-control" id="mobile" data-parsley-type="digits" data-parsley-type-message="Chỉ có thể nhập số thôi" data-parsley-pattern="/^[0-9-]*$/" data-parsley-pattern-message="giá trị không hợp lệ" maxlength="15" data-parsley-trigger="change input focusin focusout" data-parsley-required="true" data-parsley-required-message="Hạng mục nhập bắt buộc" data-parsley-maxlength="15" data-parsley-maxlength-message="Hãy nhập ít hơn 15 ký tự" data-parsley-minlength="9" data-parsley-minlength-message="Hãy nhập nhiều hơn 9 ký tự" placeholder="Chỉ nhập số mà không có "-"" autocomplete="off" name="mobile" type="text" value="" data-parsley-id="1202"><ul class="parsley-errors-list" id="parsley-id-1202"></ul>
									</div>
								</div>
								<div class="form-group">
									<div class="labels">
										<p>tên ngân hàng</p>
									</div>
									<div class="infos">
										<label>
											<select id="bankname" class="form-control " data-parsley-required="true" data-parsley-required-message="Hạng mục nhập bắt buộc" name="bankname" data-parsley-id="5166">
												<option value="">--lựa chọn ngân hàng--</option>
                        @foreach(\VanguardLTE\User::$values["banks"] AS $val)
                          @if($val != "")
                            <option value="{{$val}}">{{$val}}</option>
                          @endif
                        @endforeach
											</select><ul class="parsley-errors-list" id="parsley-id-5166"></ul>
										</label>
									</div>
								</div>
								<div class="form-group">
									<div class="labels">
										<p>số tài khoản</p>
									</div>
									<div class="infos">
										<input id="accountnumber" class="form-control " data-parsley-type="digits" data-parsley-type-message="Chỉ có thể nhập số thôi" data-parsley-pattern="/^[0-9]*$/" data-parsley-pattern-message="giá trị không hợp lệ" maxlength="30" data-parsley-trigger="change" data-parsley-maxlength="30" data-parsley-maxlength-message="Hãy nhập ít hơn 30 ký tự" data-parsley-required="true" data-parsley-required-message="Hạng mục nhập bắt buộc" autocomplete="off" name="accountnumber" type="text" value="" data-parsley-id="1905"><ul class="parsley-errors-list" id="parsley-id-1905"></ul>
									</div>
								</div>
								<div class="form-group">
									<div class="labels">
										<p>người gửi tiền</p>
									</div>
									<div class="infos">
										<input id="mb_account_name" class="form-control" data-parsley-trigger="change" data-parsley-pattern="/^[ㄱ-ㅎ|가-힣|a-z|A-Z|0-9]+$/" data-parsley-pattern-message="giá trị không hợp lệ" maxlength="12" data-parsley-minlength="2" data-parsley-minlength-message="Hãy nhập nhiều hơn 3 ký tự" data-parsley-maxlength="12" data-parsley-maxlength-message="Hãy nhập ít hơn 12 ký tự" data-parsley-required="true" data-parsley-required-message="Hạng mục nhập bắt buộc" autocomplete="off" name="a_name" type="text" value="" data-parsley-id="0047"><ul class="parsley-errors-list" id="parsley-id-0047"></ul>
									</div>
								</div>
								<div class="form-group">
									<div class="labels">
										<p>mã giới thiệu</p>
									</div>
									<div class="infos">
										<input class="form-control " id="recomcode_id" data-parsley-trigger="change" maxlength="12" data-parsley-required="true" data-parsley-required-message="Hạng mục nhập bắt buộc" autocomplete="off" name="recomcode_id" type="text" value="" data-parsley-id="9597"><ul class="parsley-errors-list" id="parsley-id-9597"></ul>
									</div>
								</div>
							</div>
							<div class="modal-btn-foot">
								<div class="btn-cont">
									<button type="submit" class="over-style">
										<span class="os-cont">
											gia nhập hội viên
										</span>
									</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="mypageModal modal fade" role="dialog">
	<div class="bs-modal modal-dialog">
		<div class="bsm-cont">
			<button class="mdl-close-btn" data-dismiss="modal">sự đóng cửa</button>
			<div class="bsm-inner">
				<div class="mdl-head">
					<div class="mdl-title">
						<div class="active">
							<p class="en">MY PAGE</p>
							<p class="kr">trang của tôi</p>
						</div>
					</div>
				</div>
				<div class="nav-mdl three-mob">
					<div class="nav-cont">
						<div class="nav-btn active five">
							<button>
								<i class="icon icon-Rolodex"></i>
								<p class="text">thông tin của tôi</p>
							</button>
						</div>
						<div class="nav-btn">
							<button class="mp">
								<i class="icon icon-Message"></i>
								<p class="text">lời nhắn</p>
							</button>
						</div>
						<div class="nav-btn">
							<button class="mp">
								<i class="icon icon-Medal"></i>
								<p class="text">chuyển đổi tiền thưởng</p>
							</button>
						</div>
						<div class="nav-btn">
							<button>
								<i class="icon icon-ClosedLock"></i>
								<p class="text">thay đổi mật khẩu</p>
							</button>
						</div>
					</div>
				</div>
				<div class="modal-body">
					<div class="tab-mdl active">
						<div class="mypage-nav">
							<button class="account-link active"><i class="icon icon-User"></i> thông tin của tôi</button>
							<button class="history-link"><i class="icon icon-List"></i> chi tiết nhập và rút tiền</button>
						</div>
						<div class="mypage-tabs">
							<div class="mp-tab active">
								<div class="form-container">
									<div class="form-group">
										<div class="labels">
											<p>ID (tên) :</p>
										</div>
										<div class="infos">
											<p>
                        @if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
                        {{ Auth::user()->username }}
                        @endif
                      </p>
										</div>
									</div>
									<div class="form-group">
										<div class="labels">
											<p>số tiền dự trữ :</p>
										</div>
										<div class="infos">
											<p class="player-balance">
                      @if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
                      {{ number_format(Auth::user()->balance,0)}} WON
                      @endif</p>
										</div>
									</div>
									<div class="form-group">
										<div class="labels">
											<p>thông tin tài khoản đăng ký :</p>
										</div>
										<div class="infos">
											<p>:****:***</p>
										</div>
									</div>
									<div class="form-group">
										<div class="labels">
											<p>sự liên lạc :</p>
										</div>
										<div class="infos">
											<p>
                        @if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
                          @if(Auth::user()->phone != "")
                          {{ Auth::user()->phone }}
                          @else
                          000-0000-0000
                          @endif
                        @endif</p>
										</div>
									</div>
									<div class="form-group">
										<div class="labels">
											<p>thời gian gia nhập :</p>
										</div>
										<div class="infos">
											<p>
                      @if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check())){{ Auth::user()->created_at}}@endif</p>
										</div>
									</div>
								</div>
							</div>

							<div class="mp-tab">
								<table class="bs-table with-depth">
                <colgroup>
                  <col width="55%">
                  <col width="25%">
                  <col width="10%">
                  <col width="10%">
                </colgroup>
									<thead>
										<tr>
											<th>tiêu đề</th>
                      <th>ngày soạn thảo</th>
                      <th>người viết</th>
                      <th></th>
										</tr>
									</thead>
									<tbody class="message-list">
                    @if ($msgs && count($msgs) > 0)
                    @foreach ($msgs as $msg)
                      <tr  class="depth-click" onclick="readMessage('{{$msg->id}}')">
                        <td>{{$msg->title}}</td>
                        <td>{{$msg->created_at}}</td>
                        <td>{{$msg->writer->hasRole('admin')?'người quản lý':'tổng trụ sở chính'}}</td>
                        <td>
                        <button type="button" class="delete-btn" onclick="deleteMessage('{{$msg->id}}')" style="background:transparent;border:none;"><i class="icon icon-Delete"></i></button>
                        </td>
                      </tr>
                      <tr class="dropdown">
                      <td colspan="4">
                        <div class="mess-cont" style="display: none;">
                          <div class="inner">
                            <?php echo $msg->content ?>
                          </div>
                        </div>
                      </td>
                    </tr>
                    @endforeach
                    @else
                      <tr class="depth-click">
                      <td class="text-center" colspan="3">không có lời nhắn</td>
                      </tr>
                    @endif
									</tbody>
								</table>
							</div>

						   <div class="mp-tab">

								<form id="pointFrm" action="javascript:bonusSubmit();" method="POST" data-parsley-validate="" novalidate="">
								<div class="form-container">
									<div class="form-group">
										<div class="labels">
											<p>tiền thưởng sở hữu</p>
										</div>
										<div class="infos">
											<p class="info player-point">{{Auth::check()?number_format(auth()->user()->deal_balance,0) : 0}}WON</p>
										</div>
									</div>
									<div class="form-group">
										<div class="labels">
											<p>số tiền chuyển đổi</p>
										</div>
										<div class="infos">
                      <input type="hidden" value="<?= csrf_token() ?>" name="_token" id="_token">
											<input class="form-control w400" id="pointAmount" data-parsley-type="digits" data-parsley-type-message="giá trị không hợp lệ" data-parsley-pattern="/^[0-9]*$/" data-parsley-pattern-message="giá trị không hợp lệ" data-parsley-trigger="focusin change" data-parsley-required="true" data-parsley-required-message="Hạng mục nhập bắt buộc" data-parsley-min="30000" data-parsley-min-message="Tiền gửi tối thiểu 30.000 won trở lên" placeholder="Có thể từ 30.000 won tiền gửi tối thiểu (tiền gửi đơn vị 10.000 won)" name="point_money" type="text" value="" data-parsley-id="9147"><ul class="parsley-errors-list" id="parsley-id-9147"></ul>
											<div class="btn-grp" style="margin-top: 25px;">
												<button type="button" onclick="addAmount(4,1)">10,000</button>
												<button type="button" onclick="addAmount(4,3)">30,000</button>
												<button type="button" onclick="addAmount(4,5)">50,000</button>
												<button type="button" onclick="addAmount(4,10)">100,000</button>
												<button type="button" onclick="addAmount(4,50)">500,000</button>
												<button type="button" onclick="resetAmount(4)">sự khởi động lại</button>
											</div>
										</div>
									</div>

									<div class="modal-btn-foot">
										<div class="btn-cont">
											<button type="submit" class="green">sự chuyển đổi</button>
											<button type="button" style="display: none;"></button>
										</div>
									</div>
								</div>
								</form>

								<script>
									var df = $("#pointFrm");
									df.submit(function (e) {
										var amount = parseInt($("[name=point_money]").val());
										if (amount % 10000 > 0) {
											alert_error('Hãy nhập số tiền gửi theo đơn vị 10.000 won');
											e.preventDefault();
										} else {
											$(this).parsley().validate();
											if ($(this).parsley().isValid()) {
												$('.wrapper_loading').removeClass('hidden');
											}
										}
									});
								</script>
							</div>


							<div class="mp-tab">
								<form id="passwordChangeForm" action="javascript:goMypage();" method="POST" data-parsley-validate="" novalidate="">
									<div class="form-container">
										<div class="form-group">
											<div class="labels">
												<p>mật khẩu hiện có</p>
											</div>
											<div class="infos">
												<input type="password" name="cur_pwd" data-parsley-trigger="focusout" data-parsley-remote-message="Mật khẩu không khớp" data-parsley-remote="/player/current/password" data-parsley-required="true" data-parsley-remote-validator="reverse" data-parsley-required-message="Hạng mục nhập bắt buộc" data-parsley-id="7778"><ul class="parsley-errors-list" id="parsley-id-7778"></ul>
											</div>
										</div>
										<div class="form-group">
											<div class="labels">
												<p>mật khẩu để thay đổi</p>
											</div>
											<div class="infos">
												<input type="password" id="new_password" name="new_pwd" data-parsley-trigger="focusout" data-parsley-minlength="4" data-parsley-minlength-message="ít nhất là 4 chữ cái" data-parsley-maxlength="12" data-parsley-maxlength-message="tối đa không quá 12 ký tự" data-parsley-required="true" data-parsley-required-message="Hạng mục nhập bắt buộc" data-parsley-id="2499"><ul class="parsley-errors-list" id="parsley-id-2499"></ul>
											</div>
										</div>
										<div class="form-group">
											<div class="labels">
												<p>Kiểm tra mật khẩu</p>
											</div>
											<div class="infos">
												<input type="password" name="new_pwd_confirm" data-parsley-trigger="focusout" data-parsley-equalto="#new_password" data-parsley-equalto-message="Mật khẩu không khớp" data-parsley-required="true" data-parsley-required-message="Hạng mục nhập bắt buộc" data-parsley-id="6448"><ul class="parsley-errors-list" id="parsley-id-6448"></ul>
											</div>
										</div>
										<div class="modal-btn-foot">
											<div class="btn-cont">
												<button type="submit" class="over-style red">
													<span class="os-cont"><i class="fa fa-check-square"></i> sự thay đổi</span>
												</button>
												<button type="reset" class="over-style" data-dismiss="modal">
													<span class="os-cont"><i class="fa fa-window-close"></i> sự hủy bỏ</span>
												</button>
											</div>
										</div>
									</div>
								</form>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="subpgModal modal fade in" role="dialog" style="display: none; padding-right: 17px;">
	<div class="bs-modal modal-dialog">
		<div class="bsm-cont sub-page">
			<button class="mdl-close-btn" data-dismiss="modal">sự đóng cửa</button>
			<div class="bsm-inner">
				<div class="mdl-head">
					<div class="mdl-title">
						<div class="deposit">
							<p class="en">DEPOSIT</p>
							<p class="kr">đăng ký gửi tiền</p>
						</div>
						<div class="withdraw">
							<p class="en">WITHDRAW</p>
							<p class="kr">đơn xin rút tiền</p>
						</div>
						<div class="event active">
							<p class="en">EVENT</p>
							<p class="kr">sự kiện</p>
						</div>
						<div class="notice">
							<p class="en">NOTICE</p>
							<p class="kr">thông báo</p>
						</div>
					</div>
				</div>

				<div class="nav-mdl">
					<div class="nav-cont">
						<div class="nav-btn">
							<button class="deposit-link active">
								<i class="icon icon-Bag"></i>
								<p class="text">đăng ký gửi tiền</p>
							</button>
						</div>
						<div class="nav-btn">
							<button class="withdraw-link">
								<i class="icon icon-Dollar"></i>
								<p class="text">đơn xin rút tiền</p>
							</button>
						</div>
						<div class="nav-btn active">
							<button class="event-link">
								<i class="icon icon-Agenda"></i>
								<p class="text">sự kiện</p>
							</button>
						</div>
						<div class="nav-btn">
							<button class="notice-link">
								<i class="icon icon-Megaphone"></i>
								<p class="text">thông báo</p>
							</button>
						</div>
					</div>
				</div>

				<div class="modal-body">

					<!----------------------------- DEPOSIT ----------------------------->
						
          <div class="tab-mdl deposit">

          <form action="javascript:depositSubmit()" method="post" id="depoFrm" name="depoFrm" data-parsley-validate="" novalidate="">            
            <div class="form-container">
              <div class="form-group">
                <div class="labels">
                  <p>thông tin hội viên</p>
                </div>
                <div class="infos">
                  <p>@if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
                        {{ Auth::user()->username }}
                        @endif</p>
                </div>
              </div>
              <div class="form-group">
									<div class="labels">
										<p>tên ngân hàng</p>
									</div>
									<div class="infos">
										<label>
											<select id="bankname" class="form-control " data-parsley-required="true" data-parsley-required-message="Hạng mục nhập bắt buộc" name="bankname" data-parsley-id="5166" value="">
												<option value="">--lựa chọn ngân hàng--</option>
                        @foreach(\VanguardLTE\User::$values["banks"] AS $val)
                          @if($val != "")
                            <option value="{{$val}}" {{Auth::check() && auth()->user()->bank_name == $val?'selected':''}}>{{$val}}</option>
                          @endif
                        @endforeach
											</select><ul class="parsley-errors-list" id="parsley-id-5166"></ul>
										</label>
									</div>
								</div>
								<div class="form-group">
									<div class="labels">
										<p>số tài khoản</p>
									</div>
									<div class="infos">
										<input id="accountnumber" class="form-control " data-parsley-type="digits" data-parsley-type-message="Chỉ có thể nhập số thôi" data-parsley-pattern="/^[0-9]*$/" data-parsley-pattern-message="giá trị không hợp lệ" maxlength="30" data-parsley-trigger="change" data-parsley-maxlength="30" data-parsley-maxlength-message="Hãy nhập ít hơn 30 ký tự" data-parsley-required="true" data-parsley-required-message="Hạng mục nhập bắt buộc" autocomplete="off" name="accountnumber" type="text" value="{{Auth::check()?auth()->user()->account_no:''}}" data-parsley-id="1905"><ul class="parsley-errors-list" id="parsley-id-1905"></ul>
									</div>
								</div>
              <div class="form-group">
                <div class="labels">
                  <p>tên người gửi tiền</p>
                </div>
                <div class="infos">
                  <input id="recommender" class="form-control "  data-parsley-trigger="change" data-parsley-required="true" data-parsley-required-message="Hạng mục nhập bắt buộc" name="recommender" type="text" value="{{Auth::check()?auth()->user()->recommender:''}}" data-parsley-id="0017"><ul class="parsley-errors-list" id="parsley-id-0017"></ul>
                </div>
              </div>
              <div class="form-group">
                <div class="labels">
                  <p>số tiền gửi vào ngân hàng</p>
                </div>
                <div class="infos">
                  <input class="form-control w400" id="depositAmount" data-parsley-type="digits" data-parsley-type-message="giá trị không hợp lệ" data-parsley-pattern="/^[0-9]*$/" data-parsley-pattern-message="giá trị không hợp lệ" data-parsley-trigger="focusin change" data-parsley-required="true" data-parsley-required-message="Hạng mục nhập bắt buộc" data-parsley-min="30000" data-parsley-min-message="Tiền gửi tối thiểu 30.000 won trở lên" placeholder="Có thể từ 30.000 won tiền gửi tối thiểu (tiền gửi đơn vị 10.000 won)" name="money" type="text" value="" data-parsley-id="0361"><ul class="parsley-errors-list" id="parsley-id-0361"></ul>
                  <div class="btn-grp" style="margin-top: 25px;">
                    <button type="button" onclick="addAmount(1,1)">10,000</button>
                    <button type="button" onclick="addAmount(1,3)">30,000</button>
                    <button type="button" onclick="addAmount(1,5)">50,000</button>
                    <button type="button" onclick="addAmount(1,10)">100,000</button>
                    <button type="button" onclick="addAmount(1,50)">500,000</button>
                    <button type="button" onclick="resetAmount(1)">sự khởi động lại</button>
                  </div>
                </div>
              </div>
              <div class="deposit-ask">
                <button type="button" onclick="askAccount();">
                  <i class="icon icon-Info"></i>
                  <span>thuộc tài khoản gửi tiền</span>
                </button>
                <p id="bankinfo">* Nhất định phải hỏi tài khoản khi gửi tiền!</p>
              </div>
            </div>
            <div class="modal-btn-foot">
              <div class="btn-cont">
                <button type="submit" class="green">tiền gửi</button>
                <button type="reset" class="blue" data-dismiss="modal">sự hủy bỏ</button>
              </div>
            </div>
          </form>

          
        </div>

    <script>
      var df = $("#depoFrm");
      df.submit(function (e) {
        var amount = parseInt($("[name=money]").val());
        if (amount % 10000 > 0) {
          alert_error('Hãy nhập số tiền gửi theo đơn vị 10.000 won');
          e.preventDefault();
        } else {
          $(this).parsley().validate();
          if ($(this).parsley().isValid()) {
            $('.wrapper_loading').removeClass('hidden');
          }
        }
      });
    </script>

					<!----------------------------- WITHDRAW ----------------------------->
						
    <div class="tab-mdl withdraw">
      <form action="javascript:withdrawSubmit()" method="post" id="withFrm" name="withFrm" data-parsley-validate="" novalidate="">
        <input type="hidden" id="wt_gamecategory" name="wt_gamecategory" value="XI Gaming">
        <input type="hidden" name="wt_curmoney" id="wt_curmoney" value="0">

        <div class="form-container">
          <div class="form-group">
            <div class="labels">
              <p>số tiền dự trữ</p>
            </div>
            <div class="infos">
              <p class="player-balance">
              @if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
              {{ number_format(Auth::user()->balance,0)}} WON
              @endif
              </p>
            </div>
          </div>
          <div class="form-group">
            <div class="labels">
              <p>tên ngân hàng</p>
            </div>
            <div class="infos">
              <label>
                <select id="bankname" class="form-control " data-parsley-required="true" data-parsley-required-message="Hạng mục nhập bắt buộc" name="bankname" data-parsley-id="7298">
                  <option value="">--lựa chọn ngân hàng--</option>
                  @foreach(\VanguardLTE\User::$values["banks"] AS $val)
                    @if($val != "")
                    <option value="{{$val}}" {{Auth::check() && auth()->user()->bank_name == $val?'selected':''}}>{{$val}}</option>
                    @endif
                  @endforeach
                </select><ul class="parsley-errors-list" id="parsley-id-7298"></ul>
              </label>
            </div>
          </div>
          <div class="form-group">
            <div class="labels">
              <p>số tài khoản</p>
            </div>
            <div class="infos">
              <input id="accountnumber" class="form-control " data-parsley-type="digits" data-parsley-type-message="Chỉ có thể nhập số thôi" data-parsley-pattern="/^[0-9]*$/" data-parsley-pattern-message="giá trị không hợp lệ" maxlength="30" data-parsley-trigger="change" data-parsley-maxlength="30" data-parsley-maxlength-message="Hãy nhập ít hơn 30 ký tự" data-parsley-required="true" data-parsley-required-message="Hạng mục nhập bắt buộc" autocomplete="off" name="accountnumber" type="text" value="{{Auth::check()? auth()->user()->account_no : ''}}" data-parsley-id="1905"><ul class="parsley-errors-list" id="parsley-id-1905"></ul>
            </div>
          </div>
          <div class="form-group">
            <div class="labels">
              <p>tên người rút tiền</p>
            </div>
            <div class="infos">
              <input id="recommender" class="form-control "  data-parsley-trigger="change" data-parsley-required="true" data-parsley-required-message="Hạng mục nhập bắt buộc" name="recommender" type="text" value="{{Auth::check()? auth()->user()->recommender:''}}" data-parsley-id="0017"><ul class="parsley-errors-list" id="parsley-id-0017"></ul>
            </div>
          </div>

          <div class="form-group">
            <div class="labels">
              <p>số tiền rút ra</p>
            </div>
            <div class="infos">
              <input class="form-control w400 withdrawal_amount" id="withdrawalAmount" data-parsley-type="digits" data-parsley-type-message="giá trị không hợp lệ" data-parsley-pattern="/^[0-9]*$/" data-parsley-pattern-message="giá trị không hợp lệ" data-parsley-trigger="focus change" data-parsley-required="true" data-parsley-required-message="Hạng mục nhập bắt buộc" data-parsley-min="10000" data-parsley-min-message="Tiền rút tối thiểu trên 10.000 won" placeholder="Có thể rút tiền tối thiểu từ 10.000 won (10.000 won)" name="money" type="text" value="" data-parsley-id="5344"><ul class="parsley-errors-list" id="parsley-id-5344"></ul>
              <div class="btn-grp" style="margin-top: 25px;">
                <button type="button" onclick="addAmount(2,1)">10,000</button>
                <button type="button" onclick="addAmount(2,3)">30,000</button>
                <button type="button" onclick="addAmount(2,5)">50,000</button>
                <button type="button" onclick="addAmount(2,10)">100,000</button>
                <button type="button" onclick="addAmount(2,50)">500,000</button>
                <button type="button" onclick="resetAmount(2)">sự khởi động lại</button>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-btn-foot">
          <div class="btn-cont">
            <button type="submit" class="over-style red">
              <span class="os-cont">sự rút tiền</span>
            </button>
            <button type="reset" class="over-style" data-dismiss="modal">
              <span class="os-cont">sự hủy bỏ</span>
            </button>
          </div>
        </div>
      </form>
    </div>

    <script>
      var wf = $("#withFrm");
      wf.submit(function (e) {
        if($("#wt_gamecategory").val() == "XI Gaming") {
          $("#wt_curmoney").val( $("#ximoney").val() );
        } else if($("#wt_gamecategory").val() == "M Gaming") {
          $("#wt_curmoney").val($("#mmoney").val());
        }

        var amount = parseInt($("[name=wt_money]").val());
        if (amount % 10000 > 0) {
          alert_error('Vui lòng nhập số tiền rút ra theo đơn vị 10.000 won.');
          e.preventDefault();
        } else {
          $(this).parsley().validate();
          if ($(this).parsley().isValid()) {
            $('.wrapper_loading').removeClass('hidden');
          }
        }
      });
    </script>

					<!----------------------------- EVENT ----------------------------->
    <div class="tab-mdl event event-section active">
        <table class="bs-table event_table">
          <colgroup>
            <col width="10%">
            <col width="50%">
            <col width="40%">
          </colgroup>
          <thead>
            <tr>
              <th>con số</th>
              <th>tiêu đề</th>
              <th>ngày soạn thảo</th>
            </tr>
          </thead>
          <tbody>


          <tr>
            <td class="text-center" colspan="3">không có bài đăng</td>
          </tr>


          </tbody>
        </table>
    </div>



<!----------------------------- EVENT VIEW ----------------------------->

    <div class="tab-mdl event-view event-view-section">
    </div>
              <!----------------------------- NOTICE ----------------------------->
    <div class="tab-mdl notice notice-section">
      <table class="bs-table notice-table">
        <colgroup>
          <col width="10%">
          <col width="50%">
          <col width="40%">
        </colgroup>
        <thead>
          <tr>
            <th>con số</th>
            <th>tiêu đề</th>
            <th>ngày soạn thảo</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-center" colspan="3"></td>
          </tr>
        </tbody>
      </table>
    </div>

    <!----------------------------- NOTICE VIEW ----------------------------->

    <div class="tab-mdl notice-view notice-view-section">
    </div>

				</div>


			</div>
		</div>
	</div>
</div>

<div class="gamelistModal modal fade" role="dialog">
    <div class="bs-modal modal-dialog">
        <div class="bsm-cont">
            <button type="button" class="mdl-close-btn" data-dismiss="modal">sự đóng cửa</button>
            <div class="bsm-inner">
                <div class="mdl-head">
                    <div class="mdl-title">
                        <div class="active">
                            <!-- <p class="en tp-name">TPG</p> -->
                            <p class="kr tp-name"></p>
                        </div>
                    </div>
                </div>
                <div class="nav-mdl three-mob five">
                    <div class="nav-cont">
                        <div class="nav-btn active">
                            <button type="button" onclick="gameDivision($(this))" class="alltab" data-target=".a-tab">
                                <i class="icon icon-Controller"></i>
                                <p class="text">toàn bộ</p>
                            </button>
                        </div>
                        <div class="nav-btn">
                            <button type="button" onclick="gameDivision($(this))" data-target=".p-tab">
                                <i class="icon icon-Controller"></i>
                                <p class="text">sự nổi tiếng</p>
                            </button>
                        </div>
                        <div class="nav-btn">
                            <button type="button" onclick="gameDivision($(this))" data-target=".s-tab">
                                <i class="icon icon-Controller"></i>
                                <p class="text">khe cắm</p>
                            </button>
                        </div>
                        <div class="nav-btn">
                            <button type="button" onclick="gameDivision($(this))" data-target=".t-tab">
                                <i class="icon icon-Controller"></i>
                                <p class="text">cái bàn</p>
                            </button>
                        </div>
                        <div class="nav-btn">
                            <button type="button" onclick="gameDivision($(this))" data-target=".e-tab">
                                <i class="icon icon-Controller"></i>
                                <p class="text">đàn guitar</p>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="tab-mdl slot-section active">
                        <div class="gamelist-cont Coolfire">
                          
                            <div class="gl-inner slots a-tab hidden">

                            </div> 
                        </div>
                        <div class="gamelist-cont Coolfire">
                          <div class="gl-inner slots p-tab hidden">
                          </div>
                        </div>  
                        <div class="gamelist-cont Coolfire">
                          <div class="gl-inner slots s-tab hidden">
                          </div>
                        </div>  
                        <div class="gamelist-cont Coolfire">
                          <div class="gl-inner slots t-tab hidden">
                          </div>
                        </div>  
                        <div class="gamelist-cont Coolfire">
                          <div class="gl-inner slots e-tab hidden">
                          </div>
                        </div>  
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="historyModal modal fade" role="dialog">
	<div class="bs-modal modal-dialog">
		<div class="bsm-cont">
			<button class="mdl-close-btn" data-dismiss="modal">sự đóng cửa</button>
			<div class="bsm-inner">
				<div class="mdl-head">
					<div class="mdl-title">
						<div class="active">
							<p class="en">MY PAGE</p>
							<p class="kr">trang của tôi</p>
						</div>
					</div>
				</div>
				<div class="nav-mdl three-mob three">
					<div class="nav-cont">
						<div class="nav-btn active" data-id="DP" data-target=".deposit-list">
							<button>
								<i class="icon icon-Bag"></i>
								<p class="text">tiền gửi</p>
							</button>
						</div>
						<div class="nav-btn" data-id="WT" data-target=".withdraw-list">
							<button>
								<i class="icon icon-Dollar"></i>
								<p class="text">sự rút tiền</p>
							</button>
						</div>
						<div class="nav-btn" data-id="BH" data-target=".bonuses-list">
							<button>
								<i class="icon icon-DiamondRing"></i>
								<p class="text">chi tiết tiền thưởng</p>
							</button>
						</div>
					</div>
				</div>
				<div class="modal-body">
					<div class="tab-mdl active">
						<div class="mypage-nav">
							<button class="account-link"><i class="icon icon-User"></i> thông tin của tôi</button>
							<button class="history-link active"><i class="icon icon-List"></i> chi tiết nhập và rút tiền</button>
						</div>
						<div class="mypage-tabs">
							<div class="mp-tab deposit-list active">
								<table class="bs-table deposit-tb">
									<colgroup>
										<!--<col width="15%"/>-->
										<col width="15%">
										<col width="30%">
										<col width="15%">
										<col width="25%">
									</colgroup>
									<thead>
									<tr>
										<!--<th>sự phân biệt</th>-->
										<th>sự phân biệt</th>
										<th>số tiền</th>
										<th>trạng thái</th>
										<th>ngày tháng</th>
									</tr>
									</thead>
									<tbody>
									<tr>
										<td colspan="5"></td>
									</tr>
									</tbody>
								</table>
							</div>
							<div class="mp-tab withdraw-list">
								<table class="bs-table withdraw-tb">
									<colgroup>
										<!--<col width="15%"/>-->
										<col width="15%">
										<col width="30%">
										<col width="15%">
										<col width="25%">
									</colgroup>
									<thead>
									<tr>
										<!--<th>sự phân biệt</th>-->
										<th>sự phân biệt</th>
										<th>số tiền</th>
										<th>trạng thái</th>
										<th>ngày tháng</th>
									</tr>
									</thead>
									<tbody>
									<tr>
										<td colspan="5"></td>
									</tr>
									</tbody>
								</table>
							</div>
							<div class="mp-tab bonuses-list">
								<table class="bs-table bonus-tb">
									<colgroup>
										<col width="26%">
										<col width="12%">
										<col width="20%">
										<col width="12%">
										<col width="10%">
										<col width="20%">
									</colgroup>
									<thead>
									<tr>
										<th>chi tiết tiền thưởng</th>
										<th>chủng loại</th>
										<th>số tiền</th>
										<th>số tiền cuộn tròn</th>
										<th>trạng thái</th>
										<th>ngày tháng</th>
									</tr>
									</thead>
									<tbody>
									<tr>
										<td colspan="6"></td>
									</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@if (count($noticelist) > 0)
@foreach ($noticelist as $notice)
<div class="pop1" id="pop{{$notice->id}}" style="left:{{300+$loop->index*350}}px;">
    <?php echo $notice->content ?>
    <p></br></p>
    <div style="position:absolute;left:5px;bottom:5px;width:190px;background-color:transparent;color:white;cursor: hand;display:content">
      <input type="checkbox" name="notice1" id="notice{{$notice->id}}" value="" style="width:20px;">
      <label for="notice{{$notice->id}}">Cả ngày hôm nay không mở cửa  <span style="cursor:pointer;" onclick="closeWin(document.getElementById('pop{{$notice->id}}'), {{$notice->id}}, document.getElementById('notice{{$notice->id}}').checked);">[sự đóng cửa]</span></label>
    </div>
  </div>
@endforeach
@endif
@yield('content')

@yield('popup')

<script type='text/javascript'>
var xigame_id = '488';
@if (count($noticelist) > 0)
  @foreach ($noticelist as $notice)
    if ( getCookie( "divpopup0{{$notice->id}}" ) == "check" ) {
      closeWin2(document.getElementById('pop{{$notice->id}}'));
    }
  @endforeach
@endif

  function loginSubmit() {
      
      if ($("input[name='userid']").val() == "ID" || $("input[name='userid']").val() == "") {
          alert("Vui lòng nhập ID đăng nhập");
          frm.userid.focus();
          return;
      }
      if ($("input[name='password']").val() == "******" || $("input[name='password']").val() == "") {
          alert("Vui lòng nhập mật khẩu");
          frm.password.focus();
          return;
      }

      // frm.action = "/login/login.asp";
      // frm.submit();
      var username = $("input[name='userid']").val();
      var password = $("input[name='password']").val();

      var formData = new FormData();
      formData.append("_token", $("#_token").val());
      formData.append("username", username);
      formData.append("password", password);

      $.ajax({
          type: "POST",
          url: "/api/login",
          data: formData,
          processData: false,
          contentType: false,
          cache: false,
          async: false,
          success: function (data, status) {
              if (data.error) {
                  alert(data.msg);
              }

              location.reload();
          },
          error: function (err, xhr) {
          }
      });
  }

  function joinSubmit() {

      $.ajax({
				type: 'POST',
				url: "{{route('frontend.api.join')}}",
				data: {	username: $("#joinForm #membername").val(),
						password: document.getElementById("memberpw").value,
						tel1: $("#joinForm #mobile").val(),
						tel2: '',
						tel3: '',
						bank_name: $("#joinForm #bankname").val(),
						recommender: $("#joinForm #mb_account_name").val(),
						account_no: $("#joinForm #accountnumber").val(),
						friend: $("#joinForm #recomcode_id").val()},
				cache: false,
				async: false,
				success: function (data) {
					if (data.error) {
						alert(data.msg);
						return;
					}
					else{
            alert_ok_reload(data.msg, "/");
					}

				},
				error: function (err, xhr) {
					alert(err.responseText);
				}
			});
  }
  function depositSubmit() {
    if (!depositAccountRequested)
    {
      alert_error('Yêu cầu tài khoản gửi tiền trước');
      return;
    }
    $.ajax({
        type: "POST",
        url: "/api/addbalance",
        data: { 
          accountName: $("#depoFrm #recommender").val(),
          bank:$("#depoFrm #bankname").val(),
          no:$("#depoFrm #accountnumber").val(),
          money: $("#depoFrm #depositAmount").val() },
        cache: false,
        async: false,
        success: function (data) {
            if (data.error) {
                alert_error(data.msg);
                if (data.code == "001") {
                    location.reload();
                } else if (data.code == "002") {
                    $("#depoFrm #recommender").focus();
                }
                return;
            }

            alert_ok("Đã hoàn thành việc đăng ký sạc pin");
            // $("#deposit .btn3_2").click();
        },
        error: function (err, xhr) {
          alert_error(err.responseText);
        },
    });
  }

  function withdrawSubmit() {
    $.ajax({
        type: "POST",
        url: "/api/outbalance",
        data: { 
          accountName: $("#withFrm #recommender").val(),
          bank:$("#withFrm #bankname").val(),
          no:$("#withFrm #accountnumber").val(),
          money: $("#withFrm #withdrawalAmount").val() },
        cache: false,
        async: false,
        success: function (data) {
            if (data.error) {
                alert_error(data.msg);
                if (data.code == "001") {
                    location.reload();
                } else if (data.code == "002") {
                    $("#withFrm #recommender").focus();
                }
                return;
            }

            alert_ok("Đơn xin đổi tiền đã hoàn thành");
            // $("#deposit .btn3_2").click();
        },
        error: function (err, xhr) {
          alert_error(err.responseText);
        },
    });
  }

  function bonusSubmit() {
    var _token = $('#pointFrm _token').val();
    var _dealsum = $('#pointFrm #pointAmount').val();

    $.ajax({
        type: 'POST',
        url: '/api/convert_deal_balance',
        data: { _token: _token, summ: _dealsum },
        cache: false,
        async: false,
        success: function (data) {
            if (data.error) {
                alert_error(data.msg);
                return;
            }
            alert_ok_reload('Số tiền thu được đã được chuyển thành số tiền dự trữ', '/');
        },
        error: function (err, xhr) {
            alert(err.responseText);
        }
    });
}

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
  });

  
<!--
function setCookie( name, value, expiredays ) {
    var todayDate = new Date();
    todayDate.setDate( todayDate.getDate() + expiredays );
    document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
}

function closeWin( popup, num, closeflag ) {
	if(closeflag == true) setCookie( "divpopup0" + num, "check" , 1);
	popup.style.display='none';
}

function closeWin2( popup ) {
	popup.style.display='none';
}

function getCookie( name ) {

	var nameOfCookie = name + "=";
    var x = 0;

	//alert(document.cookie);
	while ( x <= document.cookie.length ) {
		var y = (x+nameOfCookie.length);

		if ( document.cookie.substring( x, y ) == nameOfCookie ) {
			if ( (endOfCookie=document.cookie.indexOf( ";", y )) == -1 )
				endOfCookie = document.cookie.length;
	        return unescape( document.cookie.substring( y, endOfCookie ) );
	    }
	    x = document.cookie.indexOf( " ", x ) + 1;

	    if ( x == 0 ) break;
	}
	return "";
}

var img_L = 0;
var img_T = 0;
var targetObj;

function getLeft(o) {
	 return parseInt(o.style.left.replace('px', ''));
}
function getTop(o) {
	 return parseInt(o.style.top.replace('px', ''));
}

// 이미지 움직이기
function moveDrag(e) {
	 var e_obj = window.event? window.event : e;
	 var dmvx = parseInt(e_obj.clientX + img_L);
	 var dmvy = parseInt(e_obj.clientY + img_T);
	 targetObj.style.left = dmvx +"px";
	 targetObj.style.top = dmvy +"px";
	 return false;
}

// 드래그 시작
function startDrag(e, obj) {
	 targetObj = obj;
	 var e_obj = window.event? window.event : e;
	 img_L = getLeft(obj) - e_obj.clientX;
	 img_T = getTop(obj) - e_obj.clientY;

	 document.onmousemove = moveDrag;
	 document.onmouseup = stopDrag;
	 if(e_obj.preventDefault)e_obj.preventDefault();
}

// 드래그 멈추기
function stopDrag() {
	 document.onmousemove = null;
	 document.onmouseup = null;
}
-->

</script>

</body>
</html>