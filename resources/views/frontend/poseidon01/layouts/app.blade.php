
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
<link href="/frontend/poseidon01/css/m/layout.css?v=202301301150" rel="stylesheet" type="text/css">
@else
<link href="/frontend/kdior/css/common.css?v=202301301150" rel="stylesheet" type="text/css">
<link href="/frontend/poseidon01/css/layout.css?v=202301301150" rel="stylesheet" type="text/css">
@endif
<link rel="stylesheet" href="/frontend/kdior/css/animations.css?v=202301301150"><!-- CSS animations1 -->
<script language="javascript" src="/frontend/kdior/js/showid.js" type="text/javascript"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script><!-- sk_기본 -->
<script type="text/javascript" src="/frontend/kdior/jq/sk_popup/sk_popup.js"></script><!-- 팝업 -->
<link href="/frontend/kdior/jq/hamburger1/menu.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/frontend/kdior/jq/hamburger1/menu.js"></script>
<script type="text/javascript" src="/frontend/kdior/js/sk_tab.js"></script><!-- sk_탭 -->
<script src="/frontend/kdior/js/func.js"></script>
  
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">

<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Anton'>

<link rel="stylesheet" href="/frontend/poseidon01/css/star.css">

</head>

<body>
	<!-- ★메인★ -->
	<div id="wrap">
		@if (empty($logo))
		<?php $logo = 'poseidon01'; ?>
		@endif
		@if( $detect->isMobile() || $detect->isTablet() ) 
			@include('frontend.poseidon01.layouts.partials.m.header')
			@include('frontend.poseidon01.layouts.partials.m.content')
		@else
			@include('frontend.poseidon01.layouts.partials.header')
			@include('frontend.poseidon01.layouts.partials.banner')
			<div class="main_contents_wrap">
				<div class="main_contents_box">
					@include('frontend.kdior.layouts.partials.category')
					@include('frontend.kdior.layouts.partials.board')
				</div>
			</div>
		@endif

		@include('frontend.kdior.layouts.partials.footer')
		@if( ($detect->isMobile() || $detect->isTablet() ) && Auth::check()) 
			@include('frontend.poseidon01.layouts.partials.m.aside')
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
	@if ($ntc->popup == 'popup')
		@include('frontend.kdior.modals.notice', ['notice' => $ntc])
	@endif
	@endforeach
</body>
</html>

