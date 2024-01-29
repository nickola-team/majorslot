<html>
<head>
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
</head>
	<body style="margin:0;">
		<script src="/games/WinPowerBallGP/js/jquery.min.js"></script>
		<iframe id="iframe" src="{{$videourl}}" width="{{$width??1200}}" height="{{$height??800}}" scrolling="no" frameborder="0" style="overflow:hidden;" allow="autoplay;"></iframe>
		<script>
			window.onload=function(){
			var $photoBox = $('#iframe'), adjust = 0, boxWidth = {{$width??1200}} + adjust;			
			function boxScale(){   		    
				var currentWidth = document.documentElement.clientWidth;   		    
					if(currentWidth<boxWidth)
					{
						$photoBox.css({
							'-webkit-transform':'scale('+(currentWidth/boxWidth)+')',
							'-ms-transform':'scale('+(currentWidth/boxWidth)+')',
							transform:'scale('+(currentWidth/boxWidth)+')',		
							'-webkit-transformOrigin':'0 0',
							'-ms-transformOrigin':'0 0',
							transformOrigin:'0 0'		
						});
				  } else {		  
			      $photoBox.css({
			        '-webkit-transform':'scale(1)',
			        '-ms-transform':'scale(1)',
			        transform:'scale(1)'
			      });
				  }
				}			
				
				boxScale();		
				$(window).on('resize', boxScale);		
			}
		</script>
	</body>
</html>