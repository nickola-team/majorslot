
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>@yield('page-title') - {{ settings('app_name') }}</title>
    <meta name="viewport" content="width=device-width">
    <link rel="icon" href="/frontend/Default/img/favicon.png" >

	<link rel="stylesheet" href="/back/bower_components/bootstrap/dist/css/bootstrap.min.css">

	<link rel="stylesheet" href="/frontend/Major/major/css/common.css">
	<link rel="stylesheet" href="/frontend/Major/major/css/layout.css">
	<link rel="stylesheet" href="/frontend/Major/major/css/animations.css">
	<link rel="stylesheet" href="/frontend/Major/major/jq/slot/slot.css">
	<link rel="stylesheet" href="/frontend/Major/major/jq/slideshow2/slideshow.css">
	<link rel="stylesheet" href="/frontend/Major/css/sweetalert2.min.css">
	<link rel="stylesheet" href="/frontend/Major/css/odometer-theme-default.css">
	<link rel="stylesheet" href="/frontend/Major/fonts/fas/css/all.min.css">

	<script src="/frontend/Major/jquery-1.12.2.min.js"></script>
	<script src="/frontend/Major/js/jquery.cookie.js"></script>
	<script src="/frontend/Major/major/js/showid.js"></script>
	<script src="/frontend/Major/major/jq/popup/popup.js"></script>
	<script src="/frontend/Major/major/js/sk_opacity.js"></script>
	<script src="/frontend/Major/major/js/sk_tab.js"></script>
	<script src="/frontend/Major/major/js/sk_table.js"></script>

	<script src="/frontend/Major/major/js/common.js"></script>
	<script src="/frontend/Major/major/jq/slot/slot.js"></script>
<!--	<script src="/frontend/Major/major/jq/slideshow2/jquery.easing.1.3.js"></script>
	<script src="/frontend/Major/major/jq/slideshow2/slideshow.js"></script> !-->
	<script src="/frontend/Major/js/sweetalert2.min.js"></script>
	<script src="/frontend/Major/js/swiper.min.js"></script>
	<script src="/frontend/Major/js/paging.min.js"></script>
	<script src="/frontend/Major/js/odometer.min.js"></script>
	<script src="/frontend/Major/js/jquery.animateNumber.min.js"></script>
	<script src="/back/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
</head>

<body>
    <!-- 메인 -->
    <div id="wrap">
        <div id="header_wrap">
            <div class="header_box">
                <div class="logo"><a href="{{url('/') }}"><img src="/frontend/Major/major/images/logo.png"></a></div>
                <div class="gnb">
                    <ul>
						@if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
							<li><a href="#" onMouseOver="show_over(this);" onMouseOut="show_out(this);" onclick="openMenu('deposit');" class="menu_pop_open"><img width="120" src="/frontend/Major/major/images/gnb1.png"><img width="120" src="/frontend/Major/major/images/gnb1over.png" style="display:none;"></a></li>
							<li><a href="#" onMouseOver="show_over(this);" onMouseOut="show_out(this);" onclick="openMenu('withdraw');" class="menu_pop_open"><img width="120" src="/frontend/Major/major/images/gnb2.png"><img width="120" src="/frontend/Major/major/images/gnb2over.png" style="display:none;"></a></li>
							{{-- <li><a href="#" onMouseOver="show_over(this);" onMouseOut="show_out(this);" onclick="openMenu('faq');" class="menu_pop_open"><img width="120" src="/frontend/Major/major/images/gnb3.png"><img width="120" src="/frontend/Major/major/images/gnb3over.png" style="display:none;"></a></li>
							<li><a href="#" onMouseOver="show_over(this);" onMouseOut="show_out(this);" onclick="openMenu('notice');" class="menu_pop_open"><img width="120" src="/frontend/Major/major/images/gnb4.png"><img width="120" src="/frontend/Major/major/images/gnb4over.png" style="display:none;"></a></li> --}}
						@else
							<li><a href="#" onMouseOver="show_over(this);" onMouseOut="show_out(this);" onclick="Swal.fire('로그인 하여 주세요.');return false;"><img width="120" src="/frontend/Major/major/images/gnb1.png"><img width="120" src="/frontend/Major/major/images/gnb1over.png" style="display:none;"></a></li>
                            <li><a href="#" onMouseOver="show_over(this);" onMouseOut="show_out(this);" onclick="Swal.fire('로그인 하여 주세요.');return false;"><img width="120" src="/frontend/Major/major/images/gnb2.png"><img width="120" src="/frontend/Major/major/images/gnb2over.png" style="display:none;"></a></li>
                            {{-- <li><a href="#" onMouseOver="show_over(this);" onMouseOut="show_out(this);" onclick="Swal.fire('로그인 하여 주세요.');return false;"><img width="120" src="/frontend/Major/major/images/gnb3.png"><img width="120" src="/frontend/Major/major/images/gnb3over.png" style="display:none;"></a></li>
                            <li><a href="#" onMouseOver="show_over(this);" onMouseOut="show_out(this);" onclick="Swal.fire('로그인 하여 주세요.');return false;"><img width="120" src="/frontend/Major/major/images/gnb4.png"><img width="120" src="/frontend/Major/major/images/gnb4over.png" style="display:none;"></a></li> --}}
						@endif
					</ul>
                </div>
            </div>
        </div>

		@yield('content')

        <div id="footer">
            <img src="/frontend/Major/major/images/footer.png">
        </div>
    </div>

	@yield('popup')

</body>
</html>