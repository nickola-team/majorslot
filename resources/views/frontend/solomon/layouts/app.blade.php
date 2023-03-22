
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="icon" href="/frontend/winners/img/favicon.ico" >
    <meta
      name="viewport"
      content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=0"
    />
    <meta name="theme-color" content="#000000" />
      <script type="text/javascript" src="/frontend/winners/js/app.js"></script>
    <script type="text/javascript" src="/frontend/winners/js/jquery.number.min.js"></script>
    <script type="text/javascript" src="/frontend/winners/js/parsley.min.js"></script>
    <script type="text/javascript" src="/frontend/winners/js/parsley.remote.min.js"></script>
    <script type="text/javascript" src="/frontend/winners/js/lazyload.min.js"></script>
    <script type="text/javascript" src="/frontend/winners/js/action.js"></script>

    <script src="/frontend/winners/js/TweenMax.min.js"></script>
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
            "크롬에 최적화되어습니다. 크롬으브라우저를  이용해주시길 바람니다."
          ),
          (window.location.href = "https://www.google.com/intl/ko/chrome/"));
      }
    </script>

    <link rel="stylesheet" href="/frontend/winners/css/font-icon.css">
    <link rel="stylesheet" href="/frontend/winners/css/app.css">
    <link rel="stylesheet" href="/frontend/winners/css/style7d93.css?v=2368">
    <link rel="stylesheet" href="/frontend/winners/css/galaxy5358.css?v=1540">
    <link rel="stylesheet" href="/frontend/winners/css/themes/default.min.css">


    <!-- Sweet-Alert  -->
    <script type="text/javascript" src="/frontend/winners/js/sweetalert/sweetalert.min.js"></script>
    <script type="text/javascript" src="/frontend/winners/js/sweetalert/jquery.sweet-alert.custom.js"></script>
    <link rel="stylesheet" href="/frontend/winners/js/sweetalert/sweetalert.css" type="text/css">

    <script type="text/javascript" src="/frontend/winners/js/menuafee.js?v=9337"></script>
	<script type="text/javascript" src="/frontend/winners/js/maina5e0.js?v=3289"></script>
	<script type="text/javascript" src="/frontend/winners/js/script838b.js?v=1433"></script>
   
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
					<img src="/frontend/solomon/img/logo.png">
				</div>
			</a>

      <div class="nav-cont">
        <div class="link-main">
          <ul class="bs-ul">
            @if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
            @if ($unreadmsg>0)
            <li class="deposit-link ">
               <a href="javascript:void(0);"  onclick="alert_error('쪽지를 확인하세요.');">
            @else
            <li class="deposit-link subpg-link">
              <a href="javascript:void(0);">
            @endif
            @else
            <li class="deposit-link ">
               <a href="javascript:void(0);"  onclick="alert_error('로그인이 필요합니다.');">
            @endif
                <div class="icon-cont">
                  <img src="/frontend/winners/img/icon/deposit.png">
                </div>
                <span>입금신청</span>
              </a>
            </li>
           
            @if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
            @if ($unreadmsg>0)
            <li class="deposit-link ">
               <a href="javascript:void(0);"  onclick="alert_error('쪽지를 확인하세요.');">
            @else
            <li class="withdraw-link subpg-link">
              <a href="javascript:void(0);" >
            @endif
            @else
            <li class="withdraw-link ">
               <a href="javascript:void(0);"  onclick="alert_error('로그인이 필요합니다.');">
            @endif
                <div class="icon-cont">
                  <img src="/frontend/winners/img/icon/withdraw.png">
                </div>
                <span>출금신청</span>
              </a>
            </li>
           
              @if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
              <li class="event-link subpg-link">
                <a href="javascript:void(0);">
              @else
              <li class="event-link">
                <a href="javascript:void(0);"  onclick="alert_error('로그인이 필요합니다.');">
              @endif
                <div class="icon-cont">
                  <img src="/frontend/winners/img/icon/event.png">
                </div>
                <span>이벤트</span>
              </a>
            </li>
           
              @if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
              <li class="notice-link subpg-link">
                <a href="javascript:void(0);">
              @else
              <li class="notice-link">
                <a href="javascript:void(0);"  onclick="alert_error('로그인이 필요합니다.');">
              @endif
                <div class="icon-cont">
                  <img src="/frontend/winners/img/icon/notice.png">
                </div>
                <span>공지사항</span>
              </a>
            </li>
            
              @if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
              <li class="mypage-link">
                <a href="javascript:void(0);">
              @else
              <li>
                <a href="javascript:void(0);"  onclick="alert_error('로그인이 필요합니다.');">
              @endif
                <div class="icon-cont">
                  <img src="/frontend/winners/img/icon/mypage.png">
                </div>
                <span>마이페이지</span>
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
                        보유금 :
                      </span>
                    </div>
                    <div class="info">
                      <p class="player-balance" style="margin:0"> {{ number_format(Auth::user()->balance,0) }}원</p>
                    </div>
                  </div>
                  <div class="al-cont point-info-btn" data-toggle="modal" data-target=".mypageModal">
                    <div class="labels">
                      <span class="inner">
                        보너스 :
                      </span>
                    </div>
                    <div class="info">
                      <p class="player-balance" style="margin:0"> {{ number_format(Auth::user()->deal_balance,0) }}원</p>
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
                      <i class="fa fa-envelope"></i> 쪽지
                      <span class="mess-count" style="animation: letter_anim 0s linear infinite;">{{$unreadmsg}}</span>
                    </button>
                    <button class="logout-btn red" onclick="goLogout();"><i class="fa fa-sign-out-alt"></i> 로그아웃</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @else
          <div class="before-login active">
            <button class="login" data-toggle="modal" data-target=".loginModal"><i class="icon icon-Starship"></i> 로그인</button>
            <button class="join" data-toggle="modal" data-target=".joinModal"><i class="icon icon-Planet"></i> 회원가입</button>
          </div>
          @endif
        </div>
      </div>
    </div>
	</div>
  <div class="banner-main" style="background-image: url(/frontend/solomon/img/banner/banner-bg.jpg) !important;">
    <div class="banner-deco">
      <div class="sky-container left">
        <div class="none"></div>
        <div class="none"></div>
        <div class="none"></div>
        <div class="star"></div>
        <div class="none"></div>
      </div>
      <img class="fireball-orange" src="/frontend/solomon/img/banner/fireball-orange.png">
      <!-- <img class="fireball-yellow" src="img/banner/fireball-yellow.png"> -->
      <!-- <img class="wolf" src="img/banner/wolf.png"> -->
      <!-- <img class="glow" src="img/banner/glow.png"> -->
      <img class="king" src="/frontend/solomon/img/banner/char-1eccb.png?3">
      <!-- <img class="robot" src="img/banner/robot.png"> -->
      <!-- <img class="diamond" src="img/banner/diamond.png"> -->
      <div class="sky-container">
        <div class="none"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
      </div>
      <img class="coins" src="/frontend/solomon/img/banner/coins.png">
      <!-- <img class="girl" src="img/banner/girl.png"> -->
      <img class="rich-man" src="/frontend/solomon/img/banner/char-2c81e.png?2">
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
              <p class="title">솔로몬에 </p>
              <p class="sub">오신것을 환영합니다.</p>
            </div>
          </div>
        </div>
        <div class="item active">
          <div class="text-cont">
            <div class="inner">
              <p class="title">아시아 최고의 환수율 </p>
              <p class="sub">업계 최고의 보너스 !!</p>
            </div>
          </div>
        </div>
        <div class="item">
          <div class="text-cont">
            <div class="inner">
              <p class="title">매일매일 터지는 잭팟 !</p>
              <p class="sub"> 솔로몬과 함께하세요 !</p>
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
      <span class="a_style">솔로몬 GAMES</span>
    </div>

    <div class="slot-container">

    @if ($categories && count($categories))
				@foreach($categories AS $index=>$category)
					@if($category->title != "Hot" && $category->title != "Card" && $category->title != "Bingo" && $category->title != "Roulette" 
					&& $category->title != "Novomatic" && $category->title != "Keno" && $category->title != "Vision" && $category->title != "Wazdan")

          @if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
            @if ($unreadmsg>0)
            <a href="javascript:void(0);" class="slot-btn"  onclick="alert_error('쪽지를 확인하세요.');">
            @elseif ($category->status == 0)
            <a href="javascript:void(0);" class="slot-btn"  onclick="alert_error('점검중입니다.');">
            @else
            <a href="javascript:void(0);" class="slot-btn" onclick=" getSlotGames('{{ $category->trans?$category->trans->trans_title:$category->title }}', '{{ $category->href }}', 0)">
            @endif
          @else
          <a href="javascript:void(0);" class="slot-btn"  onclick="alert_error('로그인이 필요합니다');">
          @endif
            <div class="sb-inner">
              <div class="main-cont">
                <div class="inner">
                  <img class="main-img" src="/frontend/winners/img/slot/{{ $category->title.'.jpg' }}">
                  <button class="start-text">게임시작</button>
                </div>
              </div>
              <button class="name-btn">
              @if ($category->trans)
                {{ $category->trans->trans_title }}
              @else
                {{ $category->title }}
              @endif
              </button>
              <img class="plate" src="/frontend/winners/img/bg/slot-plate.png">
            </div>
          </a>     

					@endif
				@endforeach
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
        <img src="/frontend/winners/img/slot-icon/Blueprint.png">
        <img src="/frontend/winners/img/slot-icon/Booongo.png">
        <img src="/frontend/winners/img/slot-icon/Endorphina.png">
        <img src="/frontend/winners/img/slot-icon/GameArt.png">
        <img src="/frontend/winners/img/slot-icon/Habanero.png">
        <img src="/frontend/winners/img/slot-icon/Micro.png">
        <img src="/frontend/winners/img/slot-icon/Platipus.png">
        <img src="/frontend/winners/img/slot-icon/Playson.png">
        <img src="/frontend/winners/img/slot-icon/Pragmatic.png">
        <img src="/frontend/winners/img/slot-icon/RedRake.png">
        <img src="/frontend/winners/img/slot-icon/Spinmatic.png">
        <img src="/frontend/winners/img/slot-icon/Spinomenal.png">
        <img src="/frontend/winners/img/slot-icon/Thunderkick.png">
      </div>
    </div>
    <div class="copyright-cont">
        <span>ⓒ COPYRIGHT  솔로몬  2022~2023 ALL RIGHTS RESERVED</span>
    </div>
	</div>


</div>

<div class="loginModal modal fade in" role="dialog" style="display: none; padding-right: 17px;">
    <div class="bs-modal modal-dialog">
        <div class="bsm-cont">
            <button class="mdl-close-btn" data-dismiss="modal">닫기</button>
            <div class="bsm-inner">
                <div class="mdl-head">
                    <div class="mdl-title">
                        <div class="active">
                            <p class="en">LOGIN</p>
                            <p class="kr">로그인</p>
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
                                        <input type="text" placeholder="아이디" name="userid" data-parsley-required="required" data-parsley-required-message="필수입력 항목입니다." autocomplete="off" data-parsley-id="7331"><ul class="parsley-errors-list" id="parsley-id-7331"></ul>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="infos">
                                        <input type="password" placeholder="비밀번호" name="password" data-parsley-required="required" data-parsley-required-message="필수입력 항목입니다." autocomplete="off" data-parsley-id="5272"><ul class="parsley-errors-list" id="parsley-id-5272"></ul>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-btn-foot">
                                <div class="btn-cont">
                                    <button type="submit" class="red over-style login-btn">
                                        <span class="os-cont"><i class="fa fa-lock-open"></i> 로그인</span>
                                    </button>
                                    <button type="reset" class="join-btn over-style">
                                    <span class="os-cont">
                                        <i class="fa fa-file-signature"></i> 회원가입
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
			<button class="mdl-close-btn" data-dismiss="modal">닫기</button>
			<div class="bsm-inner">
				<div class="mdl-head">
					<div class="mdl-title">
						<div class="active">
							<p class="en">JOIN</p>
							<p class="kr">회원가입</p>
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
										<p>닉네임(2자이상 한글,영문 및 숫자 가능)</p>
									</div>
									<div class="infos">
										<input class="form-control " id="membername" data-parsley-trigger="input change" data-parsley-pattern="/^[ㄱ-ㅎ|가-힣|a-z|A-Z|0-9|\*]+$/" data-parsley-pattern-message="유효하지 않은 값입니다." data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." data-parsley-minlength="2" data-parsley-minlength-message="2 자 이상 입력하세요." data-parsley-maxlength="12" data-parsley-maxlength-message="12 자 이하로 입력하세요." autocomplete="off" name="membername" type="text" value="" data-parsley-id="3942"><ul class="parsley-errors-list" id="parsley-id-3942"></ul>
									</div>
								</div>
								<div class="form-group">
									<div class="labels">
										<p>비밀번호</p>
									</div>
									<div class="infos">
										<input class="form-control " id="memberpw" maxlength="12" data-parsley-trigger="change" data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." data-parsley-maxlength="12" data-parsley-maxlength-message="12 자 이하로 입력하세요." data-parsley-minlength="6" data-parsley-minlength-message="6 자 이상 입력하세요." autocomplete="off" name="memberpw" type="password" value="" data-parsley-id="8027"><ul class="parsley-errors-list" id="parsley-id-8027"></ul>
									</div>
								</div>
								<div class="form-group">
									<div class="labels">
										<p>비밀번호확인</p>
									</div>
									<div class="infos">
										<input class="form-control " data-parsley-equalto="#memberpw" data-parsley-equalto-message="비밀번호가 일치하지 않습니다." maxlength="12" data-parsley-trigger="change" data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." data-parsley-minlength="6" data-parsley-minlength-message="6 자 이상 입력하세요." autocomplete="off" name="confirmpw" type="password" value="" data-parsley-id="5099" id="confirmpw"><ul class="parsley-errors-list" id="parsley-id-5099"></ul>
									</div>
								</div>
								<div class="form-group">
									<div class="labels">
										<p>전화번호</p>
									</div>
									<div class="infos">
										<input class="form-control" id="mobile" data-parsley-type="digits" data-parsley-type-message="이 값은 숫자만 입력 가능합니다." data-parsley-pattern="/^[0-9-]*$/" data-parsley-pattern-message="유효하지 않은 값입니다." maxlength="15" data-parsley-trigger="change input focusin focusout" data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." data-parsley-maxlength="15" data-parsley-maxlength-message="15 자 이하로 입력하세요." data-parsley-minlength="9" data-parsley-minlength-message="9 자 이상 입력하세요." placeholder="'-'없이 숫자 만 입력" autocomplete="off" name="mobile" type="text" value="" data-parsley-id="1202"><ul class="parsley-errors-list" id="parsley-id-1202"></ul>
									</div>
								</div>
								<div class="form-group">
									<div class="labels">
										<p>추천인코드(부여받으신 코드 입력)</p>
									</div>
									<div class="infos">
										<input class="form-control " id="recomcode_id" data-parsley-trigger="change" maxlength="12" data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." autocomplete="off" name="recomcode_id" type="text" value="" data-parsley-id="9597"><ul class="parsley-errors-list" id="parsley-id-9597"></ul>
									</div>
								</div>
							</div>
							<div class="modal-btn-foot">
								<div class="btn-cont">
									<button type="submit" class="over-style">
										<span class="os-cont">
											회원가입
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
			<button class="mdl-close-btn" data-dismiss="modal">닫기</button>
			<div class="bsm-inner">
				<div class="mdl-head">
					<div class="mdl-title">
						<div class="active">
							<p class="en">MY PAGE</p>
							<p class="kr">마이페이지</p>
						</div>
					</div>
				</div>
				<div class="nav-mdl three-mob">
					<div class="nav-cont">
						<div class="nav-btn active five">
							<button>
								<i class="icon icon-Rolodex"></i>
								<p class="text">나의 정보</p>
							</button>
						</div>
						<div class="nav-btn">
							<button class="mp">
								<i class="icon icon-Message"></i>
								<p class="text">쪽지</p>
							</button>
						</div>
						<div class="nav-btn">
							<button class="mp">
								<i class="icon icon-Medal"></i>
								<p class="text">보너스전환</p>
							</button>
						</div>
						<div class="nav-btn">
							<button>
								<i class="icon icon-ClosedLock"></i>
								<p class="text">비밀번호변경</p>
							</button>
						</div>
					</div>
				</div>
				<div class="modal-body">
					<div class="tab-mdl active">
						<div class="mypage-tabs">
							<div class="mp-tab active">
								<div class="form-container">
									<div class="form-group">
										<div class="labels">
											<p>아이디(닉네임) :</p>
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
											<p>보유금액 :</p>
										</div>
										<div class="infos">
											<p class="player-balance">
                      @if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
                      {{ number_format(Auth::user()->balance,0)}} 원
                      @endif</p>
										</div>
									</div>
									<div class="form-group">
										<div class="labels">
											<p>등록계좌정보 :</p>
										</div>
										<div class="infos">
											<p>{{Auth::check()?Auth::user()->bankInfo(true):''}}</p>
										</div>
									</div>
									<div class="form-group">
										<div class="labels">
											<p>연락처 :</p>
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
											<p>가입일시 :</p>
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
											<th>제목</th>
                      <th>작성일</th>
                      <th>작성자</th>
                      <th></th>
										</tr>
									</thead>
									<tbody class="message-list">
                    @if ($msgs && count($msgs) > 0)
                    @foreach ($msgs as $msg)
                      <tr  class="depth-click" onclick="readMessage('{{$msg->id}}')">
                        <td>{{$msg->title}}</td>
                        <td>{{$msg->created_at}}</td>
                        <td>{{$msg->writer->hasRole('admin')?'어드민':'총본사'}}</td>
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
                      <td class="text-center" colspan="3">쪽지가 없습니다.</td>
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
											<p>보유보너스</p>
										</div>
										<div class="infos">
											<p class="info player-point">{{Auth::check()?number_format(auth()->user()->deal_balance,0) : 0}}원</p>
										</div>
									</div>
									<div class="form-group">
										<div class="labels">
											<p>전환금액</p>
										</div>
										<div class="infos">
                      <input type="hidden" value="<?= csrf_token() ?>" name="_token" id="_token">
											<input class="form-control w400" id="pointAmount" data-parsley-type="digits" data-parsley-type-message="유효하지 않은 값입니다." data-parsley-pattern="/^[0-9]*$/" data-parsley-pattern-message="유효하지 않은 값입니다." data-parsley-trigger="focusin change" data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." data-parsley-min="0" data-parsley-min-message="" placeholder="" name="point_money" type="text" value="" data-parsley-id="9147"><ul class="parsley-errors-list" id="parsley-id-9147"></ul>
											<div class="btn-grp" style="margin-top: 25px;">
												<button type="button" onclick="addAmount(4,1)">1만</button>
												<button type="button" onclick="addAmount(4,3)">3만</button>
												<button type="button" onclick="addAmount(4,5)">5만</button>
												<button type="button" onclick="addAmount(4,10)">10만</button>
                        <button type="button" onclick="addAmount(4,{{Auth::check()?(intval(auth()->user()->deal_balance)/10000) : 0}})">전액</button>
												<button type="button" onclick="resetAmount(4)">정정하기</button>
											</div>
										</div>
									</div>

									<div class="modal-btn-foot">
										<div class="btn-cont">
											<button type="submit" class="green">전환하기</button>
											<button type="button" style="display: none;"></button>
										</div>
									</div>
								</div>
								</form>

								<script>
									var df = $("#pointFrm");
									df.submit(function (e) {
										var amount = parseInt($("[name=point_money]").val());
                    $(this).parsley().validate();
                    if ($(this).parsley().isValid()) {
                      $('.wrapper_loading').removeClass('hidden');
                    }
									});
								</script>
							</div>


							<div class="mp-tab">
								<form id="passwordChangeForm" action="javascript:goMypage();" method="POST" data-parsley-validate="" novalidate="">
									<div class="form-container">
										<div class="form-group">
											<div class="labels">
												<p>기존 비밀번호</p>
											</div>
											<div class="infos">
												<input type="password" name="cur_pwd" data-parsley-trigger="focusout" data-parsley-remote-message="비밀번호가 일치하지 않습니다." data-parsley-remote="/player/current/password" data-parsley-required="true" data-parsley-remote-validator="reverse" data-parsley-required-message="필수입력 항목입니다." data-parsley-id="7778"><ul class="parsley-errors-list" id="parsley-id-7778"></ul>
											</div>
										</div>
										<div class="form-group">
											<div class="labels">
												<p>변경할 비밀번호</p>
											</div>
											<div class="infos">
												<input type="password" id="new_password" name="new_pwd" data-parsley-trigger="focusout" data-parsley-minlength="4" data-parsley-minlength-message="최소 4자 이상" data-parsley-maxlength="12" data-parsley-maxlength-message="최대 12자 이하" data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." data-parsley-id="2499"><ul class="parsley-errors-list" id="parsley-id-2499"></ul>
											</div>
										</div>
										<div class="form-group">
											<div class="labels">
												<p>비밀번호 확인</p>
											</div>
											<div class="infos">
												<input type="password" name="new_pwd_confirm" data-parsley-trigger="focusout" data-parsley-equalto="#new_password" data-parsley-equalto-message="비밀번호가 일치하지 않습니다." data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." data-parsley-id="6448"><ul class="parsley-errors-list" id="parsley-id-6448"></ul>
											</div>
										</div>
										<div class="modal-btn-foot">
											<div class="btn-cont">
												<button type="submit" class="over-style red">
													<span class="os-cont"><i class="fa fa-check-square"></i> 변경하기</span>
												</button>
												<button type="reset" class="over-style" data-dismiss="modal">
													<span class="os-cont"><i class="fa fa-window-close"></i> 취소하기</span>
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
			<button class="mdl-close-btn" data-dismiss="modal">닫기</button>
			<div class="bsm-inner">
				<div class="mdl-head">
					<div class="mdl-title">
						<div class="deposit">
							<p class="en">DEPOSIT</p>
							<p class="kr">입금신청</p>
						</div>
						<div class="withdraw">
							<p class="en">WITHDRAW</p>
							<p class="kr">출금신청</p>
						</div>
						<div class="event active">
							<p class="en">EVENT</p>
							<p class="kr">이벤트</p>
						</div>
						<div class="notice">
							<p class="en">NOTICE</p>
							<p class="kr">공지사항</p>
						</div>
					</div>
				</div>

				<div class="nav-mdl">
					<div class="nav-cont">
						<div class="nav-btn">
							<button class="deposit-link active">
								<i class="icon icon-Bag"></i>
								<p class="text">입금신청</p>
							</button>
						</div>
						<div class="nav-btn">
							<button class="withdraw-link">
								<i class="icon icon-Dollar"></i>
								<p class="text">출금신청</p>
							</button>
						</div>
						<div class="nav-btn active">
							<button class="event-link">
								<i class="icon icon-Agenda"></i>
								<p class="text">이벤트</p>
							</button>
						</div>
						<div class="nav-btn">
							<button class="notice-link">
								<i class="icon icon-Megaphone"></i>
								<p class="text">공지사항</p>
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
                  <p>회원정보</p>
                </div>
                <div class="infos">
                  <p>@if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
                        {{ Auth::user()->username }}
                        @endif</p>
                </div>
              </div>
              <div class="form-group">
									<div class="labels">
										<p>은행명</p>
									</div>
									<div class="infos">
										<label>
											<select id="bankname" class="form-control " data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." name="bankname" data-parsley-id="5166" value="">
                        <option value="{{Auth::check()?auth()->user()->bank_name:''}}" selected>{{Auth::check()?auth()->user()->bank_name:''}}</option>
											</select><ul class="parsley-errors-list" id="parsley-id-5166"></ul>
										</label>
									</div>
								</div>
								<div class="form-group">
									<div class="labels">
										<p>계좌번호</p>
									</div>
									<div class="infos">
										<input id="accountnumber" class="form-control "  name="accountnumber" type="text" value="{{Auth::check()?('***'.substr(auth()->user()->account_no,-2)):''}}"  disabled><ul class="parsley-errors-list" id="parsley-id-1905"></ul>
									</div>
								</div>
              <div class="form-group">
                <div class="labels">
                  <p>입금자명</p>
                </div>
                <div class="infos">
                  <input id="recommender" class="form-control "  name="recommender" type="text" value="{{Auth::check()?(mb_substr(auth()->user()->recommender,0,1).'***'):''}}"  disabled><ul class="parsley-errors-list" id="parsley-id-0017" ></ul>
                </div>
              </div>
              <div class="form-group">
                <div class="labels">
                  <p>입금금액</p>
                </div>
                <div class="infos">
                  <input class="form-control w400" id="depositAmount" data-parsley-type="digits" data-parsley-type-message="유효하지 않은 값입니다." data-parsley-pattern="/^[0-9]*$/" data-parsley-pattern-message="유효하지 않은 값입니다." data-parsley-trigger="focusin change" data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." data-parsley-min="10000" data-parsley-min-message="최소입금 10,000원 이상" placeholder="최소입금 10,000원 부터가능(만원단위입금)" name="money" type="text" value="" data-parsley-id="0361"><ul class="parsley-errors-list" id="parsley-id-0361"></ul>
                  <div class="btn-grp" style="margin-top: 25px;">
                    <button type="button" onclick="addAmount(1,1)">1만</button>
                    <button type="button" onclick="addAmount(1,3)">3만</button>
                    <button type="button" onclick="addAmount(1,5)">5만</button>
                    <button type="button" onclick="addAmount(1,10)">10만</button>
                    <button type="button" onclick="addAmount(1,50)">50만</button>
                    <button type="button" onclick="resetAmount(1)">정정하기</button>
                  </div>
                </div>
              </div>
              <div class="deposit-ask">
                <button type="button" onclick="askAccount();">
                  <i class="icon icon-Info"></i>
                  <span>입금계좌문의</span>
                </button>
                <p id="bankinfo">* 입금시 꼭 계좌문의를 하세요!</p>
              </div>
            </div>
            <div class="modal-btn-foot">
              <div class="btn-cont">
                <button type="submit" class="green">입금하기</button>
                <button type="reset" class="blue" data-dismiss="modal">취소</button>
              </div>
            </div>
          </form>

          
        </div>

    <script>
      var df = $("#depoFrm");
      df.submit(function (e) {
        var amount = parseInt($("[name=money]").val());
        if (amount % 10000 > 0) {
          alert_error('입금금액은 만원단위로 입력하세요.');
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
              <p>보유금액</p>
            </div>
            <div class="infos">
              <p class="player-balance">
              @if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
              {{ number_format(Auth::user()->balance,0)}} 원
              @endif
              </p>
            </div>
          </div>
          <div class="form-group">
            <div class="labels">
              <p>은행명</p>
            </div>
            <div class="infos">
              <label>
                <select id="bankname" class="form-control " data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." name="bankname" data-parsley-id="7298">
                  <option value="{{Auth::check()?auth()->user()->bank_name:''}}" selected>{{Auth::check()?auth()->user()->bank_name:''}}</option>
                </select><ul class="parsley-errors-list" id="parsley-id-7298"></ul>
              </label>
            </div>
          </div>
          <div class="form-group">
            <div class="labels">
              <p>계좌번호</p>
            </div>
            <div class="infos">
              <input id="accountnumber" class="form-control " name="accountnumber" type="text" value="{{Auth::check()? ('***'.substr(auth()->user()->account_no,-2)) : ''}}"  disabled><ul class="parsley-errors-list" id="parsley-id-1905"></ul>
            </div>
          </div>
          <div class="form-group">
            <div class="labels">
              <p>출금자명</p>
            </div>
            <div class="infos">
              <input id="recommender" class="form-control "  name="recommender" type="text" value="{{Auth::check()? (mb_substr(auth()->user()->recommender,0,1).'***'):''}}"  disabled><ul class="parsley-errors-list" id="parsley-id-0017"></ul>
            </div>
          </div>
          <div class="form-group">
            <div class="labels">
              <p>출금비밀번호</p>
            </div>
            <div class="infos">
              <input id="confirmation_token" class="form-control"  data-parsley-trigger="change" data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." name="recommender" type="password" value="" data-parsley-id="0017"><ul class="parsley-errors-list" id="parsley-id-0019"></ul>
            </div>
          </div>

          <div class="form-group">
            <div class="labels">
              <p>출금금액</p>
            </div>
            <div class="infos">
              <input class="form-control w400 withdrawal_amount" id="withdrawalAmount" data-parsley-type="digits" data-parsley-type-message="유효하지 않은 값입니다." data-parsley-pattern="/^[0-9]*$/" data-parsley-pattern-message="유효하지 않은 값입니다." data-parsley-trigger="focus change" data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." data-parsley-min="10000" data-parsley-min-message="최소출금 10,000원 이상" placeholder="최소출금 10,000원 부터 가능(만원단위출금)" name="money" type="text" value="" data-parsley-id="5344"><ul class="parsley-errors-list" id="parsley-id-5344"></ul>
              <div class="btn-grp" style="margin-top: 25px;">
                <button type="button" onclick="addAmount(2,1)">1만</button>
                <button type="button" onclick="addAmount(2,3)">3만</button>
                <button type="button" onclick="addAmount(2,5)">5만</button>
                <button type="button" onclick="addAmount(2,10)">10만</button>
                <button type="button" onclick="addAmount(2,50)">50만</button>
                <button type="button" onclick="resetAmount(2)">정정</button>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-btn-foot">
          <div class="btn-cont">
            <button type="submit" class="over-style red">
              <span class="os-cont">출금하기</span>
            </button>
            <button type="reset" class="over-style" data-dismiss="modal">
              <span class="os-cont">취소</span>
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
          alert_error('출금금액은 만원단위로 입력하세요.');
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
              <th>번호</th>
              <th>제목</th>
              <th>작성일</th>
            </tr>
          </thead>
          <tbody>


          <tr>
            <td class="text-center" colspan="3">게시물이 없습니다.</td>
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
            <th>번호</th>
            <th>제목</th>
            <th>작성일</th>
          </tr>
        </thead>
        <tbody>
        <?php $nidx = 1; ?>
          @foreach ($noticelist as $notice)
            @if ($notice->popup == 'general')
            <tr class="depth-click">
              <td>{{$nidx}}</td>
              <td>{{$notice->title}}</td>
              <td>{{$notice->date_time}}</td>
            </tr>
            <tr class="dropdown">
              <td colspan="3">
                <div class="mess-cont" style="display: none;">
                  <div class="inner">
                    <?php echo $notice->content ?>
                  </div>
                </div>
              </td>
            </tr>
            <?php $nidx = $nidx + 1; ?>

            @endif
          @endforeach
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
            <button type="button" class="mdl-close-btn" data-dismiss="modal">닫기</button>
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
                                <p class="text">전체</p>
                            </button>
                        </div>
                        <div class="nav-btn">
                            <button type="button" onclick="gameDivision($(this))" data-target=".p-tab">
                                <i class="icon icon-Controller"></i>
                                <p class="text">인기</p>
                            </button>
                        </div>
                        <div class="nav-btn">
                            <button type="button" onclick="gameDivision($(this))" data-target=".s-tab">
                                <i class="icon icon-Controller"></i>
                                <p class="text">슬롯</p>
                            </button>
                        </div>
                        <div class="nav-btn">
                            <button type="button" onclick="gameDivision($(this))" data-target=".t-tab">
                                <i class="icon icon-Controller"></i>
                                <p class="text">테이블</p>
                            </button>
                        </div>
                        <div class="nav-btn">
                            <button type="button" onclick="gameDivision($(this))" data-target=".e-tab">
                                <i class="icon icon-Controller"></i>
                                <p class="text">기타</p>
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
			<button class="mdl-close-btn" data-dismiss="modal">닫기</button>
			<div class="bsm-inner">
				<div class="mdl-head">
					<div class="mdl-title">
						<div class="active">
							<p class="en">MY PAGE</p>
							<p class="kr">마이페이지</p>
						</div>
					</div>
				</div>
				<div class="nav-mdl three-mob three">
					<div class="nav-cont">
						<div class="nav-btn active" data-id="DP" data-target=".deposit-list">
							<button>
								<i class="icon icon-Bag"></i>
								<p class="text">입금</p>
							</button>
						</div>
						<div class="nav-btn" data-id="WT" data-target=".withdraw-list">
							<button>
								<i class="icon icon-Dollar"></i>
								<p class="text">출금</p>
							</button>
						</div>
						<div class="nav-btn" data-id="BH" data-target=".bonuses-list">
							<button>
								<i class="icon icon-DiamondRing"></i>
								<p class="text">보너스내역</p>
							</button>
						</div>
					</div>
				</div>
				<div class="modal-body">
					<div class="tab-mdl active">
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
										<th>구분</th>
										<th>금액</th>
										<th>상태</th>
										<th>날짜</th>
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
										<th>구분</th>
										<th>금액</th>
										<th>상태</th>
										<th>날짜</th>
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
										<th>보너스내역</th>
										<th>종류</th>
										<th>금액</th>
										<th>롤링금액</th>
										<th>상태</th>
										<th>날짜</th>
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
<?php $pidx=0;?>
@foreach ($noticelist as $notice)
@if ($notice->popup == 'popup')
<div class="pop1" id="pop{{$notice->id}}" style="left:{{300+$pidx*350}}px;">
    <?php echo $notice->content ?>
    <p></br></p>
    <div style="position:absolute;left:5px;bottom:5px;width:190px;background-color:transparent;color:white;cursor: hand;display:content">
      <input type="checkbox" name="notice1" id="notice{{$notice->id}}" value="" style="width:20px;">
      <label for="notice{{$notice->id}}">오늘 하루 열지 않음 <span style="cursor:pointer;" onclick="closeWin(document.getElementById('pop{{$notice->id}}'), {{$notice->id}}, document.getElementById('notice{{$notice->id}}').checked);">[닫기]</span></label>
    </div>
  </div>
  <?php $pidx=$pidx+1;?>
@endif
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
      
      if ($("input[name='userid']").val() == "아이디" || $("input[name='userid']").val() == "") {
          alert("로그인 아이디를 입력해 주세요");
          frm.userid.focus();
          return;
      }
      if ($("input[name='password']").val() == "******" || $("input[name='password']").val() == "") {
          alert("비밀번호를 입력해 주세요");
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
    // if (!depositAccountRequested)
    // {
    //   alert_error('입금계좌요청을 먼저 하세요');
    //   return;
    // }
    $.ajax({
        type: "POST",
        url: "/api/addbalance",
        data: { 
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

            alert_ok("충전 신청이 완료되었습니다.");
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

            alert_ok("환전 신청이 완료되었습니다.");
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
            alert_ok_reload('수익금이 보유금으로 전환되었습니다.', '/');
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
  @if ($unreadmsg>0)
      alert_ok('새로운 쪽지가 도착했습니다');
  @endif
  });

  
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