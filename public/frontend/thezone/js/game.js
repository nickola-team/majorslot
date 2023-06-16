function goStartGame(gametype) {
    noService();
    return;
    if (gametype == "micro") {
        underConstruction();
        //gameStart("M");
    } else {
        noService();
    }
}

function gameStart(strName) {
    var width = screen.width;
    var man = "";
    var win;

    if (loginYN == "N") {
        alert("로그인 후 사용가능합니다");
        return;
    }

    noService();
    return;

    if ("M" == strName) {
        man = "/api/m/mlink.asp";
    } else {
        noService();
        return;
    }

    if (width < 1024) {
        win = window.open(man, 'Gaming', 'width=800,height=600,resizable=yes,scrollbars=o,status=0,toolbar=0,screenX=150,screenY=100');
    } else {
        win = window.open(man, 'Gaming', 'width=1000,height=690,resizable=no,scrollbars=0,status=0,toolbar=0,screenX=150,screenY=100');
    }
    if (win != undefined) {
        win.focus();
    }
}

function noService() {
    alert("서비스 준비중입니다");
}

function underConstruction() {
    alert("서비스 점검중입니다");
}



function lauchXiLive(gid, isConstruct, pv) {
    // if(isConstruct == 1) {
    // 	underConstruction();
    // 	return;
    // }

    var gurl = "/api/ximax/xirun.asp?gid=" + gid + "&gtype=" + gid + "&pv=" + pv + "&nowtime=" + (new Date()).getTime();
    var game_main = window.open(gurl, 'game_main', 'width=1350, height=900, status=no, scrollbars=yes, resizable=yes');
}

function lauchXiGame(gid, gtype, pv) {
    var gurl = "/api/ximax/xirun.asp?gid=" + gid + "&gtype=" + gtype + "&pv=" + pv + "&nowtime=" + (new Date()).getTime();
    var game_main = window.open(gurl, 'game_main', 'width=1350, height=900, status=no, scrollbars=yes, resizable=yes');
}

function goSlot(title, category, isConst) {
    //if( isConst == "1" ) { underConstruction(); return; }
    if (loginYN == "Y") {
    
        var formData = new FormData();
        formData.append("_token", $("#_token").val());
        formData.append("category", category);
    
        $.ajax({
            type: "POST",
            url: "/api/getgamelist",
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            success: function (data) {
                if (data.error) {
                    alert(data.msg);
                    if (data.code == "001") {
                        location.reload();
                    }
                    return;
                }
    
                var strHtml = `
                
                    <div class="subcontent">
                	<div id="sub_box">
                        <div id="sub_title"><h2>${title}</h2></div>
                        <div id="data_box">`;

                if (data.games.length > 0) {
                    for (var i = 0; i < data.games.length; i++) {
                        if (data.games[i].provider)
                        {
                            strHtml += `
                            <div class="gamelist" onClick="startGameByProvider('${data.games[i].provider}', '${data.games[i].gamecode}');">`;
                            if (data.games[i].icon)
                            {
                                strHtml += `<img class="main-img" src="${data.games[i].icon}" alt="thumbnail">`;
                            }
                            else {
                                strHtml += `<img class="main-img" src="/frontend/Default/ico/${data.games[i].provider}/${data.games[i].gamecode}_${data.games[i].name}.jpg" alt="thumbnail">`;
                            }
                            strHtml += `
                                <div class="foot">
                                    <p style="word-break: break-all;font-size:18px;">${data.games[i].title}</p>
                                </div>
                            </div>`;
                        }
                        else
                        {
                            strHtml += `
                            <div class="gamelist" onClick="startGame('${data.games[i].name}');">
                                <img class="main-img" src="/frontend/Default/ico/${data.games[i].name}.jpg" alt="thumbnail">
                                <div class="foot">
                                    <p style="word-break: break-all;font-size:18px;">${data.games[i].title}</p>
                                </div>
                            </div>`;
                        }
                    }
                } else {
                    strHtml += '<div style="text-align: center;">';
                    strHtml += '<img src="/frontend/Major/major/images/coming_soon.png" style="margin-top: 150px;">';
                    strHtml += '</div>';
                }
                strHtml += '</div></div></div>';

// 	<link href="/frontend/thezone/css/Style_sub.css?4590" rel="stylesheet" type="text/css" />
// 	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
// 	<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
// 	<script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/2.2.0/js.cookie.js"></script>
// 	<script type="text/javascript" src="/js/common.js?v=1"></script>
// 	<script type="text/javascript" src="/js/game.js?v=2"></script>



                TINY.box.show({
                    html: strHtml,
                    width: 1800,
                    height: 800
                });
            },
            error: function (err, xhr) {
                alert(err.responseText);
            }
        });


    } else {
        showLoginAlert();
    }
}

function startGame(gamename) {
    startGameByProvider(null, gamename);
}

function startGameByProvider(provider, gamecode) {
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
            window.open(data.data.url, "game", "width=1280, height=720, left=100, top=50");
        }
        });
        
    }