
<!doctype html>
<html>
<head>
    	<meta charset="UTF-8" />
        <title>Instructions</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <meta http-equiv="Content-Script-Type" content="text/javascript" />
        <meta http-equiv="Content-Style-Type" content="text/css" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="robots" content="all" />
        
            <!--遊戲搜尋書簽-->
        <meta name="description" content="Instructions" />
        <meta name="keywords" content="WaterMargin，博弈，Instructions，遊戲說明，" />
        <meta property="og:title" content="Instructions" />
        <meta property="og:description" content="Instructions" />
            <!--遊戲搜尋書簽-->
            
        <meta property="og:url" content="" />
        <meta property="og:site_name" content="Instructions" />
        <meta property="fb:app_id" content="zengshen" />
        <meta name="Copyright" content="">
        <link rel="shortcut icon" href="img/16_Icon.png">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=640,user-scalable=1">
    
        <link rel="stylesheet" type="text/css" href="help/DuoFuDuoCaiDancingDrum/game_type/css/Instructions.css" />
        <link rel="stylesheet" type="text/css" href="help/DuoFuDuoCaiDancingDrum/game_type/css/magnific-popup.css"/>
        

<!--    匯入jQuery    -->
    <script src="../../JS/jquery-2.0.0.min.js"></script>
   <!-- <script src="JS/jquery.mousewheel.min.js"></script>
    <script type="text/javascript" src="JS/jquery.magnific-popup.min.js"></script>
    <script type="text/javascript" src="JS/popup.js"></script>-->



	<style>

			.Column_00{ width: 87%;}
			.Column_Content_00{ width: 78.2%;}

		@media only screen and (min-width: 769px) and (max-width: 850px) {

			.Column_00{ width: 87%;}
			.Column_Content_00{ width:72.8%; height: 169px;}
			.IconIMG_00{
					width: 60%;
					margin: 0 auto;
					display: block;
					}	
			.IconIMG_BG{
				width: 45%;
			}

		}

		@media only screen and (min-width: 641px) and (max-width: 768px) {
			.Column_00{ width: 77%;}
			.Column_Content_00{ width:100%;}
			.IconIMG_00{
					width: 50%;
					float: none;
					margin: 0 auto;
					display: block;
					}			

		}


		@media only screen and (min-width: 265px) and (max-width: 640px) {
			.Column_Content_00{ width:100%;}
			.IconIMG_00{
					width: 50%;
					float: none;
					margin: 0 auto;
					display: block;
					}				
		}
	</style>      
</head>
<body>

<div class="container">

	<h1><img src=help/DuoFuDuoCaiDancingDrum/game_type/img/KR/KR_T0.png alt="PAYTABLE" ></h1>
	<hr class="MainTarget"></hr>

	
<!--==============================Symbol Payout Valves===================================-->	
	<div class="H2BG_Subtitle">
		<div class="H2BG">
			<h2><img src=help/DuoFuDuoCaiDancingDrum/game_type/img/KR/KR_T1.png alt="SYMBOL PAYOUT VALVES" ></h2>
		</div>
	</div>
	
	<!--符號相關賠率-->
	<div id="Description">
		<!--Connection00-->
		<div class="Column Column_00" >
			<div class="IconIMG_BG"><img class="IconIMG IconIMG_00" src="help/DuoFuDuoCaiDancingDrum/game_type/049_DancingDrum/img/Symbol/0.png" alt="Connection00" width="100%"></div>
			<div class="Column_Line"></div>
			<div class="Column_Content Column_Content_00">
			<p>Can replace any symbol.</p><br><p>Only appear in 2,3,4  wheel.</p><br>
			</div>
        </div>
        
        @foreach($Paytable AS $index=>$record)
            <div class="Column">
                <img class="IconIMG" src={{"help/DuoFuDuoCaiDancingDrum/game_type/049_DancingDrum/img/Symbol/".($index + 1).".png"}} alt="Connection01" width="100%">
                <div class="Column_Line"></div>
                <div class="Column_Content">
                                <p>5&nbsp;&nbsp;&nbsp;&nbsp;{{$record[4]}}</p>
                    <p>4&nbsp;&nbsp;&nbsp;&nbsp;{{$record[3]}}</p>				
                    <p>3&nbsp;&nbsp;&nbsp;&nbsp;{{$record[2]}}</p>						
                </div>
            </div>
        @endforeach

	</div><!--/Description-->
	
	
	
<!--==============================Special Symbol-Only for Free Game===================================-->

	
	<div class="H2BG_Subtitle">
		<div class="H2BG">
			<h2><img src=help/DuoFuDuoCaiDancingDrum/game_type/img/KR/KR_T2_02.png alt="Special Symbol-Only for Free Game" ></h2>
		</div>
	</div>

	<!--特殊符號-免費遊戲專用-->
	<div id="Description">
	
		<!--CombinationA-->
		<div class="Column Column_00" >
			<div class="IconIMG_BG"><img class="IconIMG IconIMG_00" src="help/DuoFuDuoCaiDancingDrum/game_type/049_DancingDrum/img/Symbol/12.png" alt="Connection00" width="100%"></div>
			<div class="Column_Line"></div>
			<div class="Column_Content Column_Content_00">
			<p>5&nbsp;&nbsp;X50+1Free Game automatically selecting </p><br><p>4&nbsp;&nbsp;X10+1Free Game automatically selecting </p><br><p>3&nbsp;&nbsp;X5+1Free Game automatically selecting </p><br>			</div>
		</div>
		
		
	</div><!--/Description-->




<!--==============================FreeGame===================================-->	

	<div class="H2BG_Subtitle">
		<div class="H2BG">
			<h2><img src=help/DuoFuDuoCaiDancingDrum/game_type/img/KR/KR_T3_02.png alt="FEATURED-BONUS GAME" ></h2>
		</div>
	</div>

	<div id="Introduce">
		<!--Free Game-->
		<img class="ContentIMG" src="help/DuoFuDuoCaiDancingDrum/game_type/049_DancingDrum/img/FreeGame/FreeGame_01.png" alt="ContentIMG" width="100%">
		<div class="Content">
			<p>Free Game</p>

			<p>Room selection (automatic selection):</p><br>			<p>Before entering the free game, it will enter a page for automatically selecting the room. This page will automatically select one of three, and the automatic selection items are(15 free games 243 ways, 10 free games 1024 ways, 5 free games 3125 ways ).</p><br>
		</div>
		
		<div class="Content">
			<p style="font-size: 1.8em; text-align: center; color: #ffde31;">3X5</p>
			<img class="ContentIMG" src=help/DuoFuDuoCaiDancingDrum/game_type/049_DancingDrum/img/FreeGame/FreeGame_04_3x5_KR.png alt="ContentIMG" width="100%">
			<p style="font-size: 1.8em; text-align: center; color: #ffde31;">4X5</p>
			<img class="ContentIMG" src=help/DuoFuDuoCaiDancingDrum/game_type/049_DancingDrum/img/FreeGame/FreeGame_02_4x5_KR.png alt="ContentIMG" width="100%">
			<p style="font-size: 1.8em; text-align: center; color: #ffde31;">5X5</p>
			<img class="ContentIMG" src=help/DuoFuDuoCaiDancingDrum/game_type/049_DancingDrum/img/FreeGame/FreeGame_03_5x5_KR.png alt="ContentIMG" width="100%">
		</div>
		<div class="Content">

			<p>Free Game</p><br><p>Game Rule:When you in the Free Game,it will not made any cost.And there are still available to win free games in this period.</p><br>		</div>
		
		<!--Free Game-->
		<div class="Column Column_00" >
			<div class="IconIMG_BG"><img class="IconIMG IconIMG_00" src="help/DuoFuDuoCaiDancingDrum/game_type/049_DancingDrum/img/Symbol/12.png" alt="Free Game" width="100%"></div>
			<div class="Column_Line"></div>
			<div class="Column_Content Column_Content_00">
			<p>5&nbsp;&nbsp;X50+3Free Game</p><br><p>4&nbsp;&nbsp;X10+3Free Game</p><br><p>3&nbsp;&nbsp;X5+3Free Game</p><br>			
			<p>2&nbsp;&nbsp;+3Free Game</p><br>
			</div>
		</div>

	</div><!--Featured-Bonus Game/Introduce-->




<!--==============================Jackpot===================================-->	

	<div class="H2BG_Subtitle">
		<div class="H2BG">
			<h2><img src=help/DuoFuDuoCaiDancingDrum/game_type/img/KR/KR_T4.png alt="JACKPOT"></h2>
		</div>
	</div>
	

	<div id="Introduce">
		
		<img class="ContentIMG" src="help/DuoFuDuoCaiDancingDrum/game_type/049_DancingDrum/img/Jackpot/Jackpot.png" alt="ContentIMG" width="100%">
		<div class="Content">
			<p>1.When a wild symbol appears, the player may be awarded the Bonus Game  12 coins will appear.</p><p>2.Touching the coins will reveal the symbol of an available Jackpot.</p><p>3.When 3 matching symbols have been revealed, the corresponding Jackpot will be awarded and the feature ends.</p><p>4.Jackpot's final score is based on server data.</p><p>5.All Jackpots displayed on the website and in the lobby are for information only. Any exact amount of Jackpot accumulation will be displayed in the game window.</p>		</div>
		
	</div><!--Jackpot/Introduce-->



<!--==============================Winning Bet Lines===================================-->	

	<div class="H2BG_Subtitle">
		<div class="H2BG">
			<h2><img src=help/DuoFuDuoCaiDancingDrum/game_type/img/KR/KR_T5.png alt="WINNING BET LINES" ></h2>
		</div>
	</div>

	<div id="Introduce">
		
		<img class="ContentIMG" src="help/DuoFuDuoCaiDancingDrum/game_type/049_DancingDrum/img/WinningBetLines/WinningBetLines_01.png" alt="ContentIMG" width="50%">
		<div class="Content">
			<p style="font-size: 1.8em; text-align: center; color: #ffde31;">2x2x1x1x3=12</p>
		</div>
		
		
		<img class="ContentIMG" src="help/DuoFuDuoCaiDancingDrum/game_type/049_DancingDrum/img/WinningBetLines/WinningBetLines_02.png" alt="ContentIMG" width="50%">
		<div class="Content">
			<p style="font-size: 1.8em; text-align: center; color: #ffde31;">2x2x1=4</p>
		</div>

		
		<div class="Content">
			<p>1.Only the highest win per bet line is paid.(If you win Bonus game or Free game,that will be the first to pay)</p><br><p>2.winning reads from left to right.</p><br><p>3.Malfunction voids all pays and plays.</p><br><p>4.For more information, see the Game Rules.</p><br><p>5.The coins payout values are based on bet level per line.</p><br><!-- 			<p>1.仅支付每条线上所中奖的最高金额。</p>
			<p>2.由左至右兑奖。</p>
			<p>3.游戏当发生故障时，此局下注与游戏将被舍去。</p>
			<p>4.更多资讯请参考游戏规则。</p>
			<p>5.中奖规则为每线下注金额乘中奖倍率。</p> -->
		</div>
		
	</div><!--Winning Bet Lines/Introduce-->














</div> <!--/container-->



<!-- =========Copyright========= -->	
		<!-- <h3>※游戏出现故障时，所有赔付和游戏都视为无效</h3> -->
		
		<!--
        <hr class="MainTarget"></hr>
        <div id="Copyright"></div>
        -->



</body>

</html>