function casinoGameStart(category){
    if(is_login == 'N')
    {
        alert("로그인 후 사용하세요");
        return;
    }
	$.ajax({
	   type: "POST",
	   url: "/api/getgamelist",
	   data: {category : category},
	   cache: false,
	   async: true,
	   success: function (data, status) {
		   if (data.error) { 
			   alert(data.msg);
			   return;
		   }
		   if (data.games.length > 0) {
                var subdata = {
                    "result":true,
                    "ment":"",
                    "content":`<script>
                                    function play_game(game_type, code, lev){
                                        if ($("input#bet_agree").length){
                                            var is_agree = $("input#bet_agree").is(":checked");
                                            console.log(is_agree);
                                            if (!is_agree){
                                                alert("동의 후 게임진행이 가능합니다.");
                                                return false;
                                            }
                                        }
                                        startGameByProvider(game_type, code);  
                                    }
                                </script>
                                <div class="game-btn" onclick="play_game('${data.games[0].provider}', '${data.games[0].gamecode}')">
                                <div class="btn-container">
                                    <img src="/frontend/white01/static/images/comp/casino/evolution_baccarat_sicbo.jpg" class="main-img">
                                </div>
                                <div class="footer">
                                    <div class="name-text">
                                        입장하기
                                    </div>
                                </div>
                                <div class="play-btn">
                                    <span>게임시작</span>
                                    <div class="icon-panel">
                                        <i class="fas fa-play"></i>
                                    </div>
                                </div>
                                <div class="loading">
                                    <img>
                                </div>
                            </div>`,
                    "title":"에볼루션",
                    "warning_ment":'<p><span style="font-size:24px"><strong>회원님은 라이브카지노 이용시 <span style="color:#00ffff">최대배팅가능금액은&nbsp;300만원</span><span style="color:#ff0000"> /&nbsp;</span><span style="color:#00ffff">타이,페어는 30만원</span>까지 입니다.</strong></span></p><p><br><span style="font-size:24px"><strong>게임사들의 배팅리밋과 상관없이 자사의 운영방침이오니,&nbsp;꼭 배팅금액을 지켜주시기 바랍니다.</strong></span></p><p><span style="font-size:24px"><strong>당사는 바카라 게임만 라이선스 계약을 맺고 운영중입니다&nbsp;</strong></span></p><p><span style="font-size:24px"><strong>(블랙잭 ,룰렛 ,식보 ,용호,포커,메가볼 등등…<span style="color:#00ffff"> </span>)<span style="color:#ff0000">◁◀◁◀이용금지</span></strong></span></p><p><br><span style="font-size:24px"><strong>바카라를 제외한 게임사에서 제공하는 다른 게임을 이용시 불이익 받지 않도록 유의해주시길 바랍니다</strong></span></p><p><br><span style="font-size:24px"><strong><span style="color:#ff0000">바카라외 게임이용시 또는 배팅금액 초과해서 당첨시 해당건은 모두 취소처리 됩니다 (미적중시 결과처리)</span></strong></span></p><p><br><span style="font-size:24px"><strong><span style="color:#ff8c00">바카라 보너스 배팅시 (자동배팅포함) 최대 당첨금액= 500만원 까지만 지급//초과된 금액은 모두 회수처리&nbsp;</span></strong></span></p><p><span style="font-size:24px"><strong>위 내용을 숙지하셨으면&nbsp;아래 동의버튼 체크해주시고 게임진행해주시면 감사하겠습니다.</strong></span></p>'
                }
                if (subdata.warning_ment != "") {
                    var check = "<div class='form-check form-switch'>";
                    check += "<input class='' type='checkbox' id='bet_agree'/>";
                    check += "<label class='' for='bet_agree'>동의 후 게임진행</label>";
                    check += "</div>";

                    subdata.warning_ment = subdata.warning_ment + check;
                    $(".modal.gameModal").find(".information span.warning").html(subdata.warning_ment);
                } else {
                    $(".modal.gameModal").find(".information span.warning").empty();
                }
                $(".modal.gameModal").find("h1.title").text(subdata.title);
                $(".modal.gameModal").find(".gamelist-container").html(subdata.content);
                $(".modal.gameModal").removeClass("show-search");
                $(".modal.gameModal").addClass("hidden-search");
                $(".modal.gameModal").modal("show");
		   }
		   else
		   {
				alert('게임실행 오류');
		   }
		  }
	   });
 }

function startGameByProvider(provider, gamecode,max = false) {
	var formData = new FormData();
	formData.append("provider", provider);
	formData.append("gamecode", gamecode);
	$.ajax({
	type: "POST",
	url: "/api/getgamelink",
	data: formData,
	processData: false,
	contentType: false,
	cache: false,
	async: false,
	success: function (data) {
		if (data.error) {
			alert(data.msg);
			return;
		}
        window.open(data.data.url, "CASINO", "left=0,top=0,width=1700,height=900,resizable=no");
	// 	if (max)
    //    {
    //      window.open(data.data.url, "game", "width=" + screen.width + ", height=" + screen.height + ", left=100, top=50");
    //    }else{
    //      window.open(data.data.url, "game", "width=1280, height=720, left=100, top=50");
    //    }
	}
	});
	
 }