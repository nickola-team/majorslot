<!-- 라이브카지노 -->
<div id="casino_1" class="popup_style04 popup_none">
		<div class="popup_wrap_1360">   
			<div class="close_box"><a href="#" class="casino_1_close"><img src="/frontend/kdior/images/popup_close.png?v=202301301150"></a></div>
			<div class="popupbox">
				<div class="title1"><img src="/frontend/kdior/images/title01.png?v=202301301150"/></div><!-- 타이틀 -->
				<div class="game">
					<ul  class="categorylist">
						@foreach($categories AS $index=>$category)
						@if ($category->type =='live' && $category->provider != 'kuza')
						<li>
							<a href="#" onMouseOver="show_over(this);" onMouseOut="show_out(this);" 
							@auth 
								@if ($category->status == 0)
								onclick="alert('점검중입니다');"
								@else
								onclick="casinoGameStart('{{$category->href}}');"
								@endif
							@endif
							>
							<img src="/frontend/kdior/images/game/{{strtoupper($category->title)}}.png">
							<img src="/frontend/kdior/images/game/{{strtoupper($category->title)}}.png" class="mouseover3 casino_1_close etc_pop2_open" style="display:none;">
							</a>
						</li>
						@endif
						@endforeach
					</ul>
				</div>    
			</div>
		</div>
	</div>

	<!-- 호텔 라이브카지노 -->
	<div id="casino_2" class="popup_style04 popup_none">
		<div class="popup_wrap_1360">   
			<div class="close_box"><a href="#" class="casino_2_close"><img src="/frontend/kdior/images/popup_close.png?v=202301301150"></a></div>
			<div class="popupbox">
				<div class="title1"><img src="/frontend/kdior/images/title04.png?v=202301301150"></div><!-- 타이틀 -->
				<div class="game">
					<ul   class="gamelist">

						@foreach($hotgames AS $hotgame)
						<li>
							<a href="#" onMouseOver="show_over(this);" onMouseOut="show_out(this);" 
							@auth 
								onclick="startGameByProvider(null, '{{$hotgame['name']}}');"
							@endif
							>
							<img src="/frontend/Default/ico/{{$hotgame['name']}}.jpg">
							<img src="/frontend/Default/ico/{{$hotgame['name']}}.jpg" class="mouseover3 casino_2_close etc_pop2_open" style="display:none;">
							<br>{{$hotgame['title']}}
							</a>
						</li>
						@endforeach

					</ul>
				</div>    
			</div>
		</div>
	</div>



	<!-- 슬롯게임 -->
	<div id="casino_3" class="popup_style04 popup_none">
		<div class="popup_wrap_1360">   
			<div class="close_box"><a href="#" class="casino_3_close"><img src="/frontend/kdior/images/popup_close.png?v=202301301150"></a></div>
			<div class="popupbox">
				<div class="title1"><img src="/frontend/kdior/images/title03.png?v=202301301150"></div><!-- 타이틀 -->
				<div class="game">
					<ul class="categorylist">
						@foreach($categories AS $index=>$category)
						@if ($category->type =='slot')
						<li>
							<a href="#"  onMouseOver="show_over(this);" onMouseOut="show_out(this);" 
							@auth
								@if ($category->status == 0)
								onclick="alert('점검중입니다');"
								@else
								class="casino_3_close etc_pop1_open"
								onclick="slotGame('{{$category->href}}', '{{$category->trans?$category->trans->trans_title:$category->title}}');"
								@endif
							@else
								class="casino_3_close etc_pop2_open" 
							@endif
							>
							<img src="/frontend/kdior/images/game/{{strtoupper($category->title)}}.png">
							<img src="/frontend/kdior/images/game/{{strtoupper($category->title)}}.png" class="mouseover3 casino_1_close etc_pop2_open" style="display:none;">
							</a>
						</li>
						@endif
						@endforeach

					</ul>
				</div>    
			</div>
		</div>
	</div>

	<!-- 미니게임 -->
	<div id="casino_4" class="popup_style04 popup_none">
		<div class="popup_wrap_1360">   
			<div class="close_box"><a href="#" class="casino_4_close"><img src="/frontend/kdior/images/popup_close.png?v=202301301150"></a></div>
			<div class="popupbox">
				
				<div class="title1"><img src="/frontend/kdior/images/title05.png?v=202301301150"></div><!-- 타이틀 -->
				<div class="game">
					<ul class="gamelist">
						{{$status = 0}}
						@foreach($categories AS $index=>$category)
							@if ($category->type =='pball')
								@if ($category->status == 0)
									{{$status = 1}}
								@else
									{{$status = 2}}
								@endif
							@endif
						@endforeach

						@foreach($pbgames AS $pbgame)
						<li>
							<a href="#" onMouseOver="show_over(this);" onMouseOut="show_out(this);" 
							@auth 
								@switch($status)
									@case(0)
										onclick="alert('지원하지 않는 게임입니다.');"
										@break
									@case(1)
										onclick="alert('점검중입니다');"
										@break
									@case(2)
										onclick="startGameByProvider(null, '{{$pbgame['name']}}');"
										@break
									@default
								@endswitch
							@endif
							>
							<img src="/frontend/Default/ico/{{$pbgame['name']}}.jpg">
							<img src="/frontend/Default/ico/{{$pbgame['name']}}.jpg" class="mouseover3 casino_4_close etc_pop2_open" style="display:none;">
							<br>{{$pbgame['title']}}
							</a>
						</li>
						@endforeach
					</ul>
				</div>    
			</div>
		</div>
	</div>

