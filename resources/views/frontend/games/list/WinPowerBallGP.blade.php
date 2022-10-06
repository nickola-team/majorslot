<!doctype html>
<html lang="ko">
<head>
<base href='/games/WinPowerBallGP/'>
<meta charset="utf-8">
<meta http-equiv="imagetoolbar" content="no">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>{{ $game->title }}</title>
	
<link href="//fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="theme/basic/skin/board/powerball/style.css?v=20220825">
<link rel="stylesheet" type="text/css" href="//use.fontawesome.com/releases/v5.0.12/css/all.css" />
		<link rel="stylesheet" href="css/bootstrap.min.css?ver=20220825">	
		<link rel="stylesheet" href="css/shop.style.css?ver=20220825">
		<link rel="stylesheet" href="assets/css/main.css?ver=20220825" />
			

<script src="js/jquery.min.js"></script>
<script src="js/jquery-migrate.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.parallax.js"></script>
<script src="js/owl.carousel.js"></script>
<script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="js/jquery.themepunch.tools.min.js"></script>
<script src="js/jquery.themepunch.revolution.min.js"></script>
<script src="js/custom.js"></script>
<script src="js/shop.app.js"></script>
<script src="js/owl-carousel.js"></script>
<script src="js/style-switcher.js"></script>
<script src="js/main.js?v=1"></script>
<script src="js/ion.sound.min.js"></script>
<script src="js/jquery.countdown.min.js"></script>
<script src="js/jquery.noty.packaged.min.js"></script>
<script src="js/jquery-migrate-1.4.1.min.js"></script>
<script src="js/jquery.menu.js?ver=20220825"></script>
<script src="js/common.js?ver=20220825"></script>
</head>
<body class="lang_ko_KR header-fixed" oncontextmenu="return false" ondragstart="return false" onselectstart="return false">
    <div id="content" style="margin:0px">
	<div class="inner2">
		<article class="box post post-excerpt">
    <style>
	#hd_pop {z-index:1000;position:relative;margin:0 auto;width:1200px;height:0}
	#hd_pop h2 {position:absolute;font-size:0;line-height:0;overflow:hidden}
	.hd_pops {position:absolute;border:1px solid #e9e9e9;background:#000;}
	.hd_pops img {max-width:100%}
	.hd_pops_con {}
	.hd_pops_footer {position:relative;padding:0;background:#000;color:#fff;text-align:left;border-top:1px solid #e9e9e9;font-size:1em;font-family: "굴림" ,"Gulim", "Apple SD Gothic Neo", "Malgun Gothic", sans-serif;}
	.hd_pops_footer:after {display:block;visibility:hidden;clear:both;content:""}
	.hd_pops_footer button {padding: 10px;border:0;color:#fff}
	.hd_pops_footer .hd_pops_reject {background:#c1262c;text-align:left}
	.hd_pops_footer .hd_pops_close {background:#c1262c;position:absolute;top:0;right:0}
	
	.project {
            position: relative;
            overflow: hidden;
			height:150px;
        }
	 video {object-fit: fill;}	       
</style>

			<div id="">
				<div class="container main-box">
				<div class="wrap col-game-box" style="">
					<div class="col-game">
						<div class="game-box">
							<div class="page-title">
                                <span class="title">WIN 파워볼 </span>
							</div>
							<div class="instants-game-div">
								<div class="broadcast-div" style="width:830px;height:560px;margin:0 auto;overflow:hidden;">
									<div style="position:absolute;top:80px;">
										<iframe id="iframevideo" src="/GamePlay/WinPowerBall?p=90" width="830" height="560" scrolling="no" frameborder="0" style="overflow:hidden;" allow="autoplay;"></iframe>
									</div>
								</div>
								<div class="page-title" style="width:830px;height:406px;margin:0 auto;">
									<button type="button" class="ifm-view" data-selected="" data-num="4">일반볼합계 언오</button>
									<button type="button" class="ifm-view" data-selected="" data-num="3">일반볼합계 홀짝</button>
									<button type="button" class="ifm-view" data-selected="" data-num="2">파워볼 언오</button>
									<button type="button" class="ifm-view" data-selected="selected" data-num="1">파워볼 홀짝</button>
									<iframe id="iframe_result" src="http://pow-9.com/powerball_odd.php" width="830" height="400" scrolling="yes" frameborder="0" style="overflow:hidden;"></iframe>
								</div>
							</div>
						</div>
					</div>
					<div class="col-cart">
						<div class="cart-box">
							<div id="cart">
								<div class="header" style="height:10px;">
								</div>
								<div class="game-view-loading">
									<div class="bar-shadow">
									</div>
									<div class="bar" style="">
									</div>
									<div class="bar-time"></div>
								</div>
								<div class="instants-box">
                                    <div class="game-list-1 display-none " id="game_list">
                                            <ul class="game-group">
                                                <div class="title">
                                                    <div class="date">====-==-==</div>
                                                    <div class="round">------회</div>
                                                </div>
                                            </ul>
                                    </div>
									<div class="game-list  ht330">
                                    <div style="width:100%;display:inline-flex;">
                                        <div style="width:35%;height:2px;background: linear-gradient(135deg, #000 0%,#555 23%,rgba(239,239,239,1) 49%,#666 73%,#000 100%);margin:10px auto"></div>
                                        <span style="margin:0 0 10px 0px;text-align:center;font-size:18px;">파워볼</span>
                                        <div style="width:35%;height:2px;background: linear-gradient(135deg, #000 0%,#555 23%,rgba(239,239,239,1) 49%,#666 73%,#000 100%);margin:10px auto"></div>
                                    </div>
										<ul>
											<li class="">
												<div class="team_sel_btn home blue" data-seltype="" data-rate="1.95" data-seltxt="파워볼(홀)" data-betid="1">
													<span class="icon">홀</span><span class="title">파워</span><br><span class="benefit">1.95</span>
												</div>
												<div class="team_sel_btn away red" data-seltype="" data-rate="1.95" data-seltxt="파워볼(짝)" data-betid="2">
													<span class="icon">짝</span><span class="title">파워</span><br><span class="benefit">1.95</span>
												</div>
												<div class="team_sel_btn home blue" data-seltype="" data-rate="1.95" data-seltxt="파워볼(언더)" data-betid="3">
													<span class="icon">언</span><span class="title">파워</span><br><span class="benefit">1.95</span>
												</div>
												<div class="team_sel_btn away red" data-seltype="" data-rate="1.95" data-seltxt="파워볼(오버)" data-betid="4">
													<span class="icon">오</span><span class="title">파워</span><br><span class="benefit">1.95</span>
												</div>
												<div class="close remain-time-con"></div>
											</li>
                                            <li class="">
												<div class="team_sel_btn mix green" data-seltype="" data-rate="4.0" data-seltxt="파워볼(홀/언더)" data-betid="5">
													<div class="topgroup"> <span class="title">파워</span><span class="benefit">4.0</span></div>
													<div class="icongroup"> <span class="icon2 bg_blue2">홀</span><span class="icon3 bg_blue2">언</span></div>
												</div>
												<div class="team_sel_btn mix green" data-seltype="" data-rate="3.0" data-seltxt="파워볼(홀/오버)" data-betid="6">
													<div class="topgroup"> <span class="title">파워</span><span class="benefit">3.0</span></div>
													<div class="icongroup"> <span class="icon2 bg_blue2">홀</span><span class="icon3 bg_red2">오</span></div>
												</div>
												<div class="team_sel_btn mix green" data-seltype="" data-rate="3.0" data-seltxt="파워볼(짝/언더)" data-betid="7">
													<div class="topgroup"> <span class="title">파워</span><span class="benefit">3.0</span></div>
													<div class="icongroup"> <span class="icon2 bg_red2">짝</span><span class="icon3 bg_blue2">언</span></div>
												</div>
												<div class="team_sel_btn mix green" data-seltype="" data-rate="4.0" data-seltxt="파워볼(짝/오버)" data-betid="8">
													<div class="topgroup"> <span class="title">파워</span><span class="benefit">4.0</span></div>
													<div class="icongroup"> <span class="icon2 bg_red2">짝</span><span class="icon3 bg_red2">오</span></div>
												</div>
												<div class="close remain-time-con"></div>
											</li>
											
                                    </ul>
                                    <div style="width:100%;display:inline-flex;">
                                        <div style="width:35%;height:2px;background: linear-gradient(135deg, #000 0%,#555 23%,rgba(239,239,239,1) 49%,#666 73%,#000 100%);margin:10px auto"></div>
                                        <span style="margin:0 0 10px 0px;text-align:center;font-size:18px;">일반볼</span>
                                        <div style="width:35%;height:2px;background: linear-gradient(135deg, #000 0%,#555 23%,rgba(239,239,239,1) 49%,#666 73%,#000 100%);margin:10px auto"></div>
                                    </div>
										<ul>
											<li class="">
												<div class="team_sel_btn home blue" data-seltype="" data-rate="1.95" data-seltxt="일반볼(홀)" data-betid="9">
													<span class="icon">홀</span><span class="title">일반</span><br><span class="benefit">1.95</span>
												</div>
												<div class="team_sel_btn away red" data-seltype="" data-rate="1.95" data-seltxt="일반볼(짝)" data-betid="10">
													<span class="icon">짝</span><span class="title">일반</span><br><span class="benefit">1.95</span>
												</div>
												<div class="team_sel_btn home blue" data-seltype="" data-rate="1.95" data-seltxt="일반볼(언더)" data-betid="11">
													<span class="icon">언</span><span class="title">일반</span><br><span class="benefit">1.95</span>
												</div>
												<div class="team_sel_btn away red" data-seltype="" data-rate="1.95" data-seltxt="일반볼(오버)" data-betid="12">
													<span class="icon">오</span><span class="title">일반</span><br><span class="benefit">1.95</span>
												</div>
												<div class="close remain-time-con"></div>
											</li>
											<li class="">
												<div class="team_sel_btn mix green" data-seltype="" data-rate="3.70" data-seltxt="일반볼(홀/언더)" data-betid="13">
													<div class="topgroup"> <span class="title">일반</span><span class="benefit">3.70</span></div>
													<div class="icongroup"> <span class="icon2 bg_blue2">홀</span><span class="icon3 bg_blue2">언</span></div>
												</div>
												<div class="team_sel_btn mix green" data-seltype="" data-rate="3.70" data-seltxt="일반볼(홀/오버)" data-betid="14">
													<div class="topgroup"> <span class="title">일반</span><span class="benefit">3.70</span></div>
													<div class="icongroup"> <span class="icon2 bg_blue2">홀</span><span class="icon3 bg_red2">오</span></div>
												</div>
												<div class="team_sel_btn mix green" data-seltype="" data-rate="3.70" data-seltxt="일반볼(짝/언더)" data-betid="15">
													<div class="topgroup"> <span class="title">일반</span><span class="benefit">3.70</span></div>
													<div class="icongroup"> <span class="icon2 bg_red2">짝</span><span class="icon3 bg_blue2">언</span></div>
												</div>
												<div class="team_sel_btn mix green" data-seltype="" data-rate="3.70" data-seltxt="일반볼(짝/오버)" data-betid="16">
													<div class="topgroup"> <span class="title">일반</span><span class="benefit">3.70</span></div>
													<div class="icongroup"> <span class="icon2 bg_red2">짝</span><span class="icon3 bg_red2">오</span></div>
												</div>
												<div class="close remain-time-con"></div>
											</li>
											
                                            </ul>
                                        <div style="width:100%;display:inline-flex;">
                                            <div style="width:35%;height:2px;background: linear-gradient(135deg, #000 0%,#555 23%,rgba(239,239,239,1) 49%,#666 73%,#000 100%);margin:10px auto"></div>
                                            <span style="margin:0 0 10px 0px;text-align:center;font-size:18px;">조 합</span>
                                            <div style="width:35%;height:2px;background: linear-gradient(135deg, #000 0%,#555 23%,rgba(239,239,239,1) 49%,#666 73%,#000 100%);margin:10px auto"></div>
                                        </div>
                                            <ul>
											<li class="">
												<div class="team_sel_btn mixnp yellow" data-seltype="" data-rate="7.0" data-seltxt="조합(홀/언더/파홀)" data-betid="17">
													<div class="topgroup"> <span class="title">조합</span><span class="benefit">7.0</span></div>
													<div class="icongroup" style="text-align: center;margin-top:5px;"> <span class="icon4 bg_blue2">홀</span><span class="icon4 bg_blue2">언</span><span class="icon4 bg_blue2" style="font-size:15px;">파홀</span></div>
												</div>
												<div class="team_sel_btn mixnp yellow" data-seltype="" data-rate="7.0" data-seltxt="조합(홀/오버/파짝)" data-betid="18">
													<div class="topgroup"> <span class="title">조합</span><span class="benefit">7.0</span></div>
													<div class="icongroup" style="text-align: center;margin-top:5px;"> <span class="icon4 bg_blue2">홀</span><span class="icon4 bg_blue2">언</span><span class="icon4 bg_red2" style="font-size:15px;">파짝</span></div>
												</div>
												<div class="team_sel_btn mixnp yellow" data-seltype="" data-rate="7.0" data-seltxt="조합(홀/언더/파홀)" data-betid="19">
													<div class="topgroup"> <span class="title">조합</span><span class="benefit">7.0</span></div>
													<div class="icongroup" style="text-align: center;margin-top:5px;"> <span class="icon4 bg_blue2">홀</span><span class="icon4 bg_red2">오</span><span class="icon4 bg_blue2" style="font-size:15px;">파홀</span></div>
												</div>
												<div class="team_sel_btn mixnp yellow" data-seltype="" data-rate="7.0" data-seltxt="조합(홀/오버/파짝)" data-betid="20">
													<div class="topgroup"> <span class="title">조합</span><span class="benefit">7.0</span></div>
													<div class="icongroup" style="text-align: center;margin-top:5px;"> <span class="icon4 bg_blue2">홀</span><span class="icon4 bg_red2">오</span><span class="icon4 bg_red2" style="font-size:15px;">파짝</span></div>
												</div>
												<div class="team_sel_btn mixnp yellow" data-seltype="" data-rate="7.0" data-seltxt="조합(짝/언더/파홀)" data-betid="21">
													<div class="topgroup"> <span class="title">조합</span><span class="benefit">7.0</span></div>
													<div class="icongroup" style="text-align: center;margin-top:5px;"> <span class="icon4 bg_red2">짝</span><span class="icon4 bg_blue2">언</span><span class="icon4 bg_blue2" style="font-size:15px;">파홀</span></div>
												</div>
												<div class="team_sel_btn mixnp yellow" data-seltype="" data-rate="7.0" data-seltxt="조합(짝/오버/파짝)" data-betid="22">
													<div class="topgroup"> <span class="title">조합</span><span class="benefit">7.0</span></div>
													<div class="icongroup" style="text-align: center;margin-top:5px;"> <span class="icon4 bg_red2">짝</span><span class="icon4 bg_blue2">언</span><span class="icon4 bg_red2" style="font-size:15px;">파짝</span></div>
												</div>
												<div class="team_sel_btn mixnp yellow" data-seltype="" data-rate="7.0" data-seltxt="조합(짝/언더/파홀)" data-betid="23">
													<div class="topgroup"> <span class="title">조합</span><span class="benefit">7.0</span></div>
													<div class="icongroup" style="text-align: center;margin-top:5px;"> <span class="icon4 bg_red2">짝</span><span class="icon4 bg_red2">오</span><span class="icon4 bg_blue2" style="font-size:15px;">파홀</span></div>
												</div>
												<div class="team_sel_btn mixnp yellow" data-seltype="" data-rate="7.0" data-seltxt="조합(짝/오버/파짝)" data-betid="24">
													<div class="topgroup"> <span class="title">조합</span><span class="benefit">7.0</span></div>
													<div class="icongroup" style="text-align: center;margin-top:5px;"> <span class="icon4 bg_red2">짝</span><span class="icon4 bg_red2">오</span><span class="icon4 bg_red2" style="font-size:15px;">파짝</span></div>
												</div>
												<div class="close remain-time-con"></div>
											</li>
										</ul>
									
									</div>
								</div>
								<div class="sel-list"></div>
								<div class="inputs">
									<ul class="bet-info">
										<li>
										 <span class="info-header">아이디</span><input type="text" id="mb_name" value="" readonly>
										 <span class="info-header">보유머니</span><input type="text" id="mb_money" class="mb_money" value="0" readonly>
										</li>
										<li>
										 <span class="info-header">선택경기</span><input type="text" id="cart_sel_text" value="" readonly>
										 <span class="info-header">적중예상</span><input type="text" id="cart_result_amount" class="input2" value="0" readonly>
										</li>
										<li>
										 <span class="info-header">총배당률</span><input type="text" id="cart_total_benefit" value="0" readonly>
			 							 <span class="info-header fc_yellow2 fb">베팅금액</span><input type="text" id="cart_amount" value="0" class="fc_yellow2 fs16 fb input2"> 
										</li>
										 <input type="hidden" id="cart_sel_type" value="" readonly>
										 <input type="hidden" id="cart_odnum2" value="" readonly>
									</ul>
									<div class="bet-btn">
										<button class="money-btn-4 amount" type="button" data-amount="1000">+ 1,000</button>
										<button class="money-btn-4 amount" type="button" data-amount="5000">+ 5,000</button>
										<button class="money-btn-4 amount" type="button" data-amount="10000">+ 10,000</button>
										<button class="money-btn-4 amount" type="button" data-amount="20000">+ 20,000</button>
										<button class="money-btn-4 amount" type="button" data-amount="30000">+ 30,000</button>
										<button class="money-btn-4 amount" type="button" data-amount="50000">+ 50,000</button>
										<button class="money-btn-4 amount" type="button" data-amount="100000">+ 100,000</button>
										<button class="money-btn-4 amount" type="button" data-amount="200000">+ 200,000</button>
										<button class="money-btn-4 amount" type="button" data-amount="300000">+ 300,000</button>
										<button class="money-btn-4 amount" type="button" data-amount="500000">+ 500,000</button>
																	
										<button class="money-btn-4 amount" type="button" data-amount="MAX">MAX</button>
										<button class="money-btn-4 amount" type="button" data-amount="0">초기화</button>
										<button class="bet money-btn-1" type="button" id="bet_bt">베팅하기</button>
									</div>
								</div>
								<div class="footer">
								</div>
							</div>
						</div>

					</div>
				</div>
				<div class="">
					<ul id="cart_bet_list"></ul>
				</div>
			</div>	
			<div style="clear:both"></div>
			</div>
		</article>
	</div>
</div>

<script>
		
	var deposit_max = '1000000';
	var seltxt = '';
	var selbetid = 0;
	var rate = 0;
	var amount = 0;
	var mb_money = '0';
	var old_betlist = [];
	var iround = 0;
	
	var bet_string = [
		'',  //0
		'파워볼(홀)',//01
		'파워볼(짝)',//02
		'파워볼(언더)',//03
		'파워볼(오버)',//04
		'파워볼(홀/언더)',//05
		'파워볼(홀/오버)',//06
		'파워볼(짝/언더)',//07
		'파워볼(짝/오버)',//08
		'일반볼(홀)',//09
		'일반볼(짝)',//10
		'일반볼(언더)',//11
		'일반볼(오버)',//12
		'일반볼(홀/언더)',//13
		'일반볼(홀/오버)',//14
		'일반볼(짝/언더)',//15
		'일반볼(짝/오버)',//16
		'조합(홀/언더/파홀)',//17
		'조합(홀/오버/파짝)',//18
		'조합(홀/언더/파홀)',//19
		'조합(홀/오버/파짝)',//20
		'조합(짝/언더/파홀)',//21
		'조합(짝/오버/파짝)',//22
		'조합(짝/언더/파홀)',//23
		'조합(짝/오버/파짝)',//24
	];
	var process_history = function ()
	{
		//User balance

		$.ajax({
			url: '/REST/GameEngine/UserInfo',
			type : 'post',
			dataType: 'json',
			async: true,
			cache: false,
			data : {
				token : '{{auth()->user()->api_token}}',
				init : false,
				p : 90
			},
			success: function(data) {			
				if( data.cmd == 'UserInfo' )					
				{
					mb_money = data.data.wallet;
					$('.mb_money').text(numberWithCommas(data.data.wallet));	
					$('.mb_money').val(numberWithCommas(data.data.wallet));
					$('#mb_name').val(data.data.userid);
				}
			}
		});	

		$.ajax({
			url: '/REST/GameEngine/HistoryBet',
			type : 'post',
			dataType: 'json',
			contentType: 'application/json',
			data: JSON.stringify({
				token : '{{auth()->user()->api_token}}',
				p : 90,
				s : 0,
				e : 0,
				pg : 0
			}),
			async: true,
			cache: false,
			success: function(data) {			
				if( data.cmd == 'HistoryBet' )					
				{				

					old_betlist = data.data.bet;
					
					list = '';
					$.each( old_betlist, function( key, val ) {
						bettime = val['date'] +  1356969600;
						dt=new Date(bettime * 1000).toLocaleString();
						list += '<li>';
						list += '<div class="time">'+dt+'</div>';
						list += '<div class="bet_list-round">'+val['dno'].substring(6)+'회차</div>';
						list += '<div class="sel">'+bet_string[val['rt']]+'</div>';
						list += '<div class="benefit">'+val['o']+'</div>';
						list += '<div class="amount">'+numberWithCommas(val['amt'])+'</div>';
						list += '<div class="amount">'+numberWithCommas(val['returnAmount'])+'</div>';

						if(val['result'] == null) list += '<div class="result">대기중</div>';
						else if(val['returnAmount'] == 0)
						{
							list += '<div class="result lose">낙첨</div>';
						} 
						else 
						{
							list += '<div class="result win">당첨</div>';
						}
						list += '</li>';
					});
					$('#cart_bet_list').html(list);
				}
			}			
		});	
	};

	var process_powerball = function()
	{
		//server time
		$.ajax({
			url: '/REST/GameEngine/ServerTime',
			type : 'get',
			dataType: 'json',
			async: true,
			cache: false,
			success: function(data) {			
				if( data.cmd == 'ServerTime' )					
				{
					servertime = data.data +  1356969600;
					dt=new Date(servertime * 1000).toLocaleString();
					$('.date').text(dt);	
				}
			}
		});	


		// Current Round
		$.ajax({
			url: '/REST/GameEngine/Trend?p=90',
			type : 'post',
			dataType: 'json',
			async: true,
			cache: false,
			success: function(data) {			
				if( data.cmd == 'Trend' )					
				{				
					draws = data.data.draw;
					if (draws.length == 0)
					{
						return;
					}
					currdraw = draws[0];

					if(iround != currdraw.dno)
					{
						iround = currdraw.dno;
						$('#iframe_result').attr('src', $('#iframe_result').attr('src'));
					}
					
					$('.round').text(currdraw.dno.substring(6) + '회');
					
					$(".bar").width( (currdraw.PartialResultTime * 10 / 54)+'%');
					bar_minute = Math.floor((currdraw.PartialResultTime / 2) / 60);
					bar_second = (currdraw.PartialResultTime / 2) % 60;
					$('.bar-time').text('베팅 마감       ' +  String(bar_minute).padStart(2, '0') + ':' + String(bar_second).padStart(2, '0'));
					
					if(currdraw.PartialResultTime == 0)
					{
						$('.game-list li').eq(0).addClass('bet-closed');
						$('.game-list li').eq(1).addClass('bet-closed');
						$('.game-list li').eq(2).addClass('bet-closed');
						$('.game-list li').eq(3).addClass('bet-closed');
						$('.game-list li').eq(4).addClass('bet-closed');
						
						$('#bet_bt').addClass('money-btn-0').text('');
						$('#bet_bt').unbind('click');
					}
					else
					{
						$('.game-list li').eq(0).removeClass('bet-closed');
						$('.game-list li').eq(1).removeClass('bet-closed');
						$('.game-list li').eq(2).removeClass('bet-closed');
						$('.game-list li').eq(3).removeClass('bet-closed');
						$('.game-list li').eq(4).removeClass('bet-closed');
						$('#bet_bt').removeClass('money-btn-0').text('베팅하기');
						$('#bet_bt').unbind('click');
						$('#bet_bt').bind('click', function() {  	
							doBetting();
						});
						
					}
				}
			}			
		});	

		
	}
	
	process_powerball();	
	setInterval(function() {
		process_powerball();	
	}, 1000);

	process_history();	
	setInterval(function() {
		process_history();	
	}, 10000);
  
  $('#bet_bt').bind('click', function() {  	
  	doBetting();
  });
  
  var doBetting = function(){
	$('#bet_bt').unbind('click');
	
	$.ajax({
		url: '/REST/GameEngine/SpreadBet',
		type : 'post',
		dataType: 'json',
		contentType: 'application/json',
		data: JSON.stringify({
			token : '{{auth()->user()->api_token}}',
			data : [
				{
					p : 90,
					dno : iround,
					id : selbetid,
					amt : amount,
				}
			],
			
		}),
		async: true,
		cache: false,
		success: function(data) {	
			$('#bet_bt').bind('click', function() {
				doBetting();  
			});
	
			if( data.code == 0)					
			{			
				mb_money = mb_money - amount;
				$('.mb_money').text(numberWithCommas(mb_money));	
				$('.mb_money').val(numberWithCommas(mb_money));
				amount = 0;
				cal();
				msg('베팅이 접수되었습니다', 'success');			
			}
			else
			{							
				msg('베팅오류. ' + data.code, 'error');			
			}				
		}
		
	});
};
	
	$('.money-btn-4').click(function() {		
		var btns = $('.money-btn-4');
		
		if($(this).attr('data-amount') == '0')
		{
			amount = 0;
		}
		else if($(this).attr('data-amount') == 'MAX')
		{
			if(parseInt(mb_money) > parseInt(deposit_max)) amount = deposit_max;
			else amount = mb_money;
			
			amount = Math.floor(amount/1000) * 1000;		
		}
		else
		{
	  	amount += parseInt($(this).attr('data-amount'));
	  }
	  
	  cal();
	});
	
	$('.team_sel_btn').click(function() {		
		var btns = $('.team_sel_btn');
	  $(btns).removeClass('sel');
	  $(this).addClass('sel'); 
	  
	  seltxt = $(this).attr('data-seltxt');
	  selbetid = $(this).attr('data-betid');
	  rate = $(this).attr('data-rate');
	  
	  $('#cart_sel_text').val( seltxt );
	  $('#cart_total_benefit').val( rate );
	  
	  cal();
	});
	
	function cal()
	{
		result_amount = amount * rate;
	  
	  $('#cart_result_amount').val(numberWithCommas(result_amount));
	  $('#cart_amount').val(numberWithCommas(amount));
	}
	
	$('.ifm-view').click(function() {	
		var btns = $('.ifm-view');
	  $(btns).attr('data-selected', '');
	  $(this).attr('data-selected', 'selected');
	  
	  if( $(this).attr('data-num') == '1' ) $('#iframe_result').attr('src', 'http://pow-9.com/powerball_odd.php');
	  else if( $(this).attr('data-num') == '2' ) $('#iframe_result').attr('src', 'http://pow-9.com/powerball_uo.php');
  	else if( $(this).attr('data-num') == '3' ) $('#iframe_result').attr('src', 'http://pow-9.com/nomal_odd.php');
  	else if( $(this).attr('data-num') == '4' ) $('#iframe_result').attr('src', 'http://pow-9.com/nomal_uo.php');
	});
</script>
						

</div>

</body>
</html>