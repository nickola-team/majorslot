"use strict";

function getContentDocument() {
    var objFrame = window.document.getElementById("main_frame");
    return (objFrame.contentDocument) ? objFrame.contentDocument : objFrame.contentWindow.document;
}

function gameJoin() {
    parent.SFrame.location.href = "/api/ximax/xilink.asp?nowtime=" + (new Date()).getTime();
}



function chkLogin() {

    //----------------------------------------------------------------------------------
    $.ajax({
        url: "/include/chkSession.asp",
        type: "POST",
        success: function(json) {
            //console.log(json);
            var content = $.parseJSON(json);

            var ret = content.islogin;

            if (ret == "false") {
                location.href = "/login/forcelogout.asp";
                // swal({
                // 	title: '주의',
                // 	text: "다른곳에서 로그인 하셨습니다.",
                // 	type: 'error'
                // }, function() {
                // 	location.href = "/login/forcelogout.asp";
                // });
                return;
            }
            setTimeout(chkLogin, 2000);
        }
    });
    //----------------------------------------------------------------------------------

}

function reGetMoney() {
    var tmpdate = new Date().getTime();
    if (parseInt($("#is_sign_in").val()) == 1) {
        //$(".player-balance").text("로딩중···");
        $.ajax({
            url: "/api/ximax/mymoney.asp",
            type: "GET",
            data: { userid: xigame_id, nowtime: tmpdate },
            dataType: "html",
            success: function(data) {
                $("#ximoney").val(data);
                $("#wt_curmoney").val(data);
                dispMoney();
            }
        });

        $.ajax({
            url: "/api/point.asp",
            type: "GET",
            dataType: "html",
            success: function(data) {
                $("#point").val(data);
                dispPoint();
            }
        });
    }

}

function dispMoney() {
    var tmp = $.number($("#ximoney").val(), 0);
    $(".player-balance").text(tmp);
}

function dispPoint() {
    var tmp = $.number($("#point").val(), 0);
    $(".player-point").text(tmp);
}

function chkState() {
    //----------------------------------------------------------------------------------
    $.ajax({
        url: "/include/chkstate.asp",
        type: "POST",
        success: function(json) {
            //console.log(json);
            var content = $.parseJSON(json);

            $('.mess-count').html(content.memocnt);
            if (content.memocnt > 0) {
                playAudio();
            }
        }
    });
    //----------------------------------------------------------------------------------
}

function updateState() {
    var cktimer = setInterval(chkState, 6000); // 3초에 한번씩 받아온다.
}

function playAudio() {
    if (document.getElementById('playAudio')) {
        document.getElementById('playAudio').play();
    } else {
        $('#alarmSound').html('	<audio src="/sound/message.wav" autoplay id="playAudio"></audio>');
    }
}

function playAudio2() {
    if (document.getElementById('playAudio2')) {
        document.getElementById('playAudio2').play();
    } else {
        $('#alarmSound').html('	<audio src="/sound/alert.wav" autoplay id="playAudio2"></audio>');
    }
}

function selectGame() {
    getContentDocument().location.href = "/api/usermoney.asp";
}



function alert_ok_reload(str, url) {
    swal({
            title: 'sự thành công',
            text: str,
            type: 'success'
        },
        function() { top.location.href = url; });
}

function alert_error_reload(str, url) {
    swal({
            title: 'sự thất bại',
            text: str,
            type: 'error'
        },
        function() { top.location.href = url; });
}

function alert_ok(str) {
    swal({ title: 'sự thành công', text: str, type: 'success' });
}

function alert_error(str) {
    swal({ title: 'sự thất bại', text: str, type: 'error' });
}



function addAmount(type, amount) {
    var obj;
    var tmp_money;

    if (type == 1) {
        obj = $("#depositAmount");
    } else if (type == 2) {
        obj = $("#withdrawalAmount");
    } else if (type == 3) {
        obj = $("#moveAmount");
    } else if (type == 4) {
        obj = $("#pointAmount");
    }
    tmp_money = Number(obj.val());
    tmp_money += 10000 * amount;
    obj.val(tmp_money);
    obj.focus();
}

function resetAmount(type) {
    if (type == 1) {
        $("#depositAmount").val("");
    } else if (type == 2) {
        $("#withdrawalAmount").val("");
    } else if (type == 3) {
        $("#moveAmount").val("");
    } else {
        $("#pointAmount").val("");
    }
}