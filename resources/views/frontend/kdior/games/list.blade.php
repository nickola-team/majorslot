@extends('frontend.kdior.layouts.app')
@section('page-title', $title)

@section('content')
<!-- 라이브카지노 -->
<div id="casino_1" class="popup_style04 popup_none">
		<div class="popup_wrap_1360">   
			<div class="close_box"><a href="#" class="casino_1_close"><img src="/frontend/kdior/images/popup_close.png?v=202301301150"></a></div>
			<div class="popupbox">
				<div class="title1"><img src="/frontend/kdior/images/title01.png?v=202301301150"/></div><!-- 타이틀 -->
				<div class="game">
					<ul style="width: 100%;display: flex;flex-wrap: wrap;align-items: center;justify-content: center;margin: 20px auto 10px auto;">

						<li><a href="#" onMouseOver="show_over(this);" onMouseOut="show_out(this);"><img src="/frontend/kdior/images/game/EVOLUTION.png?v=202301301150"><img src="/frontend/kdior/images/game/EVOLUTION.png?v=202301301150" class="mouseover3 casino_1_close etc_pop2_open" style="display:none;"></a></li>

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
				<div class="title1"><img src="/frontend/kdior/images/title02.png?v=202301301150"></div><!-- 타이틀 -->
				<div class="game">
					<ul  style="width: 100%;display: flex;flex-wrap: wrap;align-items: center;justify-content: center;margin: 20px auto 10px auto;">

						<li><a href="#" onMouseOver="show_over(this);" onMouseOut="show_out(this);"><img src="/frontend/kdior/images/game/BOTACASINO.png?v=202301301150"><img src="/frontend/kdior/images/game/BOTACASINO.png?v=202301301150" class="mouseover3 casino_2_close etc_pop2_open" style="display:none;"></a></li>

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
					<ul style="width: 100%;display: flex;flex-wrap: wrap;align-items: center;justify-content: center;margin: 20px auto 10px auto;">

						<li><a href="#" onMouseOver="show_over(this);" onMouseOut="show_out(this);"><img src="/frontend/kdior/images/game/PRAGMATICSLOT.png?v=202301301150"><img src="/frontend/kdior/images/game/PRAGMATICSLOT.png?v=202301301150" class="mouseover3 casino_3_close etc_pop2_open" style="display:none;"></a></li>

					</ul>
				</div>    
			</div>
		</div>
	</div>

    @stop