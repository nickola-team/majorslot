"use strict";

function loginSubmit(frm) {
    if (frm.userid.value == "아이디" || frm.userid.value == "") {
        alert("로그인 아이디를 입력해 주세요");
        frm.userid.focus();
        return;
    }
    if (frm.password.value == "******" || frm.password.value == "") {
        alert("비밀번호를 입력해 주세요");
        frm.password.focus();
        return;
    }

    frm.action = "/login/login.asp";
    frm.submit();
}

function getContentDocument() {
    var objFrame = window.document.getElementById("iframe");
    return (objFrame.contentDocument) ? objFrame.contentDocument : objFrame.contentWindow.document;
}

function getContentDocument2() {
    var objFrame = window.document.getElementById("iframe2");
    return (objFrame.contentDocument) ? objFrame.contentDocument : objFrame.contentWindow.document;
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
                //alert("관리자에 의해 로그아웃 되셨습니다.");
                location.href = "/login/forcelogout.asp";
                return;
            }
            setTimeout(chkLogin, 2000);
        }
    });
    //----------------------------------------------------------------------------------

}

function gameJoin() {
    top.SFrame.location.href = "/api/ximax/xilink.asp?nowtime=" + (new Date()).getTime();
}

function keepSession() {
    top.HFrame.location.href = "/session.asp";
}

function reGetMoney() {
    getContentDocument2().location.href = "/api/ximax/mymoney.asp?nowtime=" + (new Date()).getTime();
}

function updateMemo() {
    var cktimer = setInterval(function() {
        //----------------------------------------------------------------------------------
        $.ajax({
            url: "/include/chkMemo.asp",
            type: "POST",
            success: function(json) {
                //console.log(json);
                var content = $.parseJSON(json);

                $("#memocnt").html(content.memocnt);

                if (parseInt(content.memocnt) > 0) {
                    if ($('.tbox').css("display") == "none") {
                        goMemo();
                    }
                    //playAudio();
                }
            }
        });
        //----------------------------------------------------------------------------------
    }, 1000); // 3초에 한번씩 받아온다.
}

function playAudio() {
    if (document.getElementById('playAudio')) {
        document.getElementById('playAudio').play();
    } else {
        $('#alarmSound').html('	<audio src="/voice/memo.wav" autoplay id="playAudio"></audio>');
    }
}

function selectGame() {
    getContentDocument().location.href = "/api/usermoney.asp";
}



$(document).ready(function() {

    //	$('.counter').counter();
    //	$('.counter_slot').counter();

    var slider = $('.bxslider').bxSlider({
        mode: 'horizontal',
        captions: true,
        controls: false,
        auto: true,
        autoHover: true,
        pause: 4500
    });
    $('.bx-pager-item a').click(function(e) {
        var i = $(this).data('slide-index');
        slider.goToSlide(i);
        slider.stopAuto();
        restart = setTimeout(function() {
            slider.startAuto();
        }, 500);
        return false;
    });
    $(".gamebtn_cont").tabs();
    //$( "#slot" ).tabs();

    $('.game_nav li a').click(function() {
        var tab_name = $(this).parent().attr('name');
        if (tab_name == "slot") {
            //$( ".gamebtn_cont" ).tabs("option", "active", 0);
            $(".gamebtn_wrap").addClass("slot");
        } else {
            $(".gamebtn_wrap").removeClass("slot");
        }
    });
    $('.event_popup .e_btn').click(function() {
        $(this).parent().fadeOut();
    });
    $('.btn_list > ul > li').hover(
        function() {
            $(this).addClass("active");
        },
        function() {
            $(this).removeClass("active");
        }
    );
    $('.out_now > div').vTicker('init', {
        speed: 400,
        pause: 1500,
        showItems: 6
    });
    $('.out_rank > div').vTicker('init', {
        speed: 400,
        pause: 4000,
        showItems: 6
    });


    //window.setTimeout("check_out_event();", 5000);	//5초마다 출금이벤트 체크
});