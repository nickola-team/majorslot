
<?php
	$detect = new \Detection\MobileDetect();
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<title>@yield('page-title')</title>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no;">
<link rel="shortcut icon" href="/frontend/kdior/favicon.ico" type="image/x-icon">
<link href="/frontend/kdior/css/font.css?v=202301301150" rel="stylesheet" type="text/css">
<link href="/frontend/kdior/css/basic.css?v=202301301150" rel="stylesheet" type="text/css">

@if( $detect->isMobile() || $detect->isTablet() ) 
<link href="/frontend/kdior/css/m/common.css?v=202301301150" rel="stylesheet" type="text/css">
<link href="/frontend/kdior/css/m/layout.css?v=202301301150" rel="stylesheet" type="text/css">
@else
<link href="/frontend/kdior/css/common.css?v=202301301150" rel="stylesheet" type="text/css">
<link href="/frontend/kdior/css/layout.css?v=202301301150" rel="stylesheet" type="text/css">
@endif
<link rel="stylesheet" href="/frontend/kdior/css/animations.css?v=202301301150"><!-- CSS animations1 -->
<script language="javascript" src="/frontend/kdior/js/showid.js" type="text/javascript"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script><!-- sk_기본 -->
<script type="text/javascript" src="/frontend/kdior/jq/sk_popup/sk_popup.js"></script><!-- 팝업 -->
<script type="text/javascript" src="/frontend/kdior/js/sk_tab.js"></script><!-- sk_탭 -->
<link href="/frontend/kdior/jq/hamburger1/menu.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/frontend/kdior/jq/hamburger1/menu.js"></script>

<script src="/frontend/kdior/js/func.js"></script>
</head>

<body>
	<!-- ★메인★ -->
	<div id="wrap">
		@if( $detect->isMobile() || $detect->isTablet() ) 
			@include('frontend.kdior.layouts.partials.m.header', ['logo' => 'king'])
			@include('frontend.kdior.layouts.partials.m.content')
		@else
			@include('frontend.kdior.layouts.partials.header', ['logo' => 'king'])
			@include('frontend.kdior.layouts.partials.banner')
			<div class="main_contents_wrap">
				<div class="main_contents_box">
					@include('frontend.kdior.layouts.partials.category')
					@include('frontend.kdior.layouts.partials.board')
				</div>
			</div>
		@endif

		<div class="footer_wrap">
			<div class="footer_copyright">
				<img src="/frontend/kdior/images/footer_partner.png?v=202301301150">
				<br><br>
				<img src="/frontend/kdior/images/footer_bank.png?v=202301301150">
				<br>
				Copyright (c) 2021 @yield('page-title') All rights reserved
			</div>
		</div><!-- footer -->
		@if( ($detect->isMobile() || $detect->isTablet() ) && Auth::check()) 
			@include('frontend.kdior.layouts.partials.m.aside', ['logo' => 'king'])
		@endif
		
	</div><!-- wrap -->
	@yield('content')
	@auth()
		@include('frontend.kdior.modals.common')
	@else
		@if( $detect->isMobile() || $detect->isTablet() ) 
		@else
		@include('frontend.kdior.modals.login')
		@endif
		@include('frontend.kdior.modals.join')
	@endif

	@foreach ($noticelist as $ntc)
	@include('frontend.kdior.modals.notice', ['notice' => $ntc])
	@endforeach
</body>
</html>

