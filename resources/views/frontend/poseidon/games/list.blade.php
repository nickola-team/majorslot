@extends('frontend.poseidon.layouts.app')
@section('page-title', $title)

@section('content')

@if (session('status'))
    <script>
        alert('{{ session('status') }}');
    </script>
@endif



<script type='text/javascript'>

	@if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
		var loginYN='Y';
		var currentBalance = {{ Auth::user()->balance }};
		var userName = "{{ Auth::user()->username }}";
		var accountName = "{{ Auth::user()->recommender }}";
		var bankName = "{{ Auth::user()->bank_name }}";
		var account_no = "{{ Auth::user()->account_no }}";
		<?php
			$user = auth()->user();
			while ($user && !$user->isInoutPartner())
			{
				$user = $user->referral;
			}
			$telegram = '';
			if ($user)
			{
				$telegram = $user->address;
			}
		?>
		var telegram_id = "{{$telegram}}";
	@else
		var loginYN='N';
		var currentBalance = 0;
		var userName = "";
		var accountName = "";
	@endif

	var HTTP_HOST='poseidon777.com';

	var MEM_TST_YN='';
</script>

<script>
$(document).ready(function(){
	
		// reGetMoney();
		// setInterval(keepSession, 10000);
		// updateMemo();
		// gameJoin();
		// chkLogin();
	

	$( "#password" ).on( "keyup",function( e ) {
		if( e.keyCode==13 ) {
			loginSubmit(mainLogin_form);
		}
		e.preventDefault(  );
		return false;
	});

	// swal({
	// 	title: 'Error',
	// 	text: '점검중입니다.\n불편을 끼쳐 죄송합니다.',
	// 	type: 'error',
	// 	showCancelButton: false,
	// 	showConfirmButton: false
	// });
});
</script>



<!-- 사운드 관련 -->
<div style="display:none;" id="alarmSound">
</div>

<div style="display:none;">
	<iframe src="#" target="iframe" name="iframe" id="iframe" width=100% height=100></iframe>
	<iframe src="#" target="iframe2" name="iframe2" id="iframe2" width=100% height=100></iframe>
</div>


<SCRIPT language="JavaScript">

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

</SCRIPT>



@if (count($noticelist) > 0)
@foreach ($noticelist as $notice)
<div id="pop{{$notice->id}}" style="position:absolute; left:{{50+$loop->index*500}}px; top:50px; z-index:9999; background-color: #000000; border:1px solid white; padding:20px 10px 0px 10px">
	<div style="position:absolute;left:15px;bottom:10px;background-color:transparent;color:white;">
		<input type="checkbox" name="notice{{$notice->id}}" id="notice{{$notice->id}}" value="" style="width:14px;"><label for="notice{{$notice->id}}" style="font-size:16px;">오늘 하루 열지 않음</label>
		<a href="#" onclick="closeWin(document.getElementById('pop{{$notice->id}}'), {{$notice->id}}, document.getElementById('notice{{$notice->id}}').checked);" style="color:white;margin-left:12px;font-size:16px;">[ 닫기 ]</a>
	</div>
	<div><?php echo $notice->content ?><p><br></p></div>
</div>
@endforeach
@endif
<script type='text/javascript'>
@if (count($noticelist) > 0)
  @foreach ($noticelist as $notice)
    if ( getCookie( "divpopup{{$notice->id}}" ) == "check" ) {
      closeWin2(document.getElementById('pop{{$notice->id}}'));
    }
  @endforeach
@endif
</script>

<div class="main_slide">
	<div class="video_bg">
		<video loop autoplay muted preload="auto" id="login_video_b">
			<source src="/frontend/poseidon/img/bg_movie2.mov" type="video/mp4">
			Your browser does not support the video tag.
		</video>
	</div>
	<div class="slide_wrap">
		<div class="slider">
			<ul class="bxslider">
				<li><img src="/frontend/poseidon/img/slide_img0.png?5" /></li>
				<li><img src="/frontend/poseidon/img/slide_img1.png?5" /></li>
			</ul>
		</div>
	</div>
</div>
<div class="main_money">
	<!-- <ul>
		<li class="in" onclick="goDeposit();"></li>
		<li class="out" onclick="goWithdraw();"></li>
		<li class="cs_call">
			<div class="" style="margin-bottom:15px;">24시간 고객센터</div>
			<div class="number">고객 문의 주세요.</div>
		</li>
	</ul> -->
</div>

<div class="main_game">
	<div class="gamebtn_wrap slot">

		<div class="slot-container">

		@if ($categories && count($categories))
			@foreach($categories AS $index=>$category)
				@if(!(isset ($errors) && count($errors) > 0) && !Session::get('success', false))
					<a href="javascript:void(0);" class="slot-btn" onclick="goSlot('{{ $category->title }}', '{{ $category->href }}', 0)">
						<div class="inner-cont">
							<div class="main-cont">
								<img class="main-img" src="/frontend/poseidon/img/slot/{{ $category->title.'.jpg' }}">
								<div class="hover">
									<div class="center">
										<button class="play-btn">게임시작</button>
										<img src="/frontend/poseidon/img/slot-icon/{{ $category->title.'.png' }}">
									</div>
								</div>
								<div class="logo-cont">
									<img src="/frontend/poseidon/img/slot-icon/{{ $category->title.'.png' }}">
								</div>
							</div>
						</div>
						<p class="name">
						@if ($category->trans)
							{{ $category->trans->trans_title }}
						@else
							{{ $category->title }}
						@endif
						</p>
					</a>
				@endif
			@endforeach
			<?php
            $comingSoon = (intval(count($categories)/6) + 1 ) * 6 - count($categories);
			?>
			@for ($i=0;$i<$comingSoon;$i++)
				<a href="javascript:void(0);" class="slot-btn" onclick="alert('준비중입니다.');">
						<div class="inner-cont">
							<div class="main-cont">
								<img class="main-img" src="/frontend/poseidon/img/slot/coming_soon.jpg">
								<div class="hover">
									<div class="center">
										<img src="/frontend/poseidon/img/slot-icon/coming_soon.png">
									</div>
								</div>
								<div class="logo-cont">
									<img src="/frontend/poseidon/img/slot-icon/coming_soon.png">
								</div>
							</div>
						</div>
						<p class="name">
						준비중
						</p>
					</a>
			@endfor
		@endif

		</div>
	</div>
</div>

	<div class="main_board">
		<ul>
			<li class="cs_center">
				<dl>
					<dd class="txt"><img src="/frontend/poseidon/img/logo_layer.png?5" /> 고객센터 안내<span>언제 어디서나 무엇이든 물어보세요!</span></dd>
					<dd class="txt" style="float:right; margin-right:15px;"><a href="#">고객센터 문의 바로가기 <i class="fa fa-arrow-alt-circle-right"></i></a></dd>
					<!--dd class="msg call"></dd>
					<dd class="msg kakao"></dd>
					<dd class="msg skype"></dd-->
				</dl>
			</li>
			<li class="board notice">
				<div class="morebtn" onclick="goBoardList('notice');"></div>
				<ul>
				
				</ul>
			</li>
			<li class="board out_now">
				<div>
					<ul>
				
						<li>
							<div class="user">
							***012
							</div>
							<div class="amount">99,000원</div>
							<div class="date">00:17</div>
						</li>
				
						<li>
							<div class="user">
							***a17
							</div>
							<div class="amount">200,000원</div>
							<div class="date">00:17</div>
						</li>
				
						<li>
							<div class="user">
							****x04
							</div>
							<div class="amount">115,000원</div>
							<div class="date">00:15</div>
						</li>
				
						<li>
							<div class="user">
							**v01
							</div>
							<div class="amount">700,000원</div>
							<div class="date">00:14</div>
						</li>
				
						<li>
							<div class="user">
							**aa9
							</div>
							<div class="amount">100,000원</div>
							<div class="date">00:12</div>
						</li>
				
						<li>
							<div class="user">
							**aa9
							</div>
							<div class="amount">100,000원</div>
							<div class="date">00:11</div>
						</li>
				
						<li>
							<div class="user">
							**aa5
							</div>
							<div class="amount">160,000원</div>
							<div class="date">00:11</div>
						</li>
				
						<li>
							<div class="user">
							**h03
							</div>
							<div class="amount">300,000원</div>
							<div class="date">00:08</div>
						</li>
				
						<li>
							<div class="user">
							***r20
							</div>
							<div class="amount">800,000원</div>
							<div class="date">00:08</div>
						</li>
				
						<li>
							<div class="user">
							***100
							</div>
							<div class="amount">518,000원</div>
							<div class="date">00:08</div>
						</li>
				
						<li>
							<div class="user">
							**aa9
							</div>
							<div class="amount">100,000원</div>
							<div class="date">00:07</div>
						</li>
				
						<li>
							<div class="user">
							***r02
							</div>
							<div class="amount">600,000원</div>
							<div class="date">00:06</div>
						</li>
				
						<li>
							<div class="user">
							***c19
							</div>
							<div class="amount">70,000원</div>
							<div class="date">00:03</div>
						</li>
				
						<li>
							<div class="user">
							***c20
							</div>
							<div class="amount">60,000원</div>
							<div class="date">00:01</div>
						</li>
				
						<li>
							<div class="user">
							***010
							</div>
							<div class="amount">800,000원</div>
							<div class="date">00:00</div>
						</li>
				
						<li>
							<div class="user">
							***d14
							</div>
							<div class="amount">700,000원</div>
							<div class="date">00:00</div>
						</li>
				
						<li>
							<div class="user">
							***012
							</div>
							<div class="amount">300,000원</div>
							<div class="date">23:59</div>
						</li>
				
						<li>
							<div class="user">
							**h47
							</div>
							<div class="amount">300,000원</div>
							<div class="date">23:55</div>
						</li>
				
						<li>
							<div class="user">
							**v07
							</div>
							<div class="amount">179,000원</div>
							<div class="date">23:55</div>
						</li>
				
						<li>
							<div class="user">
							****008
							</div>
							<div class="amount">800,000원</div>
							<div class="date">23:55</div>
						</li>
				
					</ul>
				</div>
			</li>
			<li class="board out_rank">
				<div>
					<ul>
				
						<li>
							<div class="rank">1위</div>
							<div class="user">
							*****666
							</div>
							<div class="amount">26,009,000원</div>
							<div class="date">18:05</div>
						</li>
				
						<li>
							<div class="rank">2위</div>
							<div class="user">
							**sd2
							</div>
							<div class="amount">15,000,000원</div>
							<div class="date">12:03</div>
						</li>
				
						<li>
							<div class="rank">3위</div>
							<div class="user">
							**i06
							</div>
							<div class="amount">12,091,000원</div>
							<div class="date">23:35</div>
						</li>
				
						<li>
							<div class="rank">4위</div>
							<div class="user">
							***a03
							</div>
							<div class="amount">10,000,000원</div>
							<div class="date">23:57</div>
						</li>
				
						<li>
							<div class="rank">5위</div>
							<div class="user">
							**jj2
							</div>
							<div class="amount">10,000,000원</div>
							<div class="date">21:50</div>
						</li>
				
						<li>
							<div class="rank">6위</div>
							<div class="user">
							**sd7
							</div>
							<div class="amount">9,000,000원</div>
							<div class="date">00:27</div>
						</li>
				
						<li>
							<div class="rank">7위</div>
							<div class="user">
							**sd7
							</div>
							<div class="amount">9,000,000원</div>
							<div class="date">00:28</div>
						</li>
				
						<li>
							<div class="rank">8위</div>
							<div class="user">
							**i07
							</div>
							<div class="amount">8,990,000원</div>
							<div class="date">16:36</div>
						</li>
				
						<li>
							<div class="rank">9위</div>
							<div class="user">
							**jj3
							</div>
							<div class="amount">8,092,000원</div>
							<div class="date">16:17</div>
						</li>
				
						<li>
							<div class="rank">10위</div>
							<div class="user">
							**jj4
							</div>
							<div class="amount">8,051,000원</div>
							<div class="date">21:15</div>
						</li>
				
						<li>
							<div class="rank">11위</div>
							<div class="user">
							**jj5
							</div>
							<div class="amount">8,017,000원</div>
							<div class="date">23:48</div>
						</li>
				
						<li>
							<div class="rank">12위</div>
							<div class="user">
							**v08
							</div>
							<div class="amount">7,479,000원</div>
							<div class="date">18:36</div>
						</li>
				
						<li>
							<div class="rank">13위</div>
							<div class="user">
							*****r19
							</div>
							<div class="amount">7,304,000원</div>
							<div class="date">16:19</div>
						</li>
				
						<li>
							<div class="rank">14위</div>
							<div class="user">
							**i06
							</div>
							<div class="amount">7,200,000원</div>
							<div class="date">02:15</div>
						</li>
				
						<li>
							<div class="rank">15위</div>
							<div class="user">
							***100
							</div>
							<div class="amount">7,200,000원</div>
							<div class="date">06:45</div>
						</li>
				
						<li>
							<div class="rank">16위</div>
							<div class="user">
							**jj3
							</div>
							<div class="amount">7,170,000원</div>
							<div class="date">00:18</div>
						</li>
				
						<li>
							<div class="rank">17위</div>
							<div class="user">
							***v03
							</div>
							<div class="amount">7,000,000원</div>
							<div class="date">14:24</div>
						</li>
				
						<li>
							<div class="rank">18위</div>
							<div class="user">
							**i06
							</div>
							<div class="amount">7,000,000원</div>
							<div class="date">16:00</div>
						</li>
				
						<li>
							<div class="rank">19위</div>
							<div class="user">
							*****345
							</div>
							<div class="amount">6,899,000원</div>
							<div class="date">04:49</div>
						</li>
				
						<li>
							<div class="rank">20위</div>
							<div class="user">
							**jj5
							</div>
							<div class="amount">6,865,000원</div>
							<div class="date">00:44</div>
						</li>
				
					</ul>
				</div>
			</li>
		</ul>
	</div>


@stop