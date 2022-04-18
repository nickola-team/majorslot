
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="icon" href="/frontend/winnerscn/img/favicon.ico" >
    <meta
      name="viewport"
      content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=0"
    />
    <meta name="theme-color" content="#000000" />
      <script type="text/javascript" src="/frontend/winnerscn/js/app.js"></script>
    <script type="text/javascript" src="/frontend/winnerscn/js/jquery.number.min.js"></script>
    <script type="text/javascript" src="/frontend/winnerscn/js/parsley.min.js"></script>
    <script type="text/javascript" src="/frontend/winnerscn/js/parsley.remote.min.js"></script>
    <script type="text/javascript" src="/frontend/winnerscn/js/lazyload.min.js"></script>
    <script type="text/javascript" src="/frontend/winnerscn/js/action.js"></script>

    <script src="/frontend/winnerscn/js/TweenMax.min.js"></script>
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
            "最适合铬。 希望使用Chrome浏览器。"
          ),
          (window.location.href = "https://www.google.com/intl/ko/chrome/"));
      }
    </script>

    <link rel="stylesheet" href="/frontend/winnerscn/css/font-icon.css">
    <link rel="stylesheet" href="/frontend/winnerscn/css/app.css">
    <link rel="stylesheet" href="/frontend/winnerscn/css/style7d93.css?v=2368">
    <link rel="stylesheet" href="/frontend/winnerscn/css/galaxy5358.css?v=1540">
    <link rel="stylesheet" href="/frontend/winnerscn/css/themes/default.min.css">


    <!-- Sweet-Alert  -->
    <script type="text/javascript" src="/frontend/winnerscn/js/sweetalert/sweetalert.min.js"></script>
    <script type="text/javascript" src="/frontend/winnerscn/js/sweetalert/jquery.sweet-alert.custom.js"></script>
    <link rel="stylesheet" href="/frontend/winnerscn/js/sweetalert/sweetalert.css" type="text/css">

    <script type="text/javascript" src="/frontend/winnerscn/js/menuafee.js?v=9337"></script>
	<script type="text/javascript" src="/frontend/winnerscn/js/maina5e0.js?v=3289"></script>
	<script type="text/javascript" src="/frontend/winnerscn/js/script838b.js?v=1433"></script>
   
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
					<img src="/frontend/winnerscn/img/logo.png">
				</div>
			</a>

      <div class="nav-cont">
        <div class="link-main">
          <ul class="bs-ul">
            @if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
            @if ($unreadmsg>0)
            <li class="deposit-link ">
               <a href="javascript:void(0);"  onclick="alert_error('请确认纸条');">
            @else
            <li class="deposit-link subpg-link">
              <a href="javascript:void(0);">
            @endif
            @else
            <li class="deposit-link ">
               <a href="javascript:void(0);"  onclick="alert_error('需要登陆');">
            @endif
                <div class="icon-cont">
                  <img src="/frontend/winnerscn/img/icon/deposit.png">
                </div>
                <span>存款申请</span>
              </a>
            </li>
           
            @if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
            @if ($unreadmsg>0)
            <li class="deposit-link ">
               <a href="javascript:void(0);"  onclick="alert_error('请确认纸条');">
            @else
            <li class="withdraw-link subpg-link">
              <a href="javascript:void(0);" >
            @endif
            @else
            <li class="withdraw-link ">
               <a href="javascript:void(0);"  onclick="alert_error('需要登陆');">
            @endif
                <div class="icon-cont">
                  <img src="/frontend/winnerscn/img/icon/withdraw.png">
                </div>
                <span>取款申请</span>
              </a>
            </li>
           
           
              @if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
              <li class="notice-link subpg-link">
                <a href="javascript:void(0);">
              @else
              <li class="notice-link">
                <a href="javascript:void(0);"  onclick="alert_error('需要登陆');">
              @endif
                <div class="icon-cont">
                  <img src="/frontend/winnerscn/img/icon/notice.png">
                </div>
                <span>公告</span>
              </a>
            </li>
            
              @if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
              <li class="mypage-link">
                <a href="javascript:void(0);">
              @else
              <li>
                <a href="javascript:void(0);"  onclick="alert_error('需要登陆');">
              @endif
                <div class="icon-cont">
                  <img src="/frontend/winnerscn/img/icon/mypage.png">
                </div>
                <span>我的页面</span>
              </a>
            </li>
            <li>
              <a href="http://cn.hero-sl.com/"><img src="/frontend/winnersvi/img/CN.png"></a><a href="http://vi.hero-sl.com/"><img src="/frontend/winnersvi/img/VN.png"></a>
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
                      携带金币 :
                      </span>
                    </div>
                    <div class="info">
                      <p class="player-balance" style="margin:0"> {{ number_format(Auth::user()->balance,0) }}WON</p>
                    </div>
                  </div>
                  <div class="al-cont point-info-btn" data-toggle="modal" data-target=".mypageModal">
                    <div class="labels">
                      <span class="inner">
                      红包 :
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
                      <i class="fa fa-envelope"></i> 纸条
                      <span class="mess-count" style="animation: letter_anim 0s linear infinite;">{{$unreadmsg}}</span>
                    </button>
                    <button class="logout-btn red" onclick="goLogout();"><i class="fa fa-sign-out-alt"></i> 退出</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @else
          <div class="before-login active">
            <button class="login" data-toggle="modal" data-target=".loginModal"><i class="icon icon-Starship"></i> 登录</button>
            <button class="join" data-toggle="modal" data-target=".joinModal"><i class="icon icon-Planet"></i> 注册</button>
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
      <img class="fireball-orange" src="/frontend/winnerscn/img/banner/fireball-orange.png">
      <!-- <img class="fireball-yellow" src="img/banner/fireball-yellow.png"> -->
      <!-- <img class="wolf" src="img/banner/wolf.png"> -->
      <!-- <img class="glow" src="img/banner/glow.png"> -->
      <img class="king" src="/frontend/winnerscn/img/banner/char-1eccb.png?3">
      <!-- <img class="robot" src="img/banner/robot.png"> -->
      <!-- <img class="diamond" src="img/banner/diamond.png"> -->
      <div class="sky-container">
        <div class="none"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
      </div>
      <img class="coins" src="/frontend/winnerscn/img/banner/coins.png">
      <!-- <img class="girl" src="img/banner/girl.png"> -->
      <img class="rich-man" src="/frontend/winnerscn/img/banner/char-2c81e.png?2">
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
              <p class="title">欢迎来到  </p>
              <p class="sub">Winner's Pro</p>
            </div>
          </div>
        </div>
        <div class="item active">
          <div class="text-cont">
            <div class="inner">
              <p class="title">亚洲最高的回收率 </p>
              <p class="sub">行业最高奖金 !!</p>
            </div>
          </div>
        </div>
        <div class="item">
          <div class="text-cont">
            <div class="inner">
              <p class="title">每天都会爆裂的Jackpot !</p>
              <p class="sub"> 和Winner's Pro一起吧 !</p>
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
      <span class="a_style">Winners CASINO</span>
    </div>

    <div class="slot-container">

    @if ($categories && count($categories))
				@foreach($categories AS $index=>$category)
					@if($category->provider=='gac')

          @if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
            @if ($unreadmsg>0)
            <a href="javascript:void(0);" class="slot-btn"  onclick="alert_error('请确认纸条');">
            @else
            <a href="javascript:void(0);" class="slot-btn" onclick=" getSlotGames('{{ $category->trans->title }}', '{{ $category->href }}', 0)">
            @endif
          @else
          <a href="javascript:void(0);" class="slot-btn"  onclick="alert_error('需要登陆');">
          @endif
            <div class="sb-inner">
              <div class="main-cont">
                <div class="inner">
                  <img class="main-img" src="/frontend/winnerscn/img/slot/{{ $category->title.'.jpg' }}">
                  <button class="start-text">开始游戏</button>
                </div>
              </div>
              <button class="name-btn">
                {{ $category->title }}
              </button>
              <img class="plate" src="/frontend/winnerscn/img/bg/slot-plate.png">
            </div>
            <div class="icon-cont">
              <img src="/frontend/winnerscn/img/slot-icon2/{{ $category->title }}.png" />
            </div>
          </a>     

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
                  <img class="main-img" src="/frontend/winnerscn/img/slot/Pragmatic Play.jpg">
                  <button class="start-text">开始游戏</button>
                </div>
              </div>
              <button class="name-btn">
                SlotGames
              </button>
              <img class="plate" src="/frontend/winnerscn/img/bg/slot-plate.png">
            </div>
            <div class="icon-cont">
              <img src="/frontend/winnerscn/img/slot-icon2/Hot.png" />
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
        <img src="/frontend/winnerscn/img/slot-icon/Blueprint.png">
        <img src="/frontend/winnerscn/img/slot-icon/Booongo.png">
        <img src="/frontend/winnerscn/img/slot-icon/Endorphina.png">
        <img src="/frontend/winnerscn/img/slot-icon/GameArt.png">
        <img src="/frontend/winnerscn/img/slot-icon/Habanero.png">
        <img src="/frontend/winnerscn/img/slot-icon/Micro.png">
        <img src="/frontend/winnerscn/img/slot-icon/Platipus.png">
        <img src="/frontend/winnerscn/img/slot-icon/Playson.png">
        <img src="/frontend/winnerscn/img/slot-icon/Pragmatic.png">
        <img src="/frontend/winnerscn/img/slot-icon/RedRake.png">
        <img src="/frontend/winnerscn/img/slot-icon/Spinmatic.png">
        <img src="/frontend/winnerscn/img/slot-icon/Spinomenal.png">
        <img src="/frontend/winnerscn/img/slot-icon/Thunderkick.png">
      </div>
    </div>
    <div class="copyright-cont">
        <span>&copy; COPYRIGHT  Winner's Pro  {{ now()->year }} ALL RIGHTS RESERVED</span>
    </div>
	</div>


</div>

<div class="loginModal modal fade in" role="dialog" style="display: none; padding-right: 17px;">
    <div class="bs-modal modal-dialog">
        <div class="bsm-cont">
            <button class="mdl-close-btn" data-dismiss="modal">关闭</button>
            <div class="bsm-inner">
                <div class="mdl-head">
                    <div class="mdl-title">
                        <div class="active">
                            <p class="en">LOGIN</p>
                            <p class="kr">登录</p>
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
                                        <input type="text" placeholder="用户名" name="userid" data-parsley-required="required" data-parsley-required-message="必须输入" autocomplete="off" data-parsley-id="7331"><ul class="parsley-errors-list" id="parsley-id-7331"></ul>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="infos">
                                        <input type="password" placeholder="密码" name="password" data-parsley-required="required" data-parsley-required-message="必须输入" autocomplete="off" data-parsley-id="5272"><ul class="parsley-errors-list" id="parsley-id-5272"></ul>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-btn-foot">
                                <div class="btn-cont">
                                    <button type="submit" class="red over-style login-btn">
                                        <span class="os-cont"><i class="fa fa-lock-open"></i> 登录</span>
                                    </button>
                                    <button type="reset" class="join-btn over-style">
                                    <span class="os-cont">
                                        <i class="fa fa-file-signature"></i> 注册
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
			<button class="mdl-close-btn" data-dismiss="modal">关闭</button>
			<div class="bsm-inner">
				<div class="mdl-head">
					<div class="mdl-title">
						<div class="active">
							<p class="en">JOIN</p>
							<p class="kr">注册</p>
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
										<p>账号(2字以上中文、英文及数字可行)</p>
									</div>
									<div class="infos">
										<input class="form-control " id="membername" data-parsley-trigger="input change" data-parsley-pattern="/^[\u3000\u3400-\u4DBF\u4E00-\u9FFF|a-z|A-Z|0-9|\*]+$/" data-parsley-pattern-message="无效内容" data-parsley-required="true" data-parsley-required-message="必须输入" data-parsley-minlength="2" data-parsley-minlength-message="请输入2字以上" data-parsley-maxlength="12" data-parsley-maxlength-message="请输入12字以下" autocomplete="off" name="membername" type="text" value="" data-parsley-id="3942"><ul class="parsley-errors-list" id="parsley-id-3942"></ul>
									</div>
								</div>
								<div class="form-group">
									<div class="labels">
										<p>密码</p>
									</div>
									<div class="infos">
										<input class="form-control " id="memberpw" maxlength="12" data-parsley-trigger="change" data-parsley-required="true" data-parsley-required-message="必须输入" data-parsley-maxlength="12" data-parsley-maxlength-message="请输入12字以下" data-parsley-minlength="6" data-parsley-minlength-message="请输入6字以上" autocomplete="off" name="memberpw" type="password" value="" data-parsley-id="8027"><ul class="parsley-errors-list" id="parsley-id-8027"></ul>
									</div>
								</div>
								<div class="form-group">
									<div class="labels">
										<p>确认密码</p>
									</div>
									<div class="infos">
										<input class="form-control " data-parsley-equalto="#memberpw" data-parsley-equalto-message="密码不一致" maxlength="12" data-parsley-trigger="change" data-parsley-required="true" data-parsley-required-message="必须输入" data-parsley-minlength="6" data-parsley-minlength-message="请输入6字以上" autocomplete="off" name="confirmpw" type="password" value="" data-parsley-id="5099" id="confirmpw"><ul class="parsley-errors-list" id="parsley-id-5099"></ul>
									</div>
								</div>
								<div class="form-group">
									<div class="labels">
										<p>手机号</p>
									</div>
									<div class="infos">
										<input class="form-control" id="mobile" data-parsley-type="digits" data-parsley-type-message="请只输入数字" data-parsley-pattern="/^[0-9-]*$/" data-parsley-pattern-message="无效内容" maxlength="15" data-parsley-trigger="change input focusin focusout" data-parsley-required="true" data-parsley-required-message="必须输入" data-parsley-maxlength="15" data-parsley-maxlength-message="请输入15字以下" data-parsley-minlength="9" data-parsley-minlength-message="请输入9字以上" placeholder="无法用'-'，请只输入数字" autocomplete="off" name="mobile" type="text" value="" data-parsley-id="1202"><ul class="parsley-errors-list" id="parsley-id-1202"></ul>
									</div>
								</div>
								<div class="form-group">
									<div class="labels">
										<p>银行名</p>
									</div>
									<div class="infos">
										<label>
											<select id="bankname" class="form-control " data-parsley-required="true" data-parsley-required-message="必须输入" name="bankname" data-parsley-id="5166">
												<option value="">--选择银行--</option>
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
										<p>账户号</p>
									</div>
									<div class="infos">
										<input id="accountnumber" class="form-control " data-parsley-type="digits" data-parsley-type-message="请只输入数字" data-parsley-pattern="/^[0-9]*$/" data-parsley-pattern-message="无效内容" maxlength="30" data-parsley-trigger="change" data-parsley-maxlength="30" data-parsley-maxlength-message="请输入30字以下" data-parsley-required="true" data-parsley-required-message="必须输入" autocomplete="off" name="accountnumber" type="text" value="" data-parsley-id="1905"><ul class="parsley-errors-list" id="parsley-id-1905"></ul>
									</div>
								</div>
								<div class="form-group">
									<div class="labels">
										<p>存款人</p>
									</div>
									<div class="infos">
										<input id="mb_account_name" class="form-control" data-parsley-trigger="change" data-parsley-pattern="/^[\u3000\u3400-\u4DBF\u4E00-\u9FFF|a-z|A-Z|0-9]+$/" data-parsley-pattern-message="无效内容" maxlength="12" data-parsley-minlength="2" data-parsley-minlength-message="请输入3字以上" data-parsley-maxlength="12" data-parsley-maxlength-message="请输入12字以下" data-parsley-required="true" data-parsley-required-message="必须输入" autocomplete="off" name="a_name" type="text" value="" data-parsley-id="0047"><ul class="parsley-errors-list" id="parsley-id-0047"></ul>
									</div>
								</div>
								<div class="form-group">
									<div class="labels">
										<p>推荐人码</p>
									</div>
									<div class="infos">
										<input class="form-control " id="recomcode_id" data-parsley-trigger="change" maxlength="12" data-parsley-required="true" data-parsley-required-message="必须输入" autocomplete="off" name="recomcode_id" type="text" value="" data-parsley-id="9597"><ul class="parsley-errors-list" id="parsley-id-9597"></ul>
									</div>
								</div>
							</div>
							<div class="modal-btn-foot">
								<div class="btn-cont">
									<button type="submit" class="over-style">
										<span class="os-cont">
                    注册
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
			<button class="mdl-close-btn" data-dismiss="modal">关闭</button>
			<div class="bsm-inner">
				<div class="mdl-head">
					<div class="mdl-title">
						<div class="active">
							<p class="en">MY PAGE</p>
							<p class="kr">我的页面</p>
						</div>
					</div>
				</div>
				<div class="nav-mdl three-mob">
					<div class="nav-cont">
						<div class="nav-btn active five">
							<button>
								<i class="icon icon-Rolodex"></i>
								<p class="text">我的信息</p>
							</button>
						</div>
						<div class="nav-btn">
							<button class="mp">
								<i class="icon icon-Message"></i>
								<p class="text">纸条</p>
							</button>
						</div>
						<div class="nav-btn">
							<button class="mp">
								<i class="icon icon-Medal"></i>
								<p class="text">转换红包</p>
							</button>
						</div>
						<div class="nav-btn">
							<button>
								<i class="icon icon-ClosedLock"></i>
								<p class="text">修改密码</p>
							</button>
						</div>
					</div>
				</div>
				<div class="modal-body">
					<div class="tab-mdl active">
						<div class="mypage-nav">
							<button class="account-link active"><i class="icon icon-User"></i> 我的信息</button>
							<button class="history-link"><i class="icon icon-List"></i> 存取款详情</button>
						</div>
						<div class="mypage-tabs">
							<div class="mp-tab active">
								<div class="form-container">
									<div class="form-group">
										<div class="labels">
											<p>账号 :</p>
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
											<p>持有金额 :</p>
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
											<p>注册账号信息 :</p>
										</div>
										<div class="infos">
											<p>:****:***</p>
										</div>
									</div>
									<div class="form-group">
										<div class="labels">
											<p>联系地址 :</p>
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
											<p>注册日子 :</p>
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
											<th>标题</th>
                      <th>创造日子</th>
                      <th>创造者</th>
                      <th></th>
										</tr>
									</thead>
									<tbody class="message-list">
                    @if ($msgs && count($msgs) > 0)
                    @foreach ($msgs as $msg)
                      <tr  class="depth-click" onclick="readMessage('{{$msg->id}}')">
                        <td>{{$msg->title}}</td>
                        <td>{{$msg->created_at}}</td>
                        <td>{{$msg->writer->hasRole('admin')?'管理者':'총본사'}}</td>
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
                      <td class="text-center" colspan="3">无纸条</td>
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
											<p>携带红包</p>
										</div>
										<div class="infos">
											<p class="info player-point">{{Auth::check()?number_format(auth()->user()->deal_balance,0) : 0}}WON</p>
										</div>
									</div>
									<div class="form-group">
										<div class="labels">
											<p>转换金额</p>
										</div>
										<div class="infos">
                      <input type="hidden" value="<?= csrf_token() ?>" name="_token" id="_token">
											<input class="form-control w400" id="pointAmount" data-parsley-type="digits" data-parsley-type-message="无效内容" data-parsley-pattern="/^[0-9]*$/" data-parsley-pattern-message="无效内容" data-parsley-trigger="focusin change" data-parsley-required="true" data-parsley-required-message="必须输入" data-parsley-min="300" data-parsley-min-message="最小存款 30,000WON以上" placeholder="最小存款 30,000WON以上可能（0,000WON单位）" name="point_money" type="text" value="" data-parsley-id="9147"><ul class="parsley-errors-list" id="parsley-id-9147"></ul>
											<div class="btn-grp" style="margin-top: 25px;">
												<button type="button" onclick="addAmount(4,1)">10,000</button>
												<button type="button" onclick="addAmount(4,3)">30,000</button>
												<button type="button" onclick="addAmount(4,5)">50,000</button>
												<button type="button" onclick="addAmount(4,10)">100,000</button>
												<button type="button" onclick="addAmount(4,50)">500,000</button>
												<button type="button" onclick="resetAmount(4)">复位</button>
											</div>
										</div>
									</div>

									<div class="modal-btn-foot">
										<div class="btn-cont">
											<button type="submit" class="green">转换</button>
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
											alert_error('请输入存款金额（0,000WON单位）');
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
												<p>原密码</p>
											</div>
											<div class="infos">
												<input type="password" name="cur_pwd" data-parsley-trigger="focusout" data-parsley-remote-message="密码不一致" data-parsley-remote="/player/current/password" data-parsley-required="true" data-parsley-remote-validator="reverse" data-parsley-required-message="必须输入" data-parsley-id="7778"><ul class="parsley-errors-list" id="parsley-id-7778"></ul>
											</div>
										</div>
										<div class="form-group">
											<div class="labels">
												<p>修改密码</p>
											</div>
											<div class="infos">
												<input type="password" id="new_password" name="new_pwd" data-parsley-trigger="focusout" data-parsley-minlength="4" data-parsley-minlength-message="请输入4字以上" data-parsley-maxlength="12" data-parsley-maxlength-message="请输入12字以下" data-parsley-required="true" data-parsley-required-message="必须输入" data-parsley-id="2499"><ul class="parsley-errors-list" id="parsley-id-2499"></ul>
											</div>
										</div>
										<div class="form-group">
											<div class="labels">
												<p>确认密码</p>
											</div>
											<div class="infos">
												<input type="password" name="new_pwd_confirm" data-parsley-trigger="focusout" data-parsley-equalto="#new_password" data-parsley-equalto-message="密码不一致" data-parsley-required="true" data-parsley-required-message="必须输入" data-parsley-id="6448"><ul class="parsley-errors-list" id="parsley-id-6448"></ul>
											</div>
										</div>
										<div class="modal-btn-foot">
											<div class="btn-cont">
												<button type="submit" class="over-style red">
													<span class="os-cont"><i class="fa fa-check-square"></i> 修改</span>
												</button>
												<button type="reset" class="over-style" data-dismiss="modal">
													<span class="os-cont"><i class="fa fa-window-close"></i> 取消</span>
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
			<button class="mdl-close-btn" data-dismiss="modal">关闭</button>
			<div class="bsm-inner">
				<div class="mdl-head">
					<div class="mdl-title">
						<div class="deposit">
							<p class="en">DEPOSIT</p>
							<p class="kr">存款申请</p>
						</div>
						<div class="withdraw">
							<p class="en">WITHDRAW</p>
							<p class="kr">取款申请</p>
						</div>
						<div class="event active">
							<p class="en">EVENT</p>
							<p class="kr">活动</p>
						</div>
						<div class="notice">
							<p class="en">NOTICE</p>
							<p class="kr">公告</p>
						</div>
					</div>
				</div>

				<div class="nav-mdl">
					<div class="nav-cont">
						<div class="nav-btn">
							<button class="deposit-link active">
								<i class="icon icon-Bag"></i>
								<p class="text">存款申请</p>
							</button>
						</div>
						<div class="nav-btn">
							<button class="withdraw-link">
								<i class="icon icon-Dollar"></i>
								<p class="text">取款申请</p>
							</button>
						</div>
						<div class="nav-btn active">
							<button class="event-link">
								<i class="icon icon-Agenda"></i>
								<p class="text">活动</p>
							</button>
						</div>
						<div class="nav-btn">
							<button class="notice-link">
								<i class="icon icon-Megaphone"></i>
								<p class="text">公告</p>
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
                  <p>会员信息</p>
                </div>
                <div class="infos">
                  <p>@if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
                        {{ Auth::user()->username }}
                        @endif</p>
                </div>
              </div>
              <div class="form-group">
									<div class="labels">
										<p>银行名</p>
									</div>
									<div class="infos">
										<label>
											<select id="bankname" class="form-control " data-parsley-required="true" data-parsley-required-message="必须输入" name="bankname" data-parsley-id="5166" value="">
												<option value="">--选择银行--</option>
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
										<p>账户号</p>
									</div>
									<div class="infos">
										<input id="accountnumber" class="form-control " data-parsley-type="digits" data-parsley-type-message="请只输入数字" data-parsley-pattern="/^[0-9]*$/" data-parsley-pattern-message="无效内容" maxlength="30" data-parsley-trigger="change" data-parsley-maxlength="30" data-parsley-maxlength-message="请输入30字以下" data-parsley-required="true" data-parsley-required-message="必须输入" autocomplete="off" name="accountnumber" type="text" value="{{Auth::check()?auth()->user()->account_no:''}}" data-parsley-id="1905"><ul class="parsley-errors-list" id="parsley-id-1905"></ul>
									</div>
								</div>
              <div class="form-group">
                <div class="labels">
                  <p>存款名字</p>
                </div>
                <div class="infos">
                  <input id="recommender" class="form-control "  data-parsley-trigger="change" data-parsley-required="true" data-parsley-required-message="必须输入" name="recommender" type="text" value="{{Auth::check()?auth()->user()->recommender:''}}" data-parsley-id="0017"><ul class="parsley-errors-list" id="parsley-id-0017"></ul>
                </div>
              </div>
              <div class="form-group">
                <div class="labels">
                  <p>存款金币</p>
                </div>
                <div class="infos">
                  <input class="form-control w400" id="depositAmount" data-parsley-type="digits" data-parsley-type-message="无效内容" data-parsley-pattern="/^[0-9]*$/" data-parsley-pattern-message="无效内容" data-parsley-trigger="focusin change" data-parsley-required="true" data-parsley-required-message="必须输入" data-parsley-min="300" data-parsley-min-message="最小存款 30,000WON以上" placeholder="最小存款 30,000WON以上可能（0,000WON单位）" name="money" type="text" value="" data-parsley-id="0361"><ul class="parsley-errors-list" id="parsley-id-0361"></ul>
                  <div class="btn-grp" style="margin-top: 25px;">
                    <button type="button" onclick="addAmount(1,1)">10,000</button>
                    <button type="button" onclick="addAmount(1,3)">30,000</button>
                    <button type="button" onclick="addAmount(1,5)">50,000</button>
                    <button type="button" onclick="addAmount(1,10)">100,000</button>
                    <button type="button" onclick="addAmount(1,50)">500,000</button>
                    <button type="button" onclick="resetAmount(1)">复位</button>
                  </div>
                </div>
              </div>
              <div class="deposit-ask">
                <button type="button" onclick="askAccount();">
                  <i class="icon icon-Info"></i>
                  <span>存款账户询问</span>
                </button>
                <p id="bankinfo">* 存款前必须询问账户</p>
              </div>
            </div>
            <div class="modal-btn-foot">
              <div class="btn-cont">
                <button type="submit" class="green">存款</button>
                <button type="reset" class="blue" data-dismiss="modal">取消</button>
              </div>
            </div>
          </form>

          
        </div>

    <script>
      var df = $("#depoFrm");
      df.submit(function (e) {
        var amount = parseInt($("[name=money]").val());
        if (amount % 10000 > 0) {
          alert_error('请输入存款金额（0,000WON单位）');
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
              <p>持有金额</p>
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
              <p>银行名</p>
            </div>
            <div class="infos">
              <label>
                <select id="bankname" class="form-control " data-parsley-required="true" data-parsley-required-message="必须输入" name="bankname" data-parsley-id="7298">
                  <option value="">--选择银行--</option>
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
              <p>账户号</p>
            </div>
            <div class="infos">
              <input id="accountnumber" class="form-control " data-parsley-type="digits" data-parsley-type-message="请只输入数字" data-parsley-pattern="/^[0-9]*$/" data-parsley-pattern-message="无效内容" maxlength="30" data-parsley-trigger="change" data-parsley-maxlength="30" data-parsley-maxlength-message="请输入30字以下" data-parsley-required="true" data-parsley-required-message="必须输入" autocomplete="off" name="accountnumber" type="text" value="{{Auth::check()? auth()->user()->account_no : ''}}" data-parsley-id="1905"><ul class="parsley-errors-list" id="parsley-id-1905"></ul>
            </div>
          </div>
          <div class="form-group">
            <div class="labels">
              <p>支款者姓名</p>
            </div>
            <div class="infos">
              <input id="recommender" class="form-control "  data-parsley-trigger="change" data-parsley-required="true" data-parsley-required-message="必须输入" name="recommender" type="text" value="{{Auth::check()? auth()->user()->recommender:''}}" data-parsley-id="0017"><ul class="parsley-errors-list" id="parsley-id-0017"></ul>
            </div>
          </div>

          <div class="form-group">
            <div class="labels">
              <p>之款金额</p>
            </div>
            <div class="infos">
              <input class="form-control w400 withdrawal_amount" id="withdrawalAmount" data-parsley-type="digits" data-parsley-type-message="无效内容" data-parsley-pattern="/^[0-9]*$/" data-parsley-pattern-message="无效内容" data-parsley-trigger="focus change" data-parsley-required="true" data-parsley-required-message="必须输入" data-parsley-min="10000" data-parsley-min-message="最小取款 100WON以上" placeholder="最小取款 100WON以上可能 （0,000WON单位）" name="money" type="text" value="" data-parsley-id="5344"><ul class="parsley-errors-list" id="parsley-id-5344"></ul>
              <div class="btn-grp" style="margin-top: 25px;">
                <button type="button" onclick="addAmount(2,1)">10,000</button>
                <button type="button" onclick="addAmount(2,3)">30,000</button>
                <button type="button" onclick="addAmount(2,5)">50,000</button>
                <button type="button" onclick="addAmount(2,10)">100,000</button>
                <button type="button" onclick="addAmount(2,50)">500,000</button>
                <button type="button" onclick="resetAmount(2)">复位</button>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-btn-foot">
          <div class="btn-cont">
            <button type="submit" class="over-style red">
              <span class="os-cont">支款</span>
            </button>
            <button type="reset" class="over-style" data-dismiss="modal">
              <span class="os-cont">取消</span>
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
          alert_error('请输入支款金额（0,000WON单位）');
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
              <th>序号</th>
              <th>节目</th>
              <th>创造日子</th>
            </tr>
          </thead>
          <tbody>


          <tr>
            <td class="text-center" colspan="3">无内容</td>
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
              <th>序号</th>
              <th>节目</th>
              <th>创造日子</th>
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
            <button type="button" class="mdl-close-btn" data-dismiss="modal">关闭</button>
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
                                <p class="text">全部</p>
                            </button>
                        </div>
                        <div class="nav-btn">
                            <button type="button" onclick="gameDivision($(this))" data-target=".p-tab">
                                <i class="icon icon-Controller"></i>
                                <p class="text">名气</p>
                            </button>
                        </div>
                        <div class="nav-btn">
                            <button type="button" onclick="gameDivision($(this))" data-target=".s-tab">
                                <i class="icon icon-Controller"></i>
                                <p class="text">老虎机</p>
                            </button>
                        </div>
                        <div class="nav-btn">
                            <button type="button" onclick="gameDivision($(this))" data-target=".t-tab">
                                <i class="icon icon-Controller"></i>
                                <p class="text">桌子</p>
                            </button>
                        </div>
                        <div class="nav-btn">
                            <button type="button" onclick="gameDivision($(this))" data-target=".e-tab">
                                <i class="icon icon-Controller"></i>
                                <p class="text">其他</p>
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
			<button class="mdl-close-btn" data-dismiss="modal">关闭</button>
			<div class="bsm-inner">
				<div class="mdl-head">
					<div class="mdl-title">
						<div class="active">
							<p class="en">MY PAGE</p>
							<p class="kr">我的页面</p>
						</div>
					</div>
				</div>
				<div class="nav-mdl three-mob three">
					<div class="nav-cont">
						<div class="nav-btn active" data-id="DP" data-target=".deposit-list">
							<button>
								<i class="icon icon-Bag"></i>
								<p class="text">存款</p>
							</button>
						</div>
						<div class="nav-btn" data-id="WT" data-target=".withdraw-list">
							<button>
								<i class="icon icon-Dollar"></i>
								<p class="text">支款</p>
							</button>
						</div>
						<div class="nav-btn" data-id="BH" data-target=".bonuses-list">
							<button>
								<i class="icon icon-DiamondRing"></i>
								<p class="text">红包详情</p>
							</button>
						</div>
					</div>
				</div>
				<div class="modal-body">
					<div class="tab-mdl active">
						<div class="mypage-nav">
							<button class="account-link"><i class="icon icon-User"></i> 我的信息</button>
							<button class="history-link active"><i class="icon icon-List"></i> 存取款详情</button>
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
										<!--<th>구분</th>-->
										<th>分类</th>
										<th>金额</th>
										<th>状态</th>
										<th>日子</th>
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
										<!--<th>구분</th>-->
										<th>分类</th>
										<th>金额</th>
										<th>状态</th>
										<th>日子</th>
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
										<th>红包详情</th>
										<th>类型</th>
										<th>金额</th>
										<th>滚动金额</th>
										<th>状态</th>
										<th>日子</th>
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
      <label for="notice{{$notice->id}}">今天不显示 <span style="cursor:pointer;" onclick="closeWin(document.getElementById('pop{{$notice->id}}'), {{$notice->id}}, document.getElementById('notice{{$notice->id}}').checked);">[关闭]</span></label>
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
      
      if ($("input[name='userid']").val() == "账号" || $("input[name='userid']").val() == "") {
          alert("请输入账号");
          frm.userid.focus();
          return;
      }
      if ($("input[name='password']").val() == "******" || $("input[name='password']").val() == "") {
          alert("请输入密码");
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
      alert_error('请先输入存取款账号');
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

            alert_ok("存款申请完毕");
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

            alert_ok("取款申请完毕");
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
            alert_ok_reload('收益转换为携带金币', '/');
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