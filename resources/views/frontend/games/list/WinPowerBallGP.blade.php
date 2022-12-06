<!doctype html>
<html lang="ko">
<head>
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
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
                                <span class="title">{{isset($title)?__('powerball.'.$title,[],$pagelang):__('powerball.WINPowerBall',[],$pagelang)}} </span>
							</div>
							<div class="instants-game-div" style="margin-left:10px;">
								<div class="broadcast-div" style="width:830px;height:580px;margin:0 auto;overflow:hidden;">
									<div style="position:absolute;top:80px;">
										<iframe id="iframevideo" src="/GamePlay/WinPowerBall?p={{$p??90}}" width="830" height="600" scrolling="no" frameborder="0" style="overflow:hidden;" allow="autoplay;"></iframe>
									</div>
								</div>
							</div>
							<div style="width:100%;display:inline-flex;">
								<div style="width:35%;height:2px;background: linear-gradient(135deg, #000 0%,#555 23%,rgba(239,239,239,1) 49%,#666 73%,#000 100%);margin:10px auto"></div>
								<span style="margin:0 0 10px 0px;text-align:center;font-size:18px;">{{__('powerball.LastBetHistory',[],$pagelang)}}</span>
								<div style="width:35%;height:2px;background: linear-gradient(135deg, #000 0%,#555 23%,rgba(239,239,239,1) 49%,#666 73%,#000 100%);margin:10px auto"></div>
							</div>
							<div class="">
								<ul id="cart_bet_list">
								<li><div class="result">{{__('powerball.EmptyHistory',[],$pagelang)}}</div></li>
								</ul>
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
                                                    <div class="round">------{{__('powerball.Round',[],$pagelang)}}</div>
                                                </div>
                                            </ul>
                                    </div>
									<div class="game-list  ht330">
                                    <div style="width:100%;display:inline-flex;">
                                        <div style="width:35%;height:2px;background: linear-gradient(135deg, #000 0%,#555 23%,rgba(239,239,239,1) 49%,#666 73%,#000 100%);margin:10px auto"></div>
                                        <span style="margin:0 0 10px 0px;text-align:center;font-size:18px;">{{__('powerball.Powerball',[],$pagelang)}}</span>
                                        <div style="width:35%;height:2px;background: linear-gradient(135deg, #000 0%,#555 23%,rgba(239,239,239,1) 49%,#666 73%,#000 100%);margin:10px auto"></div>
                                    </div>
										<ul>
											<li class="">
												<div class="team_sel_btn home blue" data-seltype="" data-rate="1.95" data-seltxt="{{__('powerball.Powerball',[],$pagelang)}}({{__('powerball.Odd',[],$pagelang)}})" data-betid="1">
													<span class="icon">{{__('powerball.Odd',[],$pagelang)}}</span><span class="title">{{__('powerball.Power',[],$pagelang)}}</span><br><span class="benefit">1.95</span>
												</div>
												<div class="team_sel_btn away red" data-seltype="" data-rate="1.95" data-seltxt="{{__('powerball.Powerball',[],$pagelang)}}({{__('powerball.Even',[],$pagelang)}})" data-betid="2">
													<span class="icon">{{__('powerball.Even',[],$pagelang)}}</span><span class="title">{{__('powerball.Power',[],$pagelang)}}</span><br><span class="benefit">1.95</span>
												</div>
												<div class="team_sel_btn home blue" data-seltype="" data-rate="1.95" data-seltxt="{{__('powerball.Powerball',[],$pagelang)}}({{__('powerball.Under',[],$pagelang)}})" data-betid="3">
													<span class="icon">{{__('powerball.Under',[],$pagelang)}}</span><span class="title">{{__('powerball.Power',[],$pagelang)}}</span><br><span class="benefit">1.95</span>
												</div>
												<div class="team_sel_btn away red" data-seltype="" data-rate="1.95" data-seltxt="{{__('powerball.Powerball',[],$pagelang)}}({{__('powerball.Over',[],$pagelang)}})" data-betid="4">
													<span class="icon">{{__('powerball.Over',[],$pagelang)}}</span><span class="title">{{__('powerball.Power',[],$pagelang)}}</span><br><span class="benefit">1.95</span>
												</div>
												<div class="close remain-time-con"></div>
											</li>
                                            <li class="">
												<div class="team_sel_btn mix green" data-seltype="" data-rate="4.0" data-seltxt="{{__('powerball.Powerball',[],$pagelang)}}({{__('powerball.Odd',[],$pagelang)}}/{{__('powerball.Under',[],$pagelang)}})" data-betid="5">
													<div class="topgroup"> <span class="title">{{__('powerball.Power',[],$pagelang)}}</span><span class="benefit">4.0</span></div>
													<div class="icongroup"> <span class="icon2 bg_blue2">{{__('powerball.Odd',[],$pagelang)}}</span><span class="icon3 bg_blue2">{{__('powerball.Under',[],$pagelang)}}</span></div>
												</div>
												<div class="team_sel_btn mix green" data-seltype="" data-rate="3.0" data-seltxt="{{__('powerball.Powerball',[],$pagelang)}}({{__('powerball.Odd',[],$pagelang)}}/{{__('powerball.Over',[],$pagelang)}})" data-betid="6">
													<div class="topgroup"> <span class="title">{{__('powerball.Power',[],$pagelang)}}</span><span class="benefit">3.0</span></div>
													<div class="icongroup"> <span class="icon2 bg_blue2">{{__('powerball.Odd',[],$pagelang)}}</span><span class="icon3 bg_red2">{{__('powerball.Over',[],$pagelang)}}</span></div>
												</div>
												<div class="team_sel_btn mix green" data-seltype="" data-rate="3.0" data-seltxt="{{__('powerball.Powerball',[],$pagelang)}}({{__('powerball.Even',[],$pagelang)}}/{{__('powerball.Under',[],$pagelang)}})" data-betid="7">
													<div class="topgroup"> <span class="title">{{__('powerball.Power',[],$pagelang)}}</span><span class="benefit">3.0</span></div>
													<div class="icongroup"> <span class="icon2 bg_red2">{{__('powerball.Even',[],$pagelang)}}</span><span class="icon3 bg_blue2">{{__('powerball.Under',[],$pagelang)}}</span></div>
												</div>
												<div class="team_sel_btn mix green" data-seltype="" data-rate="4.0" data-seltxt="{{__('powerball.Powerball',[],$pagelang)}}({{__('powerball.Even',[],$pagelang)}}/{{__('powerball.Over',[],$pagelang)}})" data-betid="8">
													<div class="topgroup"> <span class="title">{{__('powerball.Power',[],$pagelang)}}</span><span class="benefit">4.0</span></div>
													<div class="icongroup"> <span class="icon2 bg_red2">{{__('powerball.Even',[],$pagelang)}}</span><span class="icon3 bg_red2">{{__('powerball.Over',[],$pagelang)}}</span></div>
												</div>
												<div class="close remain-time-con"></div>
											</li>
											
                                    </ul>
                                    <div style="width:100%;display:inline-flex;">
                                        <div style="width:35%;height:2px;background: linear-gradient(135deg, #000 0%,#555 23%,rgba(239,239,239,1) 49%,#666 73%,#000 100%);margin:10px auto"></div>
                                        <span style="margin:0 0 10px 0px;text-align:center;font-size:18px;">{{__('powerball.Normalball',[],$pagelang)}}</span>
                                        <div style="width:35%;height:2px;background: linear-gradient(135deg, #000 0%,#555 23%,rgba(239,239,239,1) 49%,#666 73%,#000 100%);margin:10px auto"></div>
                                    </div>
										<ul>
											<li class="">
												<div class="team_sel_btn home blue" data-seltype="" data-rate="1.95" data-seltxt="{{__('powerball.Normalball',[],$pagelang)}}({{__('powerball.Odd',[],$pagelang)}})" data-betid="9">
													<span class="icon">{{__('powerball.Odd',[],$pagelang)}}</span><span class="title">{{__('powerball.Normal',[],$pagelang)}}</span><br><span class="benefit">1.95</span>
												</div>
												<div class="team_sel_btn away red" data-seltype="" data-rate="1.95" data-seltxt="{{__('powerball.Normalball',[],$pagelang)}}({{__('powerball.Even',[],$pagelang)}})" data-betid="10">
													<span class="icon">{{__('powerball.Even',[],$pagelang)}}</span><span class="title">{{__('powerball.Normal',[],$pagelang)}}</span><br><span class="benefit">1.95</span>
												</div>
												<div class="team_sel_btn home blue" data-seltype="" data-rate="1.95" data-seltxt="{{__('powerball.Normalball',[],$pagelang)}}({{__('powerball.Under',[],$pagelang)}})" data-betid="11">
													<span class="icon">{{__('powerball.Under',[],$pagelang)}}</span><span class="title">{{__('powerball.Normal',[],$pagelang)}}</span><br><span class="benefit">1.95</span>
												</div>
												<div class="team_sel_btn away red" data-seltype="" data-rate="1.95" data-seltxt="{{__('powerball.Normalball',[],$pagelang)}}({{__('powerball.Over',[],$pagelang)}})" data-betid="12">
													<span class="icon">{{__('powerball.Over',[],$pagelang)}}</span><span class="title">{{__('powerball.Normal',[],$pagelang)}}</span><br><span class="benefit">1.95</span>
												</div>
												<div class="close remain-time-con"></div>
											</li>
											<li class="">
												<div class="team_sel_btn mix green" data-seltype="" data-rate="3.70" data-seltxt="{{__('powerball.Normalball',[],$pagelang)}}({{__('powerball.Odd',[],$pagelang)}}/{{__('powerball.Under',[],$pagelang)}})" data-betid="13">
													<div class="topgroup"> <span class="title">{{__('powerball.Normal',[],$pagelang)}}</span><span class="benefit">3.70</span></div>
													<div class="icongroup"> <span class="icon2 bg_blue2">{{__('powerball.Odd',[],$pagelang)}}</span><span class="icon3 bg_blue2">{{__('powerball.Under',[],$pagelang)}}</span></div>
												</div>
												<div class="team_sel_btn mix green" data-seltype="" data-rate="3.70" data-seltxt="{{__('powerball.Normalball',[],$pagelang)}}({{__('powerball.Odd',[],$pagelang)}}/{{__('powerball.Over',[],$pagelang)}})" data-betid="14">
													<div class="topgroup"> <span class="title">{{__('powerball.Normal',[],$pagelang)}}</span><span class="benefit">3.70</span></div>
													<div class="icongroup"> <span class="icon2 bg_blue2">{{__('powerball.Odd',[],$pagelang)}}</span><span class="icon3 bg_red2">{{__('powerball.Over',[],$pagelang)}}</span></div>
												</div>
												<div class="team_sel_btn mix green" data-seltype="" data-rate="3.70" data-seltxt="{{__('powerball.Normalball',[],$pagelang)}}({{__('powerball.Even',[],$pagelang)}}/{{__('powerball.Under',[],$pagelang)}})" data-betid="15">
													<div class="topgroup"> <span class="title">{{__('powerball.Normal',[],$pagelang)}}</span><span class="benefit">3.70</span></div>
													<div class="icongroup"> <span class="icon2 bg_red2">{{__('powerball.Even',[],$pagelang)}}</span><span class="icon3 bg_blue2">{{__('powerball.Under',[],$pagelang)}}</span></div>
												</div>
												<div class="team_sel_btn mix green" data-seltype="" data-rate="3.70" data-seltxt="{{__('powerball.Normalball',[],$pagelang)}}({{__('powerball.Even',[],$pagelang)}}/{{__('powerball.Over',[],$pagelang)}})" data-betid="16">
													<div class="topgroup"> <span class="title">{{__('powerball.Normal',[],$pagelang)}}</span><span class="benefit">3.70</span></div>
													<div class="icongroup"> <span class="icon2 bg_red2">{{__('powerball.Even',[],$pagelang)}}</span><span class="icon3 bg_red2">{{__('powerball.Over',[],$pagelang)}}</span></div>
												</div>
												<div class="close remain-time-con"></div>
											</li>
											
                                            </ul>
                                        <div style="width:100%;display:inline-flex;">
                                            <div style="width:35%;height:2px;background: linear-gradient(135deg, #000 0%,#555 23%,rgba(239,239,239,1) 49%,#666 73%,#000 100%);margin:10px auto"></div>
                                            <span style="margin:0 0 10px 0px;text-align:center;font-size:18px;">{{__('powerball.Combball',[],$pagelang)}}</span>
                                            <div style="width:35%;height:2px;background: linear-gradient(135deg, #000 0%,#555 23%,rgba(239,239,239,1) 49%,#666 73%,#000 100%);margin:10px auto"></div>
                                        </div>
                                            <ul>
											<li class="">
												<div class="team_sel_btn mixnp yellow" data-seltype="" data-rate="7.0" data-seltxt="{{__('powerball.Comb',[],$pagelang)}}({{__('powerball.Odd',[],$pagelang)}}/{{__('powerball.Under',[],$pagelang)}}/{{__('powerball.P',[],$pagelang)}}{{__('powerball.Odd',[],$pagelang)}})" data-betid="17">
													<div class="topgroup"> <span class="title">{{__('powerball.Comb',[],$pagelang)}}</span><span class="benefit">7.0</span></div>
													<div class="icongroup" style="text-align: center;margin-top:5px;"> <span class="icon4 bg_blue2">{{__('powerball.Odd',[],$pagelang)}}</span><span class="icon4 bg_blue2">{{__('powerball.Under',[],$pagelang)}}</span></div>
													<div class="icongroup" style="text-align: center;margin-top:5px;"> <span class="icon4 bg_blue2" style="font-size:15px;">{{__('powerball.P',[],$pagelang)}}{{__('powerball.Odd',[],$pagelang)}}</span></div>
												</div>
												<div class="team_sel_btn mixnp yellow" data-seltype="" data-rate="7.0" data-seltxt="{{__('powerball.Comb',[],$pagelang)}}({{__('powerball.Odd',[],$pagelang)}}/{{__('powerball.Over',[],$pagelang)}}/{{__('powerball.P',[],$pagelang)}}{{__('powerball.Even',[],$pagelang)}})" data-betid="18">
													<div class="topgroup"> <span class="title">{{__('powerball.Comb',[],$pagelang)}}</span><span class="benefit">7.0</span></div>
													<div class="icongroup" style="text-align: center;margin-top:5px;"> <span class="icon4 bg_blue2">{{__('powerball.Odd',[],$pagelang)}}</span><span class="icon4 bg_blue2">{{__('powerball.Under',[],$pagelang)}}</span></div>
													<div class="icongroup" style="text-align: center;margin-top:5px;"> <span class="icon4 bg_red2" style="font-size:15px;">{{__('powerball.P',[],$pagelang)}}{{__('powerball.Even',[],$pagelang)}}</span></div>
												</div>
												<div class="team_sel_btn mixnp yellow" data-seltype="" data-rate="7.0" data-seltxt="{{__('powerball.Comb',[],$pagelang)}}({{__('powerball.Odd',[],$pagelang)}}/{{__('powerball.Under',[],$pagelang)}}/{{__('powerball.P',[],$pagelang)}}{{__('powerball.Odd',[],$pagelang)}})" data-betid="19">
													<div class="topgroup"> <span class="title">{{__('powerball.Comb',[],$pagelang)}}</span><span class="benefit">7.0</span></div>
													<div class="icongroup" style="text-align: center;margin-top:5px;"> <span class="icon4 bg_blue2">{{__('powerball.Odd',[],$pagelang)}}</span><span class="icon4 bg_red2">{{__('powerball.Over',[],$pagelang)}}</span></div>
													<div class="icongroup" style="text-align: center;margin-top:5px;"> <span class="icon4 bg_blue2" style="font-size:15px;">{{__('powerball.P',[],$pagelang)}}{{__('powerball.Odd',[],$pagelang)}}</span></div>
												</div>
												<div class="team_sel_btn mixnp yellow" data-seltype="" data-rate="7.0" data-seltxt="{{__('powerball.Comb',[],$pagelang)}}({{__('powerball.Odd',[],$pagelang)}}/{{__('powerball.Over',[],$pagelang)}}/{{__('powerball.P',[],$pagelang)}}{{__('powerball.Even',[],$pagelang)}})" data-betid="20">
													<div class="topgroup"> <span class="title">{{__('powerball.Comb',[],$pagelang)}}</span><span class="benefit">7.0</span></div>
													<div class="icongroup" style="text-align: center;margin-top:5px;"> <span class="icon4 bg_blue2">{{__('powerball.Odd',[],$pagelang)}}</span><span class="icon4 bg_red2">{{__('powerball.Over',[],$pagelang)}}</span></div>
													<div class="icongroup" style="text-align: center;margin-top:5px;"> <span class="icon4 bg_red2" style="font-size:15px;">{{__('powerball.P',[],$pagelang)}}{{__('powerball.Even',[],$pagelang)}}</span></div>
												</div>
												<div class="team_sel_btn mixnp yellow" data-seltype="" data-rate="7.0" data-seltxt="{{__('powerball.Comb',[],$pagelang)}}({{__('powerball.Even',[],$pagelang)}}/{{__('powerball.Under',[],$pagelang)}}/{{__('powerball.P',[],$pagelang)}}{{__('powerball.Odd',[],$pagelang)}})" data-betid="21">
													<div class="topgroup"> <span class="title">{{__('powerball.Comb',[],$pagelang)}}</span><span class="benefit">7.0</span></div>
													<div class="icongroup" style="text-align: center;margin-top:5px;"> <span class="icon4 bg_red2">{{__('powerball.Even',[],$pagelang)}}</span><span class="icon4 bg_blue2">{{__('powerball.Under',[],$pagelang)}}</span></div>
													<div class="icongroup" style="text-align: center;margin-top:5px;"> <span class="icon4 bg_blue2" style="font-size:15px;">{{__('powerball.P',[],$pagelang)}}{{__('powerball.Odd',[],$pagelang)}}</span></div>
												</div>
												<div class="team_sel_btn mixnp yellow" data-seltype="" data-rate="7.0" data-seltxt="{{__('powerball.Comb',[],$pagelang)}}({{__('powerball.Even',[],$pagelang)}}/{{__('powerball.Over',[],$pagelang)}}/{{__('powerball.P',[],$pagelang)}}{{__('powerball.Even',[],$pagelang)}})" data-betid="22">
													<div class="topgroup"> <span class="title">{{__('powerball.Comb',[],$pagelang)}}</span><span class="benefit">7.0</span></div>
													<div class="icongroup" style="text-align: center;margin-top:5px;"> <span class="icon4 bg_red2">{{__('powerball.Even',[],$pagelang)}}</span><span class="icon4 bg_blue2">{{__('powerball.Under',[],$pagelang)}}</span></div>
													<div class="icongroup" style="text-align: center;margin-top:5px;"> <span class="icon4 bg_red2" style="font-size:15px;">{{__('powerball.P',[],$pagelang)}}{{__('powerball.Even',[],$pagelang)}}</span></div>
												</div>
												<div class="team_sel_btn mixnp yellow" data-seltype="" data-rate="7.0" data-seltxt="{{__('powerball.Comb',[],$pagelang)}}({{__('powerball.Even',[],$pagelang)}}/{{__('powerball.Under',[],$pagelang)}}/{{__('powerball.P',[],$pagelang)}}{{__('powerball.Odd',[],$pagelang)}})" data-betid="23">
													<div class="topgroup"> <span class="title">{{__('powerball.Comb',[],$pagelang)}}</span><span class="benefit">7.0</span></div>
													<div class="icongroup" style="text-align: center;margin-top:5px;"> <span class="icon4 bg_red2">{{__('powerball.Even',[],$pagelang)}}</span><span class="icon4 bg_red2">{{__('powerball.Over',[],$pagelang)}}</span></div>
													<div class="icongroup" style="text-align: center;margin-top:5px;"> <span class="icon4 bg_blue2" style="font-size:15px;">{{__('powerball.P',[],$pagelang)}}{{__('powerball.Odd',[],$pagelang)}}</span></div>
												</div>
												<div class="team_sel_btn mixnp yellow" data-seltype="" data-rate="7.0" data-seltxt="{{__('powerball.Comb',[],$pagelang)}}({{__('powerball.Even',[],$pagelang)}}/{{__('powerball.Over',[],$pagelang)}}/{{__('powerball.P',[],$pagelang)}}{{__('powerball.Even',[],$pagelang)}})" data-betid="24">
													<div class="topgroup"> <span class="title">{{__('powerball.Comb',[],$pagelang)}}</span><span class="benefit">7.0</span></div>
													<div class="icongroup" style="text-align: center;margin-top:5px;"> <span class="icon4 bg_red2">{{__('powerball.Even',[],$pagelang)}}</span><span class="icon4 bg_red2">{{__('powerball.Over',[],$pagelang)}}</span>
													</div>
													<div class="icongroup" style="text-align: center;margin-top:5px;"> 
													<span class="icon4 bg_red2" style="font-size:15px;">{{__('powerball.P',[],$pagelang)}}{{__('powerball.Even',[],$pagelang)}}</span></div>
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
										 <span class="info-header">{{__('powerball.Id',[],$pagelang)}}</span><input type="text" id="mb_name" value="" readonly>
										 <span class="info-header">{{__('powerball.Balance',[],$pagelang)}}</span><input type="text" id="mb_money" class="mb_money" value="0" readonly>
										</li>
										<li>
										 <span class="info-header">{{__('powerball.Selectgame',[],$pagelang)}}</span><input type="text" id="cart_sel_text" value="" readonly>
										 <span class="info-header">{{__('powerball.Winamount',[],$pagelang)}}</span><input type="text" id="cart_result_amount" class="input2" value="0" readonly>
										</li>
										<li>
										 <span class="info-header">{{__('powerball.Rate',[],$pagelang)}}</span><input type="text" id="cart_total_benefit" value="0" readonly>
			 							 <span class="info-header fc_yellow2 fb">{{__('powerball.Betbalance',[],$pagelang)}}</span><input type="text" id="cart_amount" value="0" class="fc_yellow2 fs16 fb input2"> 
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
										<button class="money-btn-4 amount" type="button" data-amount="0">{{__('powerball.Init',[],$pagelang)}}</button>
										<button class="bet money-btn-1" type="button" id="bet_bt">{{__('powerball.Betting',[],$pagelang)}}</button>
									</div>
								</div>
								<div class="footer">
								</div>
							</div>
						</div>

					</div>
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
		'{{__('powerball.Powerball',[],$pagelang)}}({{__('powerball.Odd',[],$pagelang)}})',//01
		'{{__('powerball.Powerball',[],$pagelang)}}({{__('powerball.Even',[],$pagelang)}})',//02
		'{{__('powerball.Powerball',[],$pagelang)}}({{__('powerball.Under',[],$pagelang)}})',//03
		'{{__('powerball.Powerball',[],$pagelang)}}({{__('powerball.Over',[],$pagelang)}})',//04
		'{{__('powerball.Powerball',[],$pagelang)}}({{__('powerball.Odd',[],$pagelang)}}/{{__('powerball.Under',[],$pagelang)}})',//05
		'{{__('powerball.Powerball',[],$pagelang)}}({{__('powerball.Odd',[],$pagelang)}}/{{__('powerball.Over',[],$pagelang)}})',//06
		'{{__('powerball.Powerball',[],$pagelang)}}({{__('powerball.Even',[],$pagelang)}}/{{__('powerball.Under',[],$pagelang)}})',//07
		'{{__('powerball.Powerball',[],$pagelang)}}({{__('powerball.Even',[],$pagelang)}}/{{__('powerball.Over',[],$pagelang)}})',//08
		'{{__('powerball.Normalball',[],$pagelang)}}({{__('powerball.Odd',[],$pagelang)}})',//09
		'{{__('powerball.Normalball',[],$pagelang)}}({{__('powerball.Even',[],$pagelang)}})',//10
		'{{__('powerball.Normalball',[],$pagelang)}}({{__('powerball.Under',[],$pagelang)}})',//11
		'{{__('powerball.Normalball',[],$pagelang)}}({{__('powerball.Over',[],$pagelang)}})',//12
		'{{__('powerball.Normalball',[],$pagelang)}}({{__('powerball.Odd',[],$pagelang)}}/{{__('powerball.Under',[],$pagelang)}})',//13
		'{{__('powerball.Normalball',[],$pagelang)}}({{__('powerball.Odd',[],$pagelang)}}/{{__('powerball.Over',[],$pagelang)}})',//14
		'{{__('powerball.Normalball',[],$pagelang)}}({{__('powerball.Even',[],$pagelang)}}/{{__('powerball.Under',[],$pagelang)}})',//15
		'{{__('powerball.Normalball',[],$pagelang)}}({{__('powerball.Even',[],$pagelang)}}/{{__('powerball.Over',[],$pagelang)}})',//16
		'{{__('powerball.Comb',[],$pagelang)}}({{__('powerball.Odd',[],$pagelang)}}/{{__('powerball.Under',[],$pagelang)}}/{{__('powerball.P',[],$pagelang)}}{{__('powerball.Odd',[],$pagelang)}})',//17
		'{{__('powerball.Comb',[],$pagelang)}}({{__('powerball.Odd',[],$pagelang)}}/{{__('powerball.Over',[],$pagelang)}}/{{__('powerball.P',[],$pagelang)}}{{__('powerball.Even',[],$pagelang)}})',//18
		'{{__('powerball.Comb',[],$pagelang)}}({{__('powerball.Odd',[],$pagelang)}}/{{__('powerball.Under',[],$pagelang)}}/{{__('powerball.P',[],$pagelang)}}{{__('powerball.Odd',[],$pagelang)}})',//19
		'{{__('powerball.Comb',[],$pagelang)}}({{__('powerball.Odd',[],$pagelang)}}/{{__('powerball.Over',[],$pagelang)}}/{{__('powerball.P',[],$pagelang)}}{{__('powerball.Even',[],$pagelang)}})',//20
		'{{__('powerball.Comb',[],$pagelang)}}({{__('powerball.Even',[],$pagelang)}}/{{__('powerball.Under',[],$pagelang)}}/{{__('powerball.P',[],$pagelang)}}{{__('powerball.Odd',[],$pagelang)}})',//21
		'{{__('powerball.Comb',[],$pagelang)}}({{__('powerball.Even',[],$pagelang)}}/{{__('powerball.Over',[],$pagelang)}}/{{__('powerball.P',[],$pagelang)}}{{__('powerball.Even',[],$pagelang)}})',//22
		'{{__('powerball.Comb',[],$pagelang)}}({{__('powerball.Even',[],$pagelang)}}/{{__('powerball.Under',[],$pagelang)}}/{{__('powerball.P',[],$pagelang)}}{{__('powerball.Odd',[],$pagelang)}})',//23
		'{{__('powerball.Comb',[],$pagelang)}}({{__('powerball.Even',[],$pagelang)}}/{{__('powerball.Over',[],$pagelang)}}/{{__('powerball.P',[],$pagelang)}}{{__('powerball.Even',[],$pagelang)}})',//24
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
				p : {{$p??90}}
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
				p : {{$p??90}},
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
					if (old_betlist.length == 0)
					{
						list += '<li><div class="result">{{__('powerball.EmptyHistory',[],$pagelang)}}</div></li>';
					}
					else
					{
						$.each( old_betlist, function( key, val ) {
							bettime = val['date'] +  1356969600;
							dt=new Date(bettime * 1000).toLocaleString();
							list += '<li>';
							list += '<div class="time">'+dt+'</div>';
							list += '<div class="bet_list-round">'+val['dno'].substring(6)+'{{__('powerball.Roundum',[],$pagelang)}}</div>';
							list += '<div class="sel">'+bet_string[val['rt']]+'</div>';
							list += '<div class="benefit">'+val['o']+'</div>';
							list += '<div class="amount">'+numberWithCommas(val['amt'])+'</div>';
							list += '<div class="amount">'+numberWithCommas(val['returnAmount'])+'</div>';

							if(val['result'] == null) list += '<div class="result">{{__('powerball.Wait',[],$pagelang)}}</div>';
							else if(val['returnAmount'] == 0)
							{
								list += '<div class="result lose">{{__('powerball.Lose',[],$pagelang)}}</div>';
							} 
							else 
							{
								list += '<div class="result win">{{__('powerball.Win',[],$pagelang)}}</div>';
							}
							list += '</li>';
						});
					}

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
			url: '/REST/GameEngine/Trend?p={{$p??90}}',
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

					for (i=0;i<draws.length;i++)
					{
						if (draws[i].s == 0)
						{
							currdraw = draws[i];
						}
						else
						{
							break;
						}
					}

					if(iround != currdraw.dno)
					{
						iround = currdraw.dno;
						$('#iframe_result').attr('src', $('#iframe_result').attr('src'));
					}
					
					$('.round').text(currdraw.dno.substring(6) + '{{__('powerball.Round',[],$pagelang)}}');
					
					$(".bar").width( (currdraw.PartialResultTime * 100 /  ({{$gtime??270}} * 2))+'%');
					bar_minute = Math.floor((currdraw.PartialResultTime / 2) / 60);
					bar_second = (currdraw.PartialResultTime / 2) % 60;
					$('.bar-time').text('{{__('powerball.Endbet',[],$pagelang)}}       ' +  String(bar_minute).padStart(2, '0') + ':' + String(bar_second).padStart(2, '0'));
					
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
						$('#bet_bt').removeClass('money-btn-0').text('{{__('powerball.Betting',[],$pagelang)}}');
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
	if (amount == 0)
	{
		msg('{{__('powerball.Invalidbetbalance',[],$pagelang)}}', 'error');
		return;
	}
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
					p : {{$p??90}},
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
				process_history();
				msg('{{__('powerball.Betaccepted',[],$pagelang)}}', 'success');
			}
			else
			{							
				msg('{{__('powerball.Beterror',[],$pagelang)}}' + data.code, 'error');			
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
	
</script>
						

</div>

</body>
</html>