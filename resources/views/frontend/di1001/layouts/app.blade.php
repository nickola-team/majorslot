<!-- include file ="BotDetect.asp" -->

<!DOCTYPE html>
<html>
	
<!-- Mirrored from di-1010.com/login by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 14 Nov 2021 01:59:19 GMT -->
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		
		<title>@yield('page-title')</title>
		<meta name="viewport" content="width=1600,minimum-scale=0,maximum-scale=5,target-densitydpi=device-dpi">
		<link rel="shortcut icon" href="/frontend/di1001/images/favicon/favicon.html" type="image/x-icon">

		<link rel="stylesheet" type="text/css" href="/frontend/di1001/tutu/css/common.css">
		<link rel="stylesheet" type="text/css" href="/frontend/di1001/tutu/css/basic.css">
		<link rel="stylesheet" type="text/css" href="/frontend/di1001/tutu/css/layoutb04c.css?v=31234">
		<link rel="stylesheet" type="text/css" href="/frontend/di1001/tutu/css/animations.css">
		<link rel="stylesheet" type="text/css" href="/frontend/di1001/css/odometer-theme-default.css">

		<script type="text/javascript" src="/frontend/di1001/tutu/js/showid.js"></script>
		<script type="text/javascript" src="/frontend/di1001/js/jquery-1.8.3.min.js"></script>
		<script type="text/javascript" src="/frontend/di1001/tutu/jq/sk_popup/sk_popup.js"></script>
		<script type="text/javascript" src="/frontend/di1001/tutu/js/sk_tab.js"></script>
		<script type="text/javascript" src="/frontend/di1001/js/sweetalert.min.js"></script>
		<script type="text/javascript" src="/frontend/di1001/js/ajax_utf_8.js"></script>
		<script type="text/javascript" src="/frontend/di1001/js/playd7cf.js?v=xxx1.2"></script>
		<script type="text/javascript" src="/frontend/di1001/js/swiper.min.js"></script>
		<script type="text/javascript" src="/frontend/di1001/js/script3860.js?v=1"></script>
		<script type="text/javascript" src="/frontend/di1001/js/paging.min.js"></script>
		<script type="text/javascript" src="/frontend/di1001/js/jsRolling.js"></script>
		<script type="text/javascript" src="/frontend/di1001/js/numeric.js"></script>
		<script type="text/javascript" src="/frontend/di1001/js/odometer.min.js"></script>
		<script type="text/javascript" src="/frontend/di1001/js/action.js"></script>
		<script type="text/javascript" src="/frontend/di1001/tutu/jq/common/common.js"></script><!-- script -->
	</head>
	<div class="common_ajax"></div>
	<script type="text/javascript">
		$("#strLoginPwd").keypress(function(e) { 
			if (e.keyCode == 13){
				LoginProc('pc');
		
			}    
		});
		
		$("#strLoginID").keypress(function(e) { 
			if (e.keyCode == 13){
				LoginProc('pc');
		
			}    
		});
		
		function LoginProc(p){
			var userid = $("#strLoginID").val();
			var userpw = $("#strLoginPwd").val();

			if(userid.length < 1){
				alert('아이디를 입력 해 주세요.');
				return false;
			}else if(userpw.length < 1){
				alert('비밀번호를 입력 해 주세요.');
				return false;
			}

			var formData = new FormData();
			formData.append("_token", $("#_token").val());
			formData.append("username", userid);
			formData.append("password", userpw);

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
						alert('아이디 또는 비밀번호가 잘못되었습니다.');
					}

					location.reload();
				},
				error: function (err, xhr) {
				}
			});
		}

		function toggleRealtime(e) {
			if(e == 'deposit'){
				$("#realtimeWithdraw").hide();
				$("#realtimeDeposit").show().css("display", "table");
				$("#dp_list_btn").attr("src", "/frontend/di1001/images/main_con3_1_title_on.html");
				$("#wd_list_btn").attr("src", "/frontend/di1001/images/main_con3_2_title.html");
				$('.main_con2_title1').removeClass('taboff');
				$('.main_con2_title2').removeClass('taboff');
				$('.main_con2_title1').addClass('tabon');
				$('.main_con2_title2').addClass('taboff');


			}else {
				$("#realtimeDeposit").hide();
				$("#realtimeWithdraw").show().css("display", "table");
				$("#dp_list_btn").attr("src", "/frontend/di1001/images/main_con3_1_title.html");
				$("#wd_list_btn").attr("src", "/frontend/di1001/images/main_con3_2_title_on.html");

				$('.main_con2_title1').removeClass('taboff');
				$('.main_con2_title2').removeClass('taboff');
				$('.main_con2_title2').addClass('tabon');
				$('.main_con2_title1').addClass('taboff');
			}
		}

		var roller;
		var roller2;
		
		function roll(rows) {
			var table = $('#realtimeDeposit');
			var allRows = table[0].rows;
			console.log(allRows);
			table.animate (
				{ 'marginTop': '-30px'}, 
				1000, 
				function () {
					for (var i = 0; i < rows; i++) {
						var targetRow = allRows[0];
						$('#realtimeDeposit').append(targetRow);
					}
					$(this).css("marginTop", 0);
			});
		}
		
		function roll2(rows) {
			var table = $('#realtimeWithdraw');
			var allRows = table[0].rows;
			table.animate (
				{ 'marginTop': '-30px'}, 
				1000, 
				function () {
					for (var i = 0; i < rows; i++) {
						var targetRow = allRows[0];
						$('#realtimeWithdraw').append(targetRow);
					}
					$(this).css("marginTop", 0);
			});
		}
		
		function startRepeat() {
			roller = setInterval(function () { roll(1); }, 5000);
		}
		function stopRepeat() {
			clearInterval(roller);
		}
		function init() {
			roller = setInterval(function () { roll(1); }, 5000);
		}
		
		function startRepeat2() {
			roller2 = setInterval(function () { roll2(1); }, 5000);
		}
		function stopRepeat2() {
			clearInterval(roller2);
		}
		function init2() {
			roller2 = setInterval(function () { roll2(1); }, 5000);
		}

		function startRepeatNew() {
			//메인배너 루프 타이머 : 5초
			$('.carousel').carousel({
				interval: 5000
			});
			$(function () {
				autoScroll('scroll-deposit');
				autoScroll('scroll-withdraw');
			});
		}	
	</script>

	<body class="overflow-x:hidden;" style="background-color: #0967c4">
		<input type="hidden" value="<?= csrf_token() ?>" name="_token" id="_token">
		<div id="casino_1" class="popup_style02 popup_none">
			<div class="popup_wrap">
				<div class="close_box"><a href="#" class="casino_1_close"><img src="/frontend/di1001/tutu/images/popup_close.png"></a></div>
				<div class="popupbox" id="popupbox_ajax"></div>
				</div>
			</div>
		</div>
		
		

		<!-- 입금신청, 출금신청, 머니이동, 쿠폰신청, 콤프신청, 이벤트, 공지사항 -->
		<div id="sub_pop1" class="popup_style02 popup_none">
			<div class="popup_wrap">
				<div class="close_box"><a href="#" class="sub_pop1_close"><img src="/frontend/di1001/tutu/images/popup_close.png"></a></div>
				<div class="popupbox">
					<div class="popup_tab_wrap">						
						<ul class="popup_tab popup_tab1">
						@if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
                            <li class="tab1" data-target="#sk_tab_con_01_1"><a href="javascript:;" onclick="tabActionProc('tab1','charge');"><span>입금신청</span></a></li>
                            <li class="tab2 sk_tab_active_01" data-target="#sk_tab_con_01_1"><a href="javascript:;" onclick="tabActionProc('tab2','excharge');"><span>출금신청</span></a></li>
                            <li class="tab3" data-target="#sk_tab_con_01_1"><a href="javascript:;" onclick="tabActionProc('tab3','inoutList');"><span>입출금내역</span></a></li>
                            <li class="tab4" data-target="#sk_tab_con_01_1"><a href="javascript:;" onclick="tabActionProc('tab4','letterList');"><span>쪽지</span></a></li>
                            <li class="tab5" data-target="#sk_tab_con_01_1"><a href="javascript:;" onclick="tabActionProc('tab5','chatList');"><span>고객센터</span></a></li>
                            <li class="tab6" data-target="#sk_tab_con_01_1"><a href="javascript:;" onclick="tabActionProc('tab6','noticeList');"><span>공지사항</span></a></li>
						@else
						<li class="tab1 sk_tab_active_01"><a href="javascript:;" onclick="javascript:swal('로그인 하여 주세요.');return false;"><span>입금신청</span></a></li>
							<li class="tab2"><a href="javascript:;" onclick="javascript:swal('로그인 하여 주세요.');return false;"><span>출금신청</span></a></li>
							<li class="tab3"><a href="javascript:;" onclick="javascript:swal('로그인 하여 주세요.');return false;"><span>머니이동</span></a></li>
							<li class="tab4"><a href="javascript:;" onclick="javascript:swal('로그인 하여 주세요.');return false;"><span>쿠폰신청</span></a></li>
							<li class="tab5"><a href="javascript:;" onclick="javascript:swal('로그인 하여 주세요.');return false;"><span>콤프신청</span></a></li>
							<li class="tab6"><a href="javascript:;" onclick="javascript:swal('로그인 하여 주세요.');return false;"><span>이벤트</span></a></li>
							<li class="tab7"><a href="javascript:;" onclick="javascript:swal('로그인 하여 주세요.');return false;"><span>공지사항</span></a></li>         
						@endif
                        </ul>
					</div>
					
					<div id="sk_tab_con_01_1" class="sk_tab_con_01"></div>
				</div>
			</div>
		</div>

		<!-- 마이페이지, 정보수정, 입금/출금내역, 지인친구목록, 출석체크목록, 머니이동목록, 쪽지함 -->
		<div id="sub_pop2" class="popup_style02 popup_none">
			<div class="popup_wrap">
				<div class="close_box"><a href="#" class="sub_pop2_close"><img src="/frontend/di1001/tutu/images/popup_close.png"></a></div>
				<div class="popupbox">            
					<div class="popup_tab_wrap">
						<ul class="popup_tab popup_tab2">
							<li class="tab1 sk_tab_active_02" data-target="#sk_tab_con_02_1"><a href="javascript:;" onClick="tabActionMy('tab1','mypage');"><span>MY페이지</span></a></li>
							<li class="tab2" data-target="#sk_tab_con_02_1"><a href="javascript:;" onClick="tabActionMy('tab2','edit');"><span>정보수정</span></a></li>
							<li class="tab3" data-target="#sk_tab_con_02_1"><a href="javascript:;" onClick="tabActionMy('tab3','banklist');"><span>입금/출금내역</span></a></li>
							<li class="tab4" data-target="#sk_tab_con_02_1"><a href="javascript:;" onClick="tabActionMy('tab4','recommend');"><span>지인친구목록</span></a></li>
							<li class="tab5" data-target="#sk_tab_con_02_1"><a href="javascript:;" onClick="tabActionMy('tab5','attendance');"><span>출석체크목록</span></a></li>
							<li class="tab6" data-target="#sk_tab_con_02_1"><a href="javascript:;" onClick="tabActionMy('tab6','bankmove');"><span>머니이동목록</span></a></li>                           
							<li class="tab7" data-target="#sk_tab_con_02_1"><a href="javascript:;" onClick="tabActionMy('tab7','message');"><span>쪽지함</span></a></li>                                                                                  
						</ul>
					</div> 
					<div id="sk_tab_con_02_1" class="sk_tab_con_02"></div>
				</div>
			</div>
		</div>

		<!-- ★메인★ -->
		<div id="wrap">
			<div id="header_wrap">
				<div class="header_box">
					<div class="logo">
						<a href="/"><img src="/frontend/di1001/tutu/images/logoae52.png?v=5" style="width:250px;"></a>
					</div>
					<div class="gnb">						
						
						<ul>
						@if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
							<li><a href="#" class="sub_pop1_open" onmouseover="show_over(this);" onmouseout="show_out(this);" onclick="tabActionProc('tab1','charge');" data-popup-ordinal="0" id="open_34483963"><img src="/frontend/di1001/tutu/images/gnb1.png" style="display: inline;"><img src="/frontend/di1001/tutu/images/gnb1over.png" style="display: none;" class="mouseover3"></a></li>
                            <li><a href="#" class="sub_pop1_open" onmouseover="show_over(this);" onmouseout="show_out(this);" onclick="tabActionProc('tab2','excharge');" data-popup-ordinal="1" id="open_80823455"><img src="/frontend/di1001/tutu/images/gnb2.png" style="display: inline;"><img src="/frontend/di1001/tutu/images/gnb2over.png" style="display: none;" class="mouseover3"></a></li>
                            <li><a href="#" class="sub_pop1_open" onmouseover="show_over(this);" onmouseout="show_out(this);" onclick="tabActionProc('tab3','inoutList');" data-popup-ordinal="2" id="open_9185439"><img src="/frontend/di1001/tutu/images/gnb11.png" style="display: inline;"><img src="/frontend/di1001/tutu/images/gnb11over.png" style="display: none;" class="mouseover3"></a></li>
                            <li style="margin-left: 10px;"><a href="#" class="sub_pop1_open" onmouseover="show_over(this);" onmouseout="show_out(this);" onclick="tabActionProc('tab4','letterList');" data-popup-ordinal="3" id="open_59824731"><img src="/frontend/di1001/tutu/images/gnb9.png" style="display: inline;"><img src="/frontend/di1001/tutu/images/gnb9over.png" style="display: none;" class="mouseover3"></a></li>
                            <li style="margin-left: 10px;"><a href="#" class="sub_pop1_open" onmouseover="show_over(this);" onmouseout="show_out(this);" onclick="tabActionProc('tab5','chatList');" data-popup-ordinal="4" id="open_81567995"><img src="/frontend/di1001/tutu/images/gnb8.png" style="display: inline;"><img src="/frontend/di1001/tutu/images/gnb8over.png" style="display: none;" class="mouseover3"></a></li>
                            <li><a href="#" class="sub_pop1_open" onmouseover="show_over(this);" onmouseout="show_out(this);" onclick="tabActionProc('tab6','noticeList');" data-popup-ordinal="5" id="open_37017382"><img src="/frontend/di1001/tutu/images/gnb7.png" style="display: inline;"><img src="/frontend/di1001/tutu/images/gnb7over.png" style="display: none;" class="mouseover3"></a></li>
							
						@else							
							<li><a href="#" class="sub_pop1_open" onMouseOver="show_over(this);" onMouseOut="show_out(this);" onclick="javascript:swal('로그인 하여 주세요.');return false;"><img src="/frontend/di1001/tutu/images/gnb1.png"><img src="/frontend/di1001/tutu/images/gnb1over.png" style="display:none;" class="mouseover3"></a></li>
                            <li><a href="#" class="sub_pop1_open" onMouseOver="show_over(this);" onMouseOut="show_out(this);" onclick="javascript:swal('로그인 하여 주세요.');return false;"><img src="/frontend/di1001/tutu/images/gnb2.png"><img src="/frontend/di1001/tutu/images/gnb2over.png" style="display:none;" class="mouseover3"></a></li>
                            <li><a href="#" class="sub_pop1_open" onMouseOver="show_over(this);" onMouseOut="show_out(this);" onclick="javascript:swal('로그인 하여 주세요.');return false;"><img src="/frontend/di1001/tutu/images/gnb11.png"><img src="/frontend/di1001/tutu/images/gnb11over.png" style="display:none;" class="mouseover3"></a></li>
                            <li style="margin-left: 10px;"><a href="#" class="sub_pop1_open" onMouseOver="show_over(this);" onMouseOut="show_out(this);" onclick="javascript:swal('로그인 하여 주세요.');return false;"><img src="/frontend/di1001/tutu/images/gnb9.png"><img src="/frontend/di1001/tutu/images/gnb9over.png" style="display:none;" class="mouseover3"></a></li>
                            <li style="margin-left: 10px;"><a href="#" class="sub_pop1_open" onMouseOver="show_over(this);" onMouseOut="show_out(this);" onclick="javascript:swal('로그인 하여 주세요.');return false;"><img src="/frontend/di1001/tutu/images/gnb8.png"><img src="/frontend/di1001/tutu/images/gnb8over.png" style="display:none;" class="mouseover3"></a></li>
                            <li><a href="#" class="sub_pop1_open" onMouseOver="show_over(this);" onMouseOut="show_out(this);" onclick="javascript:swal('로그인 하여 주세요.');return false;"><img src="/frontend/di1001/tutu/images/gnb7.png"><img src="/frontend/di1001/tutu/images/gnb7over.png" style="display:none;" class="mouseover3"></a></li>
						@endif
						</ul>
						
					</div>
				</div>
			</div>

			
			<div class="login_wrap">
				<div class="login">
				@if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
					<ul>
                        <li data-target="#sk_tab_con_01_1"><a href="#" onclick="tabActionProc('tab4','letterList');" class="sub_pop2_open"></a>&nbsp;&nbsp;&nbsp;</li>                
                        <li style="color:#222"><img src="/frontend/di1001/tutu/images/5UNZI____.png" width="20">&nbsp;{{ Auth::user()->username }}님 환영합니다.</li>
                        <li style="color:#222"><img src="/frontend/di1001/tutu/images/coin.png" width="20">&nbsp;&nbsp;<span id="lnOwnMoney">{{ number_format(Auth::user()->balance) }}</span> 원</li>
						<li><a href="/logout"><span class="login_btn2">로그아웃</span></a></li>
                    </ul>
				@else
					<ul>
					<li><input id="strLoginID" type="text" class="input_login" placeholder="아이디"></li>                    
					<li><input id="strLoginPwd" type="password" onkeypress="if(event.keyCode == 13) LoginProc('pc');" class="input_login" placeholder="비밀번호"></li>                
						<li><a href="#" onclick="javascript:LoginProc('pc');"><span class="login_btn1">로그인</span></a></li>                
						<li><a href="#" onclick="tabActionPop('','register');" class="casino_1_open"><span class="login_btn1">회원가입</span></a></li>                                         
					</ul>
				@endif
				</div> 
			</div> 
			
			<!-- ▼slideshow6▼ -->
			<script src="/frontend/di1001/tutu/jq/slideshow6/flux.js" type="text/javascript" charset="utf-8"></script>
			<script type="text/javascript" charset="utf-8">
				$(function(){
					if(!flux.browser.supportsTransitions)
						alert("Flux Slider requires a browser that supports CSS3 transitions");
						
					window.f = new flux.slider('#slider', {
						pagination: false,
						controls: false,
						//ysk transitions: ['explode', 'tiles3d', 'bars3d', 'cube', 'turn'], (터지는, 타일, 바3D, 큐브, 턴)
						transitions: ['turn', 'turn', 'turn', 'turn', 'turn'],				
						autoplay: true //ysk 자동재생
					});		
				});
			</script>

			<div style="position:relative; height:660px; overflow:hidden; clear:both;">
				<div style="position:absolute; width:1920px; left:50%; margin-left:-960px;">   
					<div id="slider">
												<img src="/frontend/di1001/tutu/images/slideshow1.jpg">
												<img src="/frontend/di1001/tutu/images/slideshow2.jpg">
					</div>
				</div>
			</div>
			<!-- ▲slideshow6▲ -->
			<script type="text/javascript" src="/frontend/di1001/tutu/js/sk_table.js"></script><!-- sk_실시간입출금현황 롤링 -->
			

			<div class="main_game_wrap">
				<div class="main_game_box">
					<div class="main_game">
						<ul>
							@if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
							
							<li><a href="#" onmouseover="show_over(this);" onmouseout="show_out(this);" onclick="tabActionPop('','subSlot');" class="casino_1_open" data-popup-ordinal="0" id="open_64663418"><img src="/frontend/di1001/tutu/images/main_game1.png" style="display: inline;"><img src="/frontend/di1001/tutu/images/main_game1.png" class="mouseover1" style="display: none;"></a></li>
                            <li><a href="#" onmouseover="show_over(this);" onmouseout="show_out(this);" onclick="javascript:swal('준비중입니다.');return false;"><img src="/frontend/di1001/tutu/images/main_game2.png" style="display: inline;"><img src="/frontend/di1001/tutu/images/main_game2.png" class="mouseover1" style="display: none;"></a></li>                
                                                                             
							@else
							<li><a href="#" onMouseOver="show_over(this);" onMouseOut="show_out(this);" onclick="javascript:swal('로그인 하여 주세요.');return false;"><img src="/frontend/di1001/tutu/images/main_game1.png"><img src="/frontend/di1001/tutu/images/main_game1.png" class="mouseover1" style="display:none;"></a></li>
							<li><a href="#" onMouseOver="show_over(this);" onMouseOut="show_out(this);" onclick="javascript:swal('로그인 하여 주세요.');return false;"><img src="/frontend/di1001/tutu/images/main_game2.png"><img src="/frontend/di1001/tutu/images/main_game2.png" class="mouseover1" style="display:none;"></a></li>                
							@endif
						</ul>
					</div>
				</div>
			</div>
						<div class="main_best_wrap">
				<div class="main_best_box">
					<div class="main_best_title"><span class="main_best_title_jackpot"><img src="/frontend/di1001/tutu/images/jackpot.png"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<iframe src="/frontend/di1001/common/jackpot.html" frameborder="0" width="900px" height="80" scrolling="auto" style="position:absolute;margin-left:-900px;margin-top:-33px"></iframe>
					</span></div>
					<div class="game_list_wrap">
						<ul>
						<?php $catcount = 0; ?>
						@if ($categories && count($categories))
							@foreach($categories AS $index=>$category)
								@if($category->title != "Hot" && $category->title != "Card" && $category->title != "Bingo" && $category->title != "Roulette" 
								&& $category->title != "Novomatic" && $category->title != "Keno" && $category->title != "Vision" && $category->title != "Wazdan" && $category->title != "New" 
								&& $category->title != "Skywind" )
								<li>
									@if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
									<a href="#" onMouseOver="show_over(this);" onMouseOut="show_out(this);" onclick="getSlotGames('{{ $category->trans->trans_title }}', '{{ $category->href }}', 0)">
									
									@else
									<a href="#" onMouseOver="show_over(this);" onMouseOut="show_out(this);" onclick="javascript:swal('로그인 하여 주세요.');return false;">
									@endif
									@if (file_exists( public_path() . '/frontend/di1001/tutu/images/slot-icon/' . $category->title.'.gif'))
										<img src="/frontend/di1001/tutu/images/slot-icon/{{ $category->title.'.gif' }}" style="width: 252px;height: 230px;border-radius:30px">
									@else										
										<img src="/frontend/di1001/tutu/images/slot-icon/{{ $category->title.'.png' }}" style="width: 252px;height: 230px;border-radius:30px">
									@endif
										<img src="/frontend/di1001/tutu/images/best_over01.png" class="mouseover2" style="display:none;width: 252px;">
									</a>     
								</li>
								<?php $catcount = $catcount +1; ?>
								@endif
							@endforeach
							<?php
								$comingSoon = (intval(($catcount-1)/5) + 1 ) * 5 - $catcount;
							?>
							@for ($i=0;$i<$comingSoon;$i++)
							<li>
								<a href="#" onMouseOver="show_over(this);" onMouseOut="show_out(this);" onclick="javascript:swal('로그인 하여 주세요.');return false;">
									<img src="/frontend/j9sl/tutu/images/slot-icon/coming_soon.png" style="width: 252px;height: 230px;border-radius:30px">
									<img src="/frontend/j9sl/tutu/images/best_over01.png" class="mouseover2" style="display:none;width: 252px;">
								</a>
							</li>
							@endfor
						@endif

						</ul> 
					</div>        
				</div>
			</div>
					</div><!-- wrap -->

		<script type="text/javascript">
			
			function tabActionPop(obj, pid) {

				if(pid == "register"){
					data=`<form id="frm_join" name="frm_join">
							<div class="title1">회원가입</div>
								<div class="contents_in_2">
									<div class="con_box10">
										<table width="100%" border="0" cellspacing="0" cellpadding="0" class="write_title_top">
										<tr>
											<td class="write_title">아이디</td><td class="write_td"></td>
											<td class="write_basic"><input class="input1" name="memberid" type="text" id="strID" onblur="CheckId(this);"><span>&nbsp;&nbsp;( 3 ~ 10글자 이여야 합니다. )</span></td>
										</tr> 
										<tr>
											<td class="write_title">비밀번호</td><td class="write_td"></td>\r\n<td class="write_basic"><input class="input1" name="memberpw" type="password" id="strPW"><span>&nbsp;&nbsp;( 영문, 숫자, 특수문자가 들어가야 합니다. )</span></td>
										</tr>
										<tr><td class="write_title">은행선택</td><td class="write_td"></td>\r\n<td class="write_basic">   
											<select class="input1" name="bankname" id="strBankName" onchange="directBankName(this);"></select>    <span id="banknmId" style="display: none;">
											<input class="input1" name="banknm" type="text" id="strDirBankName" maxlength="20" placeholder="은행명직접입력"></span></td></tr> <tr><td class="write_title">예금주</td><td class="write_td"></td><td class="write_basic">    <input class="input1" type="text" id="strBankUser" onblur="CheckHangul(this);"><span>&nbsp;&nbsp;( 입금과 출금시 사용하시는 실제 예금주명으로 기입하여 주시기 바랍니다 )</span></td></tr><tr><td class="write_title">계좌번호</td><td class="write_td"></td><td class="write_basic">    <input class="input1" name="accountnumber" type="text" id="strBankNum" onkeypress="return digit_check(event)" placeholder="계좌번호를 입력하세요">    <span>&nbsp;&nbsp;( 띄어쓰기와 - 없이 숫자로만 기입하여 주시기 바랍니다 )</span></td></tr>   <tr><td class="write_title">핸드폰</td><td class="write_td"></td><td class="write_basic"><input class="input1" type="tel" id="strPhone" maxlength="16"></td></tr>     <tr><td class="write_title">추천코드</td><td class="write_td"></td><td class="write_basic">    <input class="input1" name="recomcode_id" type="text" id="strMark"></td></tr>\r\n            </table>\r\n        </div>\r\n        <div class="con_box10">\r\n            <div class="btn_wrap_center"><ul><li><a href="#none" onclick="onRegister();return false;"><span class="btn3_1">회원가입완료</span></a></li></ul>\r\n            </div>\r\n        </div>\r\n    </div>\r\n</form>
						`;

					$("#popupbox_ajax").html(data);

					bank = `<option value="">은행명</option>`;
					@foreach(\VanguardLTE\User::$values["banks"] AS $val)

						@if($val != "")
							bank+=`<option value="{{$val}}">{{$val}}</option>`;
						@endif
					@endforeach

					$("#strBankName").html(bank);
				}
				else if(pid == "subSlot"){
					data =`<div class="game_tit"><img src="/frontend/di1001/tutu/images/casino_title3.png"></div><div class="game_list_wrap"><ul>`;
					@if ($categories && count($categories))
						@foreach($categories AS $index=>$category)
							@if($category->title != "Hot" && $category->title != "Card" && $category->title != "Bingo" && $category->title != "Roulette" 
							&& $category->title != "Novomatic" && $category->title != "Keno" && $category->title != "Vision" && $category->title != "Wazdan" && $category->title != "New" 
							&& $category->title != "Skywind" )
							data += `<li>`;
								@if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
								data += `<a href="#" onMouseOver="show_over(this);" onMouseOut="show_out(this);" onclick="getSlotGames('{{ $category->trans->trans_title }}', '{{ $category->href }}', 0)">`;
								
								@else
								data += `<a href="#" onMouseOver="show_over(this);" onMouseOut="show_out(this);" onclick="javascript:swal('로그인 하여 주세요.');return false;">`;
								@endif										
								@if (file_exists( public_path() . '/frontend/di1001/tutu/images/slot/' . $category->title.'.gif'))
								data += `<img src="/frontend/di1001/tutu/images/slot/{{ $category->title.'.gif' }}" style="width: 297px;height: 114px;  display: inline;">`;
								@else
								data += `<img src="/frontend/di1001/tutu/images/slot/{{ $category->title.'.png' }}" style="width: 297px;height: 114px;  display: inline;">`;
								@endif
								data += `<img src="/frontend/di1001/tutu/images/over01.png" class="mouseover2" style="display: none; height: 114px; width: 297px;">`;
								data += `</a>`;     
								data += `</li>`;
							@endif
						@endforeach
					@endif

					data +="</ul></div>";

					$("#popupbox_ajax").html(data);
				}
			}
			function setCookie( name, value, expiredays ) {
                var todayDate = new Date();
                    todayDate.setDate( todayDate.getDate() + expiredays );
                    document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
			}
			function closePopup1(popupid) {
                $("#divpopup" + popupid).hide();
            }

            function closePopup(popupid) {
                setCookie( "divpopup" + popupid, "close" , 1);
                $("#divpopup" + popupid).hide();
            }
			$(document).ready(function(){
                if ( document.cookie.indexOf("divpopup2=close") < 0 ){
                    $("#divpopup2").show();
                }
            });

			function tabActionProc(obj, pid) {
				if(nCheckLetter == 1&& !pid.includes('letter')) {
					alert('먼저 중요 쪽지를 확인해주세요.');
					return;
				}

				if(obj) {
					var tab = $(".popup_tab1 li." + obj).closest(".popup_tab1 > li");
					tab.siblings().removeClass("sk_tab_active_01");
					tab.addClass("sk_tab_active_01");

					var target = $(tab.attr("data-target")+".sk_tab_con_01");
					$(".sk_tab_con_01").addClass("sk_tab_hidden_01");
					target.removeClass("sk_tab_hidden_01");
					target.html("");
				} else {
					$(".sk_tab_con_01").html("");
				}
				data = "";
				if( pid == "charge" ){
					data = `<div id="sk_tab_con_01_1" class="sk_tab_con_01">
								<div class="title1">입금신청 <span style="font-size:14px; color:#ff00f6">&nbsp;&nbsp;&nbsp;&nbsp;* 보이스피싱 및 3자사기로 협박하더라도 협상 및 타협 절대없음 *</span></div><!-- 타이틀 -->                
								<div class="con_box10">
									<div class="info_wrap">
										<div class="info2">주의사항</div>
										<div class="info3">
											- 계좌거래시 입금/출금 이름이 동일해야 입출금이 가능하오니 정확히 입력해 주세요.<br>
											- 수표나 타인계좌로 이름변경하여 입금시 머니몰수 및 아이디정지 처리되오니 타인명의로 입금해야될시 문의를 먼저하시기 바랍니다</span>
										</div>
									</div>
								</div>
								<div class="con_box10">
									<div class="money">
										<ul>`
										@if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
											data+= `<li style="width:250px; text-align:left;"><img src="/frontend/di1001/tutu/images/ww_icon.png" height="26"> 지갑머니 : <span class="font05" id="lnMoney">{{ number_format(Auth::user()->balance) }}원</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>`;
										@endif	
										data+= `</ul>
									</div>
								</div>
								
								<div class="con_box10">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" class="write_title_top">
										<tr>
											<td class="write_title">아이디</td>`;
											@if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
											data+=`	<td class="write_basic"><input class="input1" size="30" value="{{ Auth::user()->username }}" readonly></td>`;
											@endif
											data+=`	</tr> 
										<tr>
											<td class="write_title">은행이름</td>
											<td class="write_basic"><input class="input1 userBankName" size="30" value="{{Auth::check()?Auth::user()->bank_name:''}}"></td>
										</tr>
										<tr>
											<td class="write_title">예금주명</td>
											<td class="write_basic"><input class="input1 userName" size="30" value="{{Auth::check()?Auth::user()->account_no:''}}"></td>
										</tr>

										<tr>
											<td class="write_title">계좌번호</td>
											<td class="write_basic"><input class="input1 accountNo" size="30" value="{{Auth::check()?Auth::user()->recommender:''}}"></td>
										</tr>
										
										<tr>
											<td class="write_title">충전계좌</td>
											<td class="write_basic" id="bank_refresh"><a href="#" id="bank_call_btn" onclick="onAskAccount();"><span class="btn1_2">텔레그램 문의</span></a></td>
										</tr>
										<tr>
											<td class="write_title">신청금액</td>
											<td class="write_basic"><input class="input1" size="30" id="deposit_amount" class="input1 numeric.decimalPlaces" placeholder="0">
												<a href="javascript:addDeposit(10000);"><span class="btn1_2">1만원</span></a>
												<a href="javascript:addDeposit(50000);"><span class="btn1_2">5만원</span></a>
												<a href="javascript:addDeposit(100000);"><span class="btn1_2">10만원</span></a>
												<a href="javascript:addDeposit(500000);"><span class="btn1_2">50만원</span></a>
												<a href="javascript:addDeposit(1000000);"><span class="btn1_2">100만원</span></a>
												<a href="javascript:addDeposit(5000000);"><span class="btn1_2">500만원</span></a>
												<a href="javascript:addDeposit(10000000);"><span class="btn1_2">1000만원</span></a>
												<a href="javascript:addDeposit(0);"><span class="btn1_1">정정</span></a><br>5만원이상 만원단위로 입금신청해주시기 바랍니다.
											</td>
										</tr>  
									</table>                
								</div>
								<div class="con_box20">
									<div class="btn_wrap_center">
										<ul>
											
											<li style="width:30%"><a href="javascript:DepositProc('bank_type');" style="width:30%"><span class="btn3_1" style="width:30%">입금하기</span></a></li>
										</ul>
									</div>
								</div> 
							</div>`;
				}
				else if( pid == "excharge" ){
					data = `<div id="sk_tab_con_01_1" class="sk_tab_con_01">
								<div class="title1">출금신청</div>
								<div class="con_box10">
									<div class="info_wrap">
										<div class="info2">주의사항</div>
										<div class="info3">
											- 출금은 최소 3만원이상 출금신청 해주시기 바랍니다.<br>
											- 출금은 입금 신청하실때 신청하신 계좌로만 출금이 가능합니다. 
										</div>
									</div>
								</div>  
								<div class="con_box10">
									<div class="money">
										<ul>`;
										@if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
											data+=`<li style="width:250px; text-align:left;"><img src="/frontend/di1001/tutu/images/ww_icon.png" height="26"> 지갑머니 : <span class="font05" id="lnMoney">{{ number_format(Auth::user()->balance) }}원</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>`;
										@endif
										data+=`</ul>
									</div>
								</div>
								<div class="con_box10">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" class="write_title_top">
										<tr>
											<td class="write_title">아이디</td>`;
											@if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
											data+=`<td class="write_basic"><input class="input1" size="30" value="{{ Auth::user()->username }}" readonly></td>`;
										@endif
										data+=`</tr>
										<tr>
											<td class="write_title">은행이름</td>
											<td class="write_basic"><input class="input1 userBankName" size="30" value="{{Auth::check()?Auth::user()->bank_name:''}}"></td>
										</tr>
										<tr>
											<td class="write_title">예금주명</td>
											<td class="write_basic"><input class="input1 userName" size="30" value="{{Auth::check()?Auth::user()->account_no:''}}"></td>
										</tr>

										<tr>
											<td class="write_title">계좌번호</td>
											<td class="write_basic"><input class="input1 accountNo" size="30" value="{{Auth::check()?Auth::user()->recommender:''}}"></td>
										</tr>
										<tr>
											<td class="write_title">신청금액</td>
											<td class="write_basic"><input size="30" id="withdraw_amount" class="input1 numeric.decimalPlaces" onkeyup="inputNumberFormat(this);" placeholder="0">
												<a href="javascript:addWithdraw(10000);"><span class="btn1_2">1만원</span></a>
												<a href="javascript:addWithdraw(50000);"><span class="btn1_2">5만원</span></a>
												<a href="javascript:addWithdraw(100000);"><span class="btn1_2">10만원</span></a>
												<a href="javascript:addWithdraw(500000);"><span class="btn1_2">50만원</span></a>
												<a href="javascript:addWithdraw(1000000);"><span class="btn1_2">100만원</span></a>
												<a href="javascript:addWithdraw(5000000);"><span class="btn1_2">500만원</span></a>
												<a href="javascript:addWithdraw(10000000);"><span class="btn1_2">1000만원</span></a>
												<a href="javascript:addWithdraw(0);"><span class="btn1_1">정정</span></a>
											</td>
										</tr>                                                            
									</table>                
								</div>
								<div class="con_box20">
									<div class="btn_wrap_center">
										<ul>
											<li><a href="javascript:WithdrawProc();"><span class="btn3_1">출금신청하기</span></a></li>
										</ul>
									</div>
								</div> 
							</div>
							`;
				}

				else if(pid == "letterList"){
					data=`<div class="title1">
								쪽지목록
							</div>
							<div class="contents_in">
								<div class="con_box10">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td width="10%" class="list_title1">번호</td>
										<td class="list_title1">제목</td>
										<td width="20%" class="list_title1">작성일</td>
										<td width="10%" class="list_title1">상태</td>
										<td width="10%" class="list_title1">기능</td>
									</tr>
										</table>
							</div>`;
				}
				else if( pid == "inoutList" ){
					data=`<div class="title1">입출금내역</div>
							<div class="con_box10">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td width="10%" class="list_title1">번호</td>
										<td width="10%" class="list_title1">구분</td>
										<td width="10%" class="list_title1">신청일시</td>
										<td width="10%" class="list_title1">처리일시</td>
										<td width="10%" class="list_title1">충/환전금액</td>
										<td width="10%" class="list_title1">처리현황</td>
									</tr> 
										</table>
								
							</div> `;
				}
				else if( pid == "chatList" ){
					data= `<div class="title1">
								고객센터
							</div>
							<div class="contents_in">
								<div class="con_box10">
									<table width="100%" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td width="10%" class="list_title1">번호</td>
											<td class="list_title1">제목</td>
											<td width="20%" class="list_title1">문의날자</td>
											<td width="10%" class="list_title1">상태</td>
											<td width="10%" class="list_title1">기능</td>
										</tr>
												</table>
								</div>
								<div class="con_box20">
									<div class="btn_wrap_center">
										<ul>
											<li><a href="#" onclick="tabActionProc('tab5','chatCreate');"><span class="btn2_1">문의작성</span></a></li>                                                                                    
										</ul>
									</div>
								</div>
							
							</div>`;
				}
				else if( pid == "noticeList" ){
					data= `<div class="title1">
								공지사항
							</div>
							<div class="contents_in">
								<div class="con_box10">
									<table width="100%" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td width="10%" class="list_title1"></td>
											<td class="list_title1">제목</td>
											<td width="20%" class="list_title1">작성일</td>
										</tr>
									
									</table>
								</div>
								
							</div>`;
				}

				$(".sk_tab_con_01").html(data);
			}
		</script>

@if ($notice != null)
		<div class="pop02_popup1 draggable02" id="divpopup2" style="position: absolute; top: 250px; left: 100px; z-index: 1000;display:none">
            <div class="pop02_popup_wrap">
                <div class="pop02_popup_btn_wrap">
                    <ul>
                        <li><a href="#"><span class="pop02_popup_btn" onclick="closePopup('2');">오늘 하루 이 창을 열지 않음</span></a></li>
                        <li><a href="#"><span class="pop02_popup_btn" onclick="closePopup1('2');">닫기 X</span></a></li>            
                    </ul>
                </div>
                <div class="pop02_popup_box">
                    <div class="pop02_popup_text" style="padding:30px;width:500px">
                        <span class="pop02_popup_font1" style="border-bottom:2px solid #fff;margin-bottom:15px">★공지사항★</span>
                        <span class="pop02_popup_font2">
								<?php echo $notice->content ?>
                        </span> 
                    </div>
                </div>
            </div>
        </div>
@endif
		<style type="text/css">
            .pop02_popup1 {position:absolute; z-index:1000000000;}
            .pop02_popup2 {position:absolute; z-index:1000000000;}
            .pop02_popup_wrap {float:left;z-index:1000000000;}
            .pop02_popup_btn_wrap {float:right;z-index:1000000000;}
            .pop02_popup_btn_wrap ul li {float:left; margin:0 0 0 5px;}
            .pop02_popup_btn {float:right; background:#6421d4; min-width:60px; height:36px; line-height:40px; padding:0 15px 0 15px; text-align:center; display:inline-block; font-family:nanumgothic, sans-serif; color:#fff; font-size:12px; font-weight:600;}
            .pop02_popup_box {float:left; border:5px solid #6421d4;clear:both;z-index:1000000000;background:#000 left top no-repeat; background-size:cover;}
            .pop02_popup_text {float:left; width:100%;z-index:1000000000;}
            .pop02_popup_font1 {float:left; width:100%; font-family:'nanumsquare', sans-serif; font-size:22px; letter-spacing:-1px; font-weight:700; color:#ffffff; line-height:40px;}
            .pop02_popup_font2 {float:left; width:100%; font-family:'nanumgothic', sans-serif; font-size:16px; letter-spacing:-1px; font-weight:400; color:#ffffff; line-height:28px;}
			#xImag {width:150px;height:120px}
			.slot_txt_style {color:#fff;text-align:center;}
        </style>
	</body>

<!-- Mirrored from di-1010.com/login by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 14 Nov 2021 02:01:42 GMT -->
</html>