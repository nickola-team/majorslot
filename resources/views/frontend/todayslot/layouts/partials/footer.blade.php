<!-- <div id="wrap" class="mainboard" style="display: block;"> -->
	<div id="footerwrap" class="mainboard" style="display:block">
		<div class="footer_wrap">
			@if( $detect->isMobile() || $detect->isTablet() ) 
				<img src="/frontend/todayslot/images/f_partners.png" width="320">
				<br>
					<img src="/frontend/todayslot/images/{{$logo}}_logo.png" width="110">
				<br>Copyright ⓒ 2023 @yield('page-title') All rights reserved.
			@else
				<img src="/frontend/todayslot/images/f_partners.png">
				<br>
					<img src="/frontend/todayslot/images/{{$logo}}_logo.png">
				<br>Copyright ⓒ 2023 @yield('page-title') All rights reserved.
			@endif
				
		</div>
	</div>
<!-- </div> -->
