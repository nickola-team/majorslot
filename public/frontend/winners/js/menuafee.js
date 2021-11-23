"use strict";

function loginSubmit() {
    var formData = $("#loginForm").serialize();
    $.ajax({
        cache: false,
        url: "/login/login.asp",
        type: "POST",
        data: formData,
        success: function(data) {
            //console.log(data);

            if (data != "success") {
                $('.wrapper_loading').addClass('hidden');
                alert_error(data);
            } else {
                alert_ok_reload("로그인 하셨습니다.", "/");
            }
        }
    });
}

function goLogout() {
    location.href = "/logout";
}

function goJoin() {
    var formData = $("#joinForm").serialize();
    $.ajax({
        cache: false,
        url: "/user/join_proc.asp",
        type: "POST",
        data: formData,
        success: function(data) {
            console.log(data);

            if (data != "success") {
                $('.wrapper_loading').addClass('hidden');
                alert_error(data);
            } else {
                alert_ok_reload("회원 가입하셨습니다.", "/");
            }
        }
    });
}

function goDeposit() {
    var formData = $("#depoFrm").serialize();
    $.ajax({
        cache: false,
        url: "/api/deposit",
        type: "POST",
        data: formData,
        success: function(data) {
            //console.log(data);

            if (data.error) {
                $('.wrapper_loading').addClass('hidden');
                alert_error(data['msg']);
            } else {
                alert_ok_reload("충전 신청하셨습니다.", "/");
            }
        }
    });
}

function askAccount() {
    var money = $("#depoFrm #depositAmount").val();
    var accountname = $("#depoFrm #recommender").val();
    var _token = $('#_token').val();
    $.ajax({
        cache: false,
        url: "/api/depositAccount",
        type: "POST",
        data: { money: money, _token: _token, account:accountname, force : 1},
        success: function(data) {
            //console.log(data);
            if (data.error) {
                alert_error(data.msg);
                if (data.code == '001') {
                    location.reload(true);
                }
                else if (data.code == '002') {
                    $('#depoFrm #depositAmount').focus();
                }
                else if (data.code == '003') {
                    $('#depoFrm #recommender').focus();
                }
                return;
            }
            depositAccountRequested = true;
            $("#depoFrm #bankinfo").html(data.msg);
        }
    });
}

function goWithdraw() {
    var formData = $("#withFrm").serialize();
    $.ajax({
        cache: false,
        url: "/api/withdraw",
        type: "POST",
        data: formData,
        success: function(data) {
            //console.log(data);

            if (data.error) {
                $('.wrapper_loading').addClass('hidden');
                alert_error(data.msg);
            } else {
                alert_ok_reload("환전 신청하셨습니다.", "/");
            }
        }
    });
}

function goMove() {
    var formData = $("#moveFrm").serialize();
    $.ajax({
        cache: false,
        url: "/exchange/move_proc.asp",
        type: "POST",
        data: formData,
        success: function(data) {
            //console.log(data);

            if (data != "success") {
                $('.wrapper_loading').addClass('hidden');
                alert_error(data);
            } else {
                alert_ok_reload("머니이동 신청하셨습니다.", "/");
            }
        }
    });
}

function goMypage() {
    var formData = $("#passwordChangeForm").serialize();
    $.ajax({
        cache: false,
        url: "/api/updateinfo",
        type: "POST",
        data: formData,
        success: function(data) {
            //console.log(data);

            if (data.error) {
                $('.wrapper_loading').addClass('hidden');
                alert_error(data.msg);
            } else {
                alert_ok_reload("비밀번호를 변경하였습니다.", "/");
            }
        }
    });
}

//-- 쿠폰 --..
function useCoupon() {
    if (confirm("쿠폰을 사용하시겠습니까?")) {
        var formData = $("#couponFrm").serialize();
        $(".wrapper_loading").removeClass("hidden");
        $.ajax({
            url: "/exchange/coupon_proc.asp",
            type: "POST",
            data: formData,
            dataType: "text",
            success: function(data) {
                console.log(data);
                if (data != "success") {
                    $('.wrapper_loading').addClass('hidden');
                    alert_error(data);
                } else {
                    alert_ok_reload("쿠폰이 등록되었습니다.", "/");
                }
            },
            complete: function() { $(".wrapper_loading").addClass("hidden") }
        });
    }
}

//-- 보너스 --//
function usePoint() {
    if (confirm("포인트를 전환하시겠습니까?")) {
        var formData = $("#pointFrm").serialize();
        $(".wrapper_loading").removeClass("hidden");
        $.ajax({
            url: "/exchange/point_proc.asp",
            type: "POST",
            data: formData,
            dataType: "text",
            success: function(data) {
                console.log(data);
                if (data != "success") {
                    $('.wrapper_loading').addClass('hidden');
                    alert_error(data);
                } else {
                    alert_ok_reload("포인트를 전환 신청하였습니다.", "/");
                }
            },
            complete: function() { $(".wrapper_loading").addClass("hidden") }
        });
    }
}



//-- 쪽지 --//
function readMessage(idx) {
    if (parseInt($('#is_sign_in').val())) {
        $.ajax({
            url: "/api/readMsg",
            type: 'POST',
            data: { id: idx },
            dataType: 'html',
            headers: {},
            success: function(data) {},
            error: function(xhr, status, error) {},
            complete: function() {}
        });
    }

}

function deleteMessage(idx) {
    if (parseInt($("#is_sign_in").val()) == 1) {
        $.ajax({
            url: "/api/deleteMsg",
            type: "POST",
            data: { id: idx },
            dataType: "html",
            success: function(t) {

            }
        });
    }
}


//-- 공지사항 --//
function viewEventDetail(idx, num) {
    if (parseInt($("#is_sign_in").val()) == 1) {
        $(".wrapper_loading").removeClass("hidden");
        $.ajax({
            url: "/board/view.asp",
            type: "GET",
            data: { target: "event", idx: idx, num: num },
            dataType: "html",
            success: function(data) {
                $('.nav-mdl .event-link').parent().addClass('active');
                $('.nav-mdl .event-link').parent().siblings('.nav-btn').removeClass('active');
                $('.tab-mdl.event-view').addClass('active');
                $('.tab-mdl.event-view').siblings('.tab-mdl').removeClass('active');

                var obj = $(".event-view-section");
                obj.empty();
                obj.append(data);
            },
            complete: function() { $(".wrapper_loading").addClass("hidden") }
        });
    }
}

function viewNoticeDetail(idx, num) {
    if (parseInt($("#is_sign_in").val()) == 1) {
        $(".wrapper_loading").removeClass("hidden");
        $.ajax({
            url: "/board/view.asp",
            type: "GET",
            data: { target: "notice", idx: idx, num: num },
            dataType: "html",
            success: function(data) {
                $('.nav-mdl .notice-link').parent().addClass('active');
                $('.nav-mdl .notice-link').parent().siblings('.nav-btn').removeClass('active');
                $('.tab-mdl.notice-view').addClass('active');
                $('.tab-mdl.notice-view').siblings('.tab-mdl').removeClass('active');

                var obj = $(".notice-view-section");
                obj.empty();
                obj.append(data);
            },
            complete: function() { $(".wrapper_loading").addClass("hidden") }
        });
    }
}

function noticeEventBack(type) {
    switch (type) {
        case "event":
            $('.nav-mdl .event-link').parent().addClass('active');
            $('.nav-mdl .event-link').parent().siblings('.nav-btn').removeClass('active');
            $('.tab-mdl.event').addClass('active');
            $('.tab-mdl.event').siblings('.tab-mdl').removeClass('active');
            noticeEventInit();
            break;
        default:
            $('.nav-mdl .notice-link').parent().addClass('active');
            $('.nav-mdl .notice-link').parent().siblings('.nav-btn').removeClass('active');
            $('.tab-mdl.notice').addClass('active');
            $('.tab-mdl.notice').siblings('.tab-mdl').removeClass('active');
            noticeEventInit();
    }
}

function noticeEventInit() {
    if ($(".event_table tbody tr td").length === 1) {
        postAjax(1, "EV");
    }
    if ($(".notice_table tbody tr td").length === 1) {
        postAjax(1, "NT");
    }
}

function boardPopup(type, idx, num) {
    if (parseInt($("#is_sign_in").val()) == 1) {
        switch (type) {
            case "event":
                viewEventDetail(idx, num);
                break;
            default:
                viewNoticeDetail(idx, num);
        }
    }
}

function postAjax(page, type) {
    
}


//-- 게임실행 --//
function openGame(pv) {
    if (parseInt($("#is_sign_in").val()) == 1) {
        var url = "";

        if (pv == "micro") {
            url = "/api/micro/microrun.asp";
        }
        window.open(url, "MGM-CASINO", "left=0,top=0,width=1280,height=743,resizable=no");
    }
}

function getSlotGames(title, category) {
    if (!parseInt($('#is_sign_in').val())) return;

    // $('.gamelist-cont').addClass('hidden');
    // $('.gamelistModal .bs-modal .nav-mdl .nav-btn:nth-child(1) button').click();
    var formData = new FormData();
    formData.append("_token", $("#_token").val());
    formData.append("category", category);
    $('.gamelistModal').modal('show');
    $.ajax({
        type: "POST",
        url: "/api/getgamelist",
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        async: false,
        success: function(data) {

            if (data.error) {
                alert(data.msg);
                if (data.code == "001") {
                    location.reload();
                }
                return;
            }

            $(".tp-name").text(title);
            var strHtml = "";
            if (data.games.length > 0) {
                for (var i = 0; i < data.games.length; i++) {
                    if (data.games[i].provider)
                    {
                        strHtml += ` <a href="javascript:void(0);" class="game-btn" onClick="startGameByProvider('${data.games[i].provider}', '${data.games[i].gamecode}');">
                                    <div class="inner">
                                        <div class="img-cont">`;
                        if (data.games[i].icon)
                        {
                        
                            strHtml += `<img class="main-img" src="${data.games[i].icon}" alt="thumbnail">`;
                        }
                        else
                        {
                            strHtml += `<img class="main-img" src="/frontend/Default/ico/${data.games[i].provider}/${data.games[i].gamecode}_${data.games[i].name}.jpg" alt="thumbnail">`;
                        }
                        strHtml += `</div>
                                    <div class="info-cont">
                                    <span class="game-name">${data.games[i].title}</span>
                                    </div>
                                    <button class="start-btn">게임시작하기</button>
                                </div>
                            </a>`;
                    }
                    else
                    {
                    strHtml += ` <a href="javascript:void(0);" class="game-btn" onClick="startGame('${data.games[i].name}');">
                                    <div class="inner">
                                        <div class="img-cont">
                                        <img class="main-img" src="/frontend/Default/ico/${data.games[i].name}.jpg">
                                        </div>
                                        <div class="info-cont">
                                        <span class="game-name">${data.games[i].title}</span>
                                        </div>
                                        <button class="start-btn">게임시작하기</button>
                                    </div>
                                </a>`;
                    }
                }

                
            }
            $(".a-tab").html(strHtml);
            gameDivision($('.alltab'));
        },
        complete: function() {
            $(".wrapper_loading").addClass("hidden");
        }
    });

}

function gameDivision(target) {
    target.parent().addClass('active');
    $('.slots').addClass('hidden');
    target.parent().siblings().removeClass('active');
    $(target.data('target')).removeClass('hidden');
}

function lauchXiLive(gid, gtype, pv) {
    // if(isConstruct == 1) {
    // 	underConstruction();
    // 	return;
    // }

    var gurl = "/api/ximax/xirun.asp?gid=" + gid + "&gtype=" + gtype + "&pv=" + pv + "&nowtime=" + (new Date()).getTime();
    var game_main = window.open(gurl, 'game_main', 'width=1350, height=900, status=no, scrollbars=yes, resizable=yes');
}

function lauchXiGame(gid, gtype, pv) {
    var gurl = "/api/ximax/xirun.asp?gid=" + gid + "&gtype=" + gtype + "&pv=" + pv + "&nowtime=" + (new Date()).getTime();
    var game_main = window.open(gurl, 'game_main', 'width=1350, height=900, status=no, scrollbars=yes, resizable=yes');
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