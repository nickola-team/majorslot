<html lang="ko">
<head>
<title>KHAN-1</title>
        <!-- Required meta tags -->
        <meta charset="utf-8"><link href="https://fonts.googleapis.com/css?family=Raleway:900&amp;display=swap" rel="stylesheet">
	    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
	    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	    
        <script src="/frontend/k1slot/js/jquery-ui-1.9.2.custom.min.js"></script>        
        <script src="/frontend/k1slot/js/t_user_common.js"></script>
	    <script src="/frontend/k1slot/js/jquery.ui.touch-punch.min.js"></script>
        <script src="/frontend/k1slot/js/loading-bar.js"></script>
        <script src="/frontend/k1slot/js/moment.min.js"></script>
        <script type="text/javascript" src="/js/common_new.js?v=20220101"></script>
        <script src="/frontend/k1slot/js/pwbAuto.js?v=20210302"></script>
        <script src="/frontend/k1slot/js/pagination.min.js"></script>
        <script src="/frontend/k1slot/js/TINYbox.js"></script>
        <script src="/frontend/k1slot/js/game.js"></script>
		<script type="text/javascript" src="/frontend/k1slot/js/common.js"></script>
	
	    <link rel="stylesheet" href="/frontend/k1slot/css/loading-bar.css">
		<link rel="stylesheet" type="text/css" href="/frontend/k1slot/css/jquery-ui-1.9.2.custom.min.css">
		<link rel="stylesheet" href="/frontend/k1slot/css/bootstrap-reboot.css">
		<link rel="stylesheet" href="/frontend/k1slot/css/style2.css?ver=20220101" type="text/css">
		<link rel="stylesheet" href="/frontend/k1slot/css/model.css" type="text/css">
		<link rel="stylesheet" type="text/css" href="/frontend/k1slot/css/pagination.css">
        <link rel="stylesheet" type="text/css" href="/frontend/k1slot/css/subpage.css">

		<script type="text/javascript">
		// 자바스크립트에서 사용하는 전역변수 선언
		var g4_path      = ".";
		var g4_bbs       = "bbs";
		var g4_bbs_img   = "img";
		var g4_url       = "http://k1k1slot.com";
		var g4_is_member = "1";
		var g4_is_admin  = "";
		var g4_bo_table  = "";
		var g4_sca       = "";
		var g4_charset   = "utf-8";
		var g4_cookie_domain = "";
		var g4_is_gecko  = navigator.userAgent.toLowerCase().indexOf("gecko") != -1;
		var g4_is_ie     = navigator.userAgent.toLowerCase().indexOf("msie") != -1;
				</script>

	<style>
    .main_best {float:left; width:100%; position:relative;}
    .main_best ul li {float:left; margin:0 10px 20px 10px;}
    .main_best>ul {
        overflow: auto;
    }

    .main_best>ul>li {
        width:  calc(100vw / 7.0);
        height: calc(100vw / 7.0);
        min-width:275px;
        min-height:275px;
        margin: 10px;
        float: left;
        overflow: auto;
        border: 1px solid rgba(255, 255, 255, 0.1);
        position: relative;
        top: 0px;
        background: rgba(255, 255, 255, 0.5);
        box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.3);
        transition: all ease 0.2s;
    }

    .main_best>ul>li:hover {
        top: -5px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        background: rgba(255, 255, 255, 0.9);
        box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.6);
    }

    .main_best>ul>li>a {
        position: absolute;
        left: 10px;
        bottom: 10px;
        font-size: 16px;
    }

    .main_best>ul>li>a strong {
        color: #fff;
        font-family: "YDgothic360";
        font-weight: normal;
        text-shadow: 1px 1px 2px #000;
        transition: all ease 0.2s;
    }

    .main_best>ul>li:hover>a strong {
        color: #000;
        text-shadow: 0px 0px 0px #fff;
    }

    .main_best>ul>li>a span {
        font-size: 14px;
        color: #000;
        transition: all ease 0.2s;
    }

    .main_best>ul>li:hover>a span {
        color: #bc4747;
    }

    .main_best>ul>li>ul {
        position: absolute;
        top: 10px;
        left: 10px;
        right: 10px;
        bottom: 40px;
        overflow: hidden;
        transition: all ease 0.4s;
    }

    .main_best>ul>li>ul:before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        z-index: 90;
        /* background: url("../images/main/gamezone-bg8e20.png?200617"); */
        filter: blur(0px);
        transition: all ease 0.4s;
    }

    .main_best>ul>li>ul {
        position: absolute;
        top: 10px;
        left: 10px;
        right: 10px;
        bottom: 40px;
        overflow: hidden;
        transition: all ease 0.4s;
    }

	.gl-inner {
        width: 100%;
        height : 700px;
        max-height:700px;
        float: left;
        display:flex;
        flex-wrap:wrap;
        align-items:flex-start;
        justify-content:flex-start;
        position: relative;
        overflow-y:scroll;
        -ms-overflow-style: none; /* IE and Edge */
        scrollbar-width: none; /* Firefox */
    }   
    .gl-inner::-webkit-scrollbar {
        display: none; /* Chrome, Safari, Opera*/
    }
    .game-btn{
        width:17%;
        display:flex;
        align-items:center;
        justify-content:center;
        position:relative;
        margin:10px 1.50%; 
        border:solid 1px #000;
        transition:0.3s;
    }
    .game-btn:hover{
        border:solid 1px #1cbfba;
    }
    .game-btn:before{
        content:'';
        position:absolute;
        top:-2px;
        left:-2px;
        right:-2px;
        bottom:-2px;
        background-image:linear-gradient(#089eaf,#05b866);
        transform:skew(2deg,2deg);
        box-shadow:0 0 10px rgba(8,158,175,0.4);
        transition:0.3s;
        z-index:-1;
    }
    .game-btn:hover:before{
        transform:skew(0deg,0deg);
        box-shadow:0 0 0 rgba(8,158,175,0);
    }
    .game-btn .inner{
        width:100%;
        background-color:rgba(0,0,0,0.9);
        position:relative;
        z-index:1; 
        padding:10px;
        text-align:center;
        transition:0.3s;
    }
    .game-btn:hover .inner{
        background-color:rgb(3, 33, 35);
    }
    .game-btn .img-cont{
        width:100%;
        float:left;
        position:relative;
        display:flex;
        align-items:center;
        justify-content:center;
    }
    .game-btn .img-cont .main-img{
        width:100%;
        transition:0.3s;
        opacity:1;
    }
    .game-btn .info-cont{
        width:100%;
        float:left;
        margin-top:10px;
    }
    .game-btn .info-cont .game-name{
        color:#fff;
        font-size:14px;
    }
    .game-btn .start-btn{
        width:120px;
        height:35px;
        background-color:rgba(255,255,255,0);
        border:solid 1px #969696;
        margin-top:10px;
        color:#ccc;
        font-size:12px;
        transition:0.3s;
    }
    .game-btn .start-btn:hover{
        background-color:rgba(255,255,255,0.1);
    }
	</style>
</head>


<script type="text/javascript">
<!--

 function event2() {
 alert("준비중입니다.");
 }

 function event3() {
 alert("점검중입니다.");
 }

 function event4() {
 alert("미확인 쪽지를 확인바랍니다.");
 }

 function event5() {
 alert("게임은 유져만 가능합니다.");
 }

 function event1() {
 alert("로그인후 이용 가능합니다.");
 }

// -->
</script>

<body>
<div class="top_menu">
	<div class="line01">
		<div class="con">
			<div class="l">
				<img src="/frontend/k1slot/images/icon_top01.png"> <span id="lbNick">{{auth()->user()->username}}</span> 님 환영합니다.
				<iframe name="go2" width="0" height="0" src="price_proc.php" frameborder="0"></iframe>
			</div>
			<div class="logo"><a href="../"><img src="/frontend/k1slot/images/logo.png" height="100px"></a></div>
			<div class="r">
				<a href="javascript:;" onclick="alert('매장에 문의하세요.');">마이페이지</a>
				<a href="javascript:;" onclick="location.href='/';">쪽지함</a>   
				<a href="javascript:;" onclick="location.href='/logout';">로그아웃</a>
							</div>
		</div>
	</div>
	<div class="line02">
		<div class="con">
			<div class="btn l btn_new" onclick="location.href='/';">게임</div>
			<div class="btn l" onclick="location.href='/';">공지사항</div>
						<div class="btn l" onclick="location.href='/';">고객센터</div>
						<div class="btn l" onclick="depositWindow();">충전</div>
			<div class="btn l" onclick="withdrawWindow();">환전</div>
		</div>
	</div>
	<div class="navnotice">
		<marquee>
			<font color="white" style="font-size:larger">※신규회원 배팅규정 필독후 이용해주시기를 바라며, 매충전시 고객센터 계좌문의후 입금을 해주시기를 바랍니다※ 필독 잘 확인 하세요</font>
		</marquee>
	</div>
</div>
<div class="body">
	<div class="info">
		<div class="round">&nbsp;</div>
		<!--div class="countdown">&nbsp;</!--div-->
			<div class="countdown" style="padding:7.5px 0;"><a class="btn_del_all" href="javascript:window.location.reload();" style="margin-right:10px;width:136px;background:#085513;">새로고침</a></div>
			<div class="point" style="cursor:pointer;">
				<div class="wrap">
					<div class="icon">P</div>
					<div class="l">보유금액</div>
					<div class="r current_user_point"><span id="lbUserPoint">{{number_format(auth()->user()->balance)}}</span></div>
				</div>
			</div>
			<div class="money" style="cursor:pointer;">
				<div class="wrap">
				<div class="icon">￦</div>
				<div class="l">보너스금</div>
				<div class="r current_user_money"><span id="lbGamePoint">{{number_format(auth()->user()->deal_balance)}}</span></div>
			</div>
		</div>
	</div>            
    <div id="BodyContent_dvProvider" class="main_best">
        <ul class="slot-games row">
		<?php $catcount = 0; ?>
		@if ($categories && count($categories))
			@foreach($categories AS $index=>$category)
				@if($category->title != "Hot" && $category->title != "Card" && $category->title != "Bingo" && $category->title != "Roulette" 
				&& $category->title != "Novomatic" && $category->title != "Keno" && $category->title != "Vision" && $category->title != "Wazdan" && $category->title != "New" 
				&& $category->title != "Skywind" )
				<li onclick="goSlot('{{$category->trans->trans_title}}','{{$category->href}}')"><a href="javascript:;"><strong> {{$category->title}} </strong><span>{{$category->trans->trans_title}}</span></a>
				<ul style="background:url('/frontend/k1slot/images/slot/{{ $category->title.'.jpg' }}') #111316 no-repeat; background-size:cover;"></ul></li>
				<?php $catcount = $catcount +1; ?>
				@endif
			@endforeach
			<?php
				$comingSoon = (intval(($catcount-1)/4) + 1 ) * 4 - $catcount;
			?>
			@for ($i=0;$i<$comingSoon;$i++)
			<li onclick="event2();"><a href="javascript:;"><strong> ComingSoon </strong><span>준비중</span></a><ul style="background:url(/frontend/k1slot/images/slot/coming_soon.jpg) #111316 no-repeat; background-size:cover;"></ul></li>
			@endfor
		@endif
            
        </ul>
	</div>
</div>

<div class="footer">
	<table>
		<tr>
			<td class="l" style="color:#fff">
				<span class="name">KHAN-1</span><br>
				all right reserved.
			</td>
			<td class="r">
				<img src="/frontend/k1slot/images/icon_footer01.png">
				<img src="/frontend/k1slot/images/icon_footer02.png">
			</td>
		</tr>
	</table>
</div>
</body>
</html>

<script language="Javascript"> 
<!-- 
function setCookie( name, value, expiredays ) { 
    var todayDate = new Date(); 
        todayDate.setDate( todayDate.getDate() + expiredays ); 
        document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";" 
    }
function closeWin( popup, num, closeflag ) {
	if(closeflag == true) {
		setCookie( "divpopup" + num, "check" , 1);
	}
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
 
//-->  
</script> 

<!-- POPUP --> 
@if ($noticelist != null && count($noticelist) > 0)
@foreach ($noticelist as $ntc)
<div id="pop{{$ntc->id}}" style="position:absolute; left:{{50+$loop->index*500}}px; top:50px; z-index:9999; background-color: #000000; border:1px solid white; padding:20px 10px 0px 10px; width:500px;">
	<div style="position:absolute;left:15px;bottom:10px;background-color:transparent;color:white;">
		<input type="checkbox" name="notice{{$ntc->id}}" id="notice{{$ntc->id}}" value="" style="width:14px;"><label for="notice{{$ntc->id}}" style="font-size:16px;">오늘 하루 열지 않음</label>
		<a href="#" onclick="closeWin(document.getElementById('pop{{$ntc->id}}'), {{$ntc->id}}, document.getElementById('notice{{$ntc->id}}').checked);" style="color:white;margin-left:12px;font-size:16px;">[ 닫기 ]</a>
	</div>
	<div><?php echo $ntc->content ?><p><br></p></div>
</div>
<script type='text/javascript'>
    if ( getCookie( "divpopup{{$ntc->id}}" ) == "check" ) {
      closeWin2(document.getElementById('pop{{$ntc->id}}'));
    }
</script>
@endforeach
@endif