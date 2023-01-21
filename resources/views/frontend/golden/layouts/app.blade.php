<!DOCTYPE html>
<html lang="ko">
	<head>
		<title>GOLDENBULL</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		
		<link rel="shortcut icon" href="/frontend/golden/images/logo/favicon.png" type="image/png">
		<link rel="stylesheet" href="/frontend/golden/css/bootstrap.min.css">
		<link rel="stylesheet" href="/frontend/golden/css/bootstrap-icons.css">
		<link rel="stylesheet" href="/frontend/golden/css/style.css">
		<link rel="stylesheet" href="/frontend/golden/css/aria.css">
		<link rel="stylesheet" href="/frontend/golden/css/alertify.min.css">
		<link rel="stylesheet" href="/frontend/golden/css/sweetalert2.css" media="all" />
		<link rel="stylesheet" href="/frontend/golden/css/themes/default.min.css">
		<link rel="stylesheet" href="/frontend/golden/css/contents.css">
		<link rel="stylesheet" href="/frontend/golden/css/mobile.css">

		<script type="text/javascript" src="/frontend/golden/js/sweetalert2.js"></script>
		
		<script type="text/javascript" src="//js.pusher.com/4.1/pusher.min.js"></script>
		
		<script type="text/javascript" src="/frontend/golden/js/jquery-1.11.3.min.js"></script>
		<script type="text/javascript" src="/frontend/golden/js/jquery.form.min.js"></script>
		<script type="text/javascript" src="/frontend/golden/js/b8fedc75a0.js"></script>
		<script type="text/javascript" src="/frontend/golden/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="/frontend/golden/js/jquery.vticker.min.js"></script>
		<script type="text/javascript" src="/frontend/golden/js/jquery.number.min.js"></script>
		<script type="text/javascript" src="/frontend/golden/js/parsley.remote.min.js"></script>
		<script type="text/javascript" src="/frontend/golden/plugins/tinymce/tinymce.min.js"></script>
		<script type="text/javascript" src="/frontend/golden/js/pagination.js"></script>
		
		<script type="text/javascript" src="/frontend/golden/js/lazyload.min.js"></script>
		<script type="text/javascript" src="/frontend/golden/js/alertify.min.js"></script>
		<script type="text/javascript" src="/frontend/golden/js/custom.js"></script>
		<script type="text/javascript" src="/frontend/golden/js/aria.min.js"></script>
        <script type="text/javascript" src="/frontend/golden/js/action.js?v=1.3"></script>
	</head>

	<body>
        <input type="hidden" value="<?= csrf_token() ?>" name="_token" id="_token">

		<div class='wrapper_loading hidden'>
			<img src="/frontend/golden/images/loading.gif" class="wrapper_loading_img" alt="loading">
		</div>

		<div class="wrapper">
			@include('frontend.golden.layouts.partials.header')

			@yield('content')

			@include('frontend.golden.layouts.partials.footer')

			<button class="scroll-top w-ba"><i class="fas fa-caret-up"></i></button>

			@if(Auth::check())
				@include('frontend.golden.modals.deposit')
				@include('frontend.golden.modals.event')
				@include('frontend.golden.modals.notice')
				@include('frontend.golden.modals.profile')
				@include('frontend.golden.modals.withdraw')
			@else
				@include('frontend.golden.modals.login')
				@include('frontend.golden.modals.join')
			@endif
		</div>
		
		@if (count($noticelist) > 0)
        <div class="pop01_popup1 draggable02" id="notification" style="position: absolute; top: 0px; left: 0px; z-index: 1000;">
          <div class="pop01_popup_wrap">
              <div class="pop01_popup_btn_wrap">
                  <ul>
                      <li><a href="#" onclick="closeNotification(false);"><span class="pop01_popup_btn">30분동안 창을 열지 않음</span></a></li>
                      <li><a href="#" onclick="closeNotification(true);"><span class="pop01_popup_btn">닫기 X</span></a></li>
                  </ul>
              </div>
              <div class="pop01_popup_box">
                  <div class="pop01_popup_text" style="padding: 30px; width: 500px;">
                    <p>공지사항</p>
                    <span class="pop01_popup_font1" style="border-bottom: 2px solid rgb(255, 255, 255); margin-bottom: 15px;"></span>
                    <span class="pop01_popup_font2">
                          <div>
                            @foreach ($noticelist AS $popup)
                              <p style="color: chocolate;">{{$popup->title}}</p>
                              <?php echo $popup->content ?>
                              <br>
                            @endforeach
                          </div>
                    </span>
                  </div>
              </div>
          </div>
        </div>
		@endif

	</body>

	<script type='text/javascript'>
		let msgCount = {{ $unreadmsg }};

		function closeNotification(onlyOnce) {
			if (onlyOnce) {
				
			}
			else {
				localStorage.setItem("hide_notification", Date.now());
			}

			$("#notification").hide();
		}

		$(document).ready(function() {
			var prevTime = localStorage.getItem("hide_notification");
			if (prevTime && Date.now() - prevTime < 0.5 * 3600 * 1000) {
				$("#notification").hide();
			}

			if (msgCount > 0) {
				showMsg(`새 쪽지가 도착했습니다. (${msgCount})`, "info");
			}
		});

	</script>

	@stack('js')

</html>