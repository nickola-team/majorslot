
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>@yield('page-title')</title>
    <meta name="viewport" content="width=device-width">
    <link rel="icon" href="/frontend/thezone/img/logo_layer.png" >

	<script type="text/javascript" src="/frontend/thezone/js/jquery-1.12.3.min.js"></script>
	<script type="text/javascript" src="/frontend/thezone/js/jquery-ui.js"></script>
	<script type="text/javascript" src="/frontend/thezone/js/TINYbox.js"></script>
	<script type="text/javascript" src="/frontend/thezone/js/jquery.vticker.min.js"></script>
	<script type="text/javascript" src="/frontend/thezone/js/jquery.bxslider.min.js"></script>

	<link rel="stylesheet" type="text/css" href="/frontend/thezone/css/normalize.css" />
	<link rel="stylesheet" type="text/css" href="/frontend/thezone/css/main.css?ver=6038" />
	<link rel="stylesheet" type="text/css" href="/frontend/thezone/css/all.css">
	<link rel="stylesheet" type="text/css" href="/frontend/thezone/css/Style_sub.css" />

    <script type="text/javascript" src="/frontend/thezone/count/jquery.counter.js"></script>

	<script type='text/javascript' src="/frontend/thezone/js/game.js?ver=5210"></script>
	<script type='text/javascript' src='/frontend/thezone/js/menu.js?ver=3007'></script>
	<script type='text/javascript' src='/frontend/thezone/js/main.js?ver=6959'></script>
	<script type='text/javascript' src='/frontend/thezone/js/fund.js?ver=5645'></script>
	<script type='text/javascript' src='/frontend/thezone/js/common.js?ver=7896'></script>


	<!-- Sweet-Alert  -->
	<script type="text/javascript" src="/frontend/thezone/js/sweetalert/sweetalert.min.js"></script>
	<script type="text/javascript" src="/frontend/thezone/js/sweetalert/jquery.sweet-alert.custom.js"></script>
	<link rel="stylesheet" href="/frontend/thezone/js/sweetalert/sweetalert.css" type="text/css">
</head>


<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" oncontextmenu="return false" ondragstart="return false" onselectstart="return false">

<header>
	@if (empty($logo))
	<?php $logo = 'punch'; ?>
	@endif
	<ul class="nav">
		<li class="logo" onclick="goHome()"><img src="/frontend/{{ $logo }}/img/logo.png?5" alt="" /></li>
		<li class="deposit" onclick="goDeposit()"><div></div></li>
		<li class="withdraw" onclick="goWithdraw()"><div></div></li>
		<li class="aboutgame" onclick="goCasino()"><div></div></li>
		<li class="event" onclick="goBoardList('event');"><div></div></li>
		<li class="notice" onclick="goBoardList('notice');"><div></div></li>
	</ul>

	<input type="hidden" value="<?= csrf_token() ?>" name="_token" id="_token">
	@if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))	
		<div class="loggedbox">
			<div class="player-name">
				<div class="total-balance">
					<strong class="text-uppercase">전체 잔액</strong>
					<strong class="text-uppercase pull-right">&nbsp; 원</strong>
					<strong class="text-uppercase pull-right" id="ximoney">{{ number_format(Auth::user()->balance,2) }}</strong>
				</div>
				
				 <div class="get-balance" onclick="getBalance();"><i class="fa fa-recycle"></i></div>
				
				<button class="btn-loginbox btn-yellow" onclick="goMypage();">
						내 계정
                      <sup class="mess-count" style="animation: letter_anim 0s linear infinite;">{{$unreadmsg}}</sup>
				</button>
			</div>
			<div class="player-money">
				<div class="total-balance">
					<strong class="text-uppercase">보너스 수익</strong>
					<strong class="text-uppercase pull-right">&nbsp; 원</strong>
					<strong class="text-uppercase pull-right" id="xideal">{{ number_format(Auth::user()->deal_balance,2) }}</strong>
					
				</div>
				<div class="get-balance" onclick="goDealOut();"><i class="fa fa-at"></i></div>
				<button class="btn-loginbox btn-gray" onclick="goLogout();">로그아웃</button>
			</div>
		</div>
	@else
		<div class="loginbox">
			<form name="mainLogin_form" id="mainLogin_form" method="post">
				<input type="hidden" name="method" value="LOGIN" />
				<input type="text" placeholder="아이디" name="userid" id="userid" onfocus="clearField(this);" onblur="checkField(this);" style="IME-MODE:inactive" onkeyup="" maxlength="12" hname="아이디" Req />
				<input type="password"  placeholder="비밀번호" name="password" id="password" onfocus="clearField_pass(this);" onblur="checkField(this);" style="IME-MODE:inactive" maxlength="12" hname="패스워드" Req mins="6" maxs="12" />
				<button type="submit" class="btn-loginbox btn-gray" onclick="loginSubmit(mainLogin_form);">로그인</button>
				<button type="button" class="btn-loginbox btn-yellow" onclick="goJoin();">회원가입</button>
			</form>
		</div>
	@endif
</header>

@yield('content')

<footer>
		<ul>
			<li>
				<dt>CASINO INFO</dt>
				<dd onclick="goCasino()">카지노 소개</dd>
			</li>
			<li>
				<dt>HOW TO PLAY</dt>
				<dd onclick="goRule('br')">바카라</dd>
				<dd onclick="goRule('bj')">블랙잭</dd>
				<dd onclick="goRule('rl')">룰렛</dd>
				<dd onclick="goRule('sb')">식보</dd>
				<dd onclick="goRule('dt')">드래곤타이거</dd>
				<dd onclick="goRule('cf')">캐리비안스터드포커</dd>
				<dd onclick="goRule('tf')">트리플페이스카드</dd>
			</li>
			<li>
				<dt>MONEY MENU</dt>
				<dd onclick="goDeposit()">입금신청</dd>
				<dd onclick="goWithdraw()">출금신청</dd>
				<dd onclick="goMove()">머니이동신청</dd>
				<dd onclick="goBoardList('event')">이벤트신청</dd>
			</li>
			<li>
				<dt>CUSTOMER CENTER</dt>
				<dd onclick="goBoardList('notice')">공지사항</dd>
				<dd onclick="goRemote()">오류해결방법</dd>
				<dd onclick="goBoardList('faq')">자주묻는질문</dd>
				<dd onclick="goBoardList('event')">이벤트안내</dd>
				<dd onclick="goPartner()">고객센터</dd>
			</li>
			<li>
				<dt>MEMBER MENU</dt>
		
				<dd onclick="goMypage()">MyPage</dd>
				<dd onclick="goLogout()">로그아웃</dd>
		
			</li>
		</ul>
		<div><img src="/frontend/{{ $logo }}/img/logo.png?5"/></div>
		<font>Copyright&copy; 펀치. ALL RIGHT RESERVED</font>
	</footer>

@yield('popup')

</body>
</html>