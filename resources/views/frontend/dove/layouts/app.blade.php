
<?php
	$detect = new \Detection\MobileDetect();
?>
<!DOCTYPE html>
<html lang="ko" class="">
<head>
    <title>DOVE CASINO</title>
	<meta data-n-head="1" charset="utf-8">
	<meta data-n-head="1" data-hid="description" name="description" content="DOVE CASINO">
	<meta data-n-head="1" name="format-detection" content="telephone=no">
	<meta data-n-head="1" name="google" content="notranslate">
	<meta data-n-head="1" name="google" value="notranslate">
	<meta data-n-head="1" data-hid="charset" charset="utf-8">
	<meta data-n-head="1" data-hid="mobile-web-app-capable" name="mobile-web-app-capable" content="yes">
	<meta data-n-head="1" data-hid="apple-mobile-web-app-title" name="apple-mobile-web-app-title" content="nuxt-sportsbook">
	<meta data-n-head="1" data-hid="og:type" name="og:type" property="og:type" content="website">
	<meta data-n-head="1" data-hid="og:title" name="og:title" property="og:title" content="nuxt-sportsbook">
	<meta data-n-head="1" data-hid="og:site_name" name="og:site_name" property="og:site_name" content="nuxt-sportsbook">
	<link data-n-head="1" rel="icon" type="image/x-icon" href="/favicon.ico">
	<link data-n-head="1" data-hid="shortcut-icon" rel="shortcut icon" href="/favicon.ico">
	<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script><!-- sk_기본 -->
	<script async="" defer="" src="https://weblogs.io/matomo.js"></script>
	<link rel="stylesheet" href="/frontend/dove/css/default.css?v=202301301150">
	<link rel="stylesheet" href="/frontend/dove/css/custom.css?v=202301301150">
	<meta data-n-head="1" name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
</head>
<body class="">
	<div id="app">
	<!---->
		@if (empty($logo))
		<?php $logo = 'dove'; ?>
		@endif
		<div id="__layout">
			<div data-v-525011a5="" class="fill-height">
				<div data-v-525011a5="" class="fill-height">
					<div data-v-525011a5="" class="layout column">
						<div data-v-525011a5="" class="contents row" style="flex-direction: row;">
							<!----> 
							<div data-v-525011a5="" class="navigation scrollable-auto column">
							@include('frontend.dove.layouts.partials.nav')
							</div>
							<div data-v-525011a5="" class="content-wrap column">
								@include('frontend.dove.layouts.partials.header')
								@include('frontend.dove.layouts.partials.content')								
							</div>
						</div> <!----> 
						<div data-v-47d63ae2="" data-v-525011a5="" class="row" style="flex-direction: row;">
							<div class="row" style="width: 100%; flex-direction: row;"></div> <!---->
						</div> 
						<div data-v-525011a5="" class="slideout-panel clearfix">
					<!----> <div class="slideout-wrapper">
								<div class="slideout-panel-bg" style="z-index: 100;"  onclick="closeNav()"></div>
								<div class="slideout open-on-left drawer-slide" style="z-index: 100;">
								@include('frontend.dove.layouts.partials.nav')
								</div>
							</div>
						</div> 
						<div data-v-525011a5="" id="main-modal" class="vue-portal-target">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript" src="/frontend/dove/js/default.js"></script>
</body>
</html>
