
	var $Banner;
	var	$Wrap;
	var $RightBoard;
	var $RightBoard2;

	$(document).ready(function(e){
		
		$Wrap=$("#wrap");
		$Banner=$(".top_banner_wrap");
		$RightBoard=$(".right_board_wrap");
		$RightBoard2=$(".right_board_wrap2");
		$(".banner_up").bind("click",onMoving2);
		$(".banner_down").bind("click",onMoving1);
		$(".right_open").bind("click",onMoving3);
		$(".right_close").bind("click",onMoving4);
		$(".right_open2").bind("click",onMoving5);
		$(".right_close2").bind("click",onMoving6);
	
		
	})
		


		

		

		// 하단에서 나오는 메뉴
		function onMoving1(){
			$Banner.stop();
			$Banner.animate({top:0});
			$Wrap.animate({top:500});
			$(".banner_up").show();
			$(".banner_down").hide();

		}


		function onMoving2(){
			$Banner.stop();
			$Banner.animate({top:-500});
			$Wrap.animate({top:0});
			$(".banner_up").hide();
			$(".banner_down").show();
		}


		// 하단에서 나오는 메뉴
		function onMoving3(){
			$RightBoard.stop();
			$RightBoard.animate({right:0});
			$(".right_close").show();
			$(".right_open").hide();

		}


		function onMoving4(){
			$RightBoard.stop();
			$RightBoard.animate({right:-400});
			$(".right_close").hide();
			$(".right_open").show();
		}

		// 하단에서 나오는 메뉴
		function onMoving5(){
			$RightBoard2.stop();
			$RightBoard2.animate({right:0});
			$(".right_close2").show();
			$(".right_open2").hide();

		}


		function onMoving6(){
			$RightBoard2.stop();
			$RightBoard2.animate({right:-400});
			$(".right_close2").hide();
			$(".right_open2").show();
		}