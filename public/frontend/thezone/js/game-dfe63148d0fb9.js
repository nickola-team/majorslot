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

function goSlot(pv, isConst) {
    //if( isConst == "1" ) { underConstruction(); return; }
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: '/slots/games.asp?pv=' + pv,
            width: 955,
            height: 600
        });
    } else {
        showLoginAlert();
    }
}