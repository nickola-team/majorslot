<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>@yield('page-title')</title>
    <meta name="viewport" content="width=device-width">
    <link rel="icon" href="/frontend/unicon/img/logo_layer.png" >

	<script type="text/javascript" src="/frontend/unicon/js/jquery-1.12.3.min.js"></script>
	<script type="text/javascript" src="/frontend/unicon/js/jquery-ui.js"></script>
	<script type="text/javascript" src="/frontend/unicon/js/TINYbox.js"></script>
	<script type="text/javascript" src="/frontend/unicon/js/jquery.vticker.min.js"></script>
	<script type="text/javascript" src="/frontend/unicon/js/jquery.bxslider.min.js"></script>

	<link rel="stylesheet" type="text/css" href="/frontend/unicon/css/normalize.css" />
	<link rel="stylesheet" type="text/css" href="/frontend/unicon/css/main.css?ver=6038" />
	<link rel="stylesheet" type="text/css" href="/frontend/unicon/css/all.css">
	<link rel="stylesheet" type="text/css" href="/frontend/unicon/css/Style_sub.css" />

    <script type="text/javascript" src="/frontend/unicon/count/jquery.counter.js"></script>

	<script type='text/javascript' src="/frontend/unicon/js/game.js?ver=5210"></script>
	<script type='text/javascript' src='/frontend/unicon/js/menu.js?ver=3007'></script>
	<script type='text/javascript' src='/frontend/unicon/js/main.js?ver=6959'></script>
	<script type='text/javascript' src='/frontend/unicon/js/fund.js?ver=5645'></script>
	<script type='text/javascript' src='/frontend/unicon/js/common.js?ver=7896'></script>


	<!-- Sweet-Alert  -->
	<script type="text/javascript" src="/frontend/unicon/js/sweetalert/sweetalert.min.js"></script>
	<script type="text/javascript" src="/frontend/unicon/js/sweetalert/jquery.sweet-alert.custom.js"></script>
	<link rel="stylesheet" href="/frontend/unicon/js/sweetalert/sweetalert.css" type="text/css">
</head>
<body>
<div class="subcontent" id="deposit">
<form method="post" id="fundFrm" name="fundFrm">

<div id="sub_box">
	<div id="sub_title"><img src="/frontend/unicon/img/bonus_title.png" /></div>
	<div id="data_box">
		<div class="dbox0">
            <input type="hidden" value="<?= csrf_token() ?>" name="_token" id="_token">
			<table class="table100" border="0" cellspacing="0" cellpadding="0">
                <tr>
					<td class="line2">현재 보너스수익</td>
					<td class="line3">
						<div class="txt_area2">
                            <div class="txt_area3"> {{number_format(auth()->user()->deal_balance,0)}} 원</div>
						</div>
					</td>
				</tr>
				<tr>
					<td class="line2">전환하실 금액</td>
					<td class="line3">
						<div class="txt_area2">
							<input id="money" type="hidden" name="money" value="0">
							<input type="text" name="money1" id="money1" style="width:80px; height:30px" onchange="comma()" />
							<a href="javascript:money_count('50000');"><img src="/frontend/unicon/img/5man.gif" width="46" height="22" border="0" align="absmiddle" /></a>
							<a href="javascript:money_count('100000');"><img src="/frontend/unicon/img/10man.gif" width="46" height="22" border="0" align="absmiddle" /></a>
							<a href="javascript:money_count('300000');"><img src="/frontend/unicon/img/30man.gif" width="46" height="22" border="0" align="absmiddle" /></a>
							<a href="javascript:money_count('500000');"><img src="/frontend/unicon/img/50man.gif" width="46" height="22" border="0" align="absmiddle" /></a>
							<a href="javascript:money_count('1000000');"><img src="/frontend/unicon/img/100man.gif" width="46" height="22" border="0" align="absmiddle" /></a>
							<a href="javascript:money_count_hand();"><img src="/frontend/unicon/img/direct.gif" width="46" height="22" border="0" align="absmiddle" /></a>
						</div>
						<div class="txt_area"></div>
					</td>
				</tr>
			</table>
		</div>
		<div class="btn"><a href="#" onClick="dealout();"><img src="/frontend/unicon/img/deal_btn.png" border="0" /></a></div>
	</div>
</div>
</form>
</div>
</body>
</html>