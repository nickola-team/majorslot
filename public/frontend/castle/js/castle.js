$(document).ready(function () {
    $("input[name='use-point-value']").number(true);

    if (parseInt($('#is_sign_in').val())) {
        $(".wager-reload").on('click', function () {
            if (!$('.balance').hasClass('wager-reload')
                || !$('.point').hasClass('wager-reload'))
                return ;
            $('.wrapper_loading').removeClass('hidden');
            wagerCheck();
            $(this).removeClass('wager-reload');
            setTimeout(function () {
                if (!$('.balance').hasClass('wager-reload')){
                    $('.balance').addClass('wager-reload');
                }
                if (!$('.point').hasClass('wager-reload')) {
                    $('.point').addClass('wager-reload')
                }
            }, 60000);
        });

        var pusher = new Pusher('76cf54d2768b927d38ab', {
            cluster: 'ap1',
            encrypted: false
        });

        var channel_login = pusher.subscribe('CASTLELogin-alert');

        channel_login.bind('CASTLELoginEvent', function (data) {
            var currentUserIdx = parseInt($('#val-idx').val());
            if (parseInt(data.notifyUserIdx) === currentUserIdx) {
                alertify.alert('알림', '중복으로 로그인되어 현재 위치에서 로그아웃 합니다.');
                $("#alertMSG").get(0).play();
                $('.wrapper_loading').removeClass('hidden');
                statusUpdate('logOff');
                setTimeout(function () {
                    window.location.href = '/logout';
                }, 600);
            }
        });

        var channel_auth = pusher.subscribe('masterAuth-alert');
        channel_auth.bind('MasterAuthEvent', function (data) {
            var currentUserIdx = parseInt($('#val-idx').val());
            if (parseInt(data.notifyUserIdx) === parseInt(currentUserIdx)) {
                alertify.alert('알림', data.notifyMessage);
                $("#newMSG").get(0).play();

                if (data.notifyType === 'normal') {
                    var messageWeb = $('.after-login .al-row .letter-count');
                    var count = parseInt(messageWeb.text());
                    messageWeb.text(count + 1);
                    messageWeb.css('animation', 'letter_anim 1s linear infinite');
                    //messageReload();
                    postAjax(1, 'MG');
                } else if (data.notifyType === 'finance') {
                    wagerCheck();
                    //refreshUserPoint();
                } else if (data.notifyType === 'ban') {
                    $('.wrapper_loading').removeClass('hidden');
                    window.location.href = '/logout';
                }
            }
        });
    }

    //logout
    $('.logout_btn').on('click', function () {
        $('.wrapper_loading').removeClass('hidden');
        window.location.href = "/logout";
    });

    //마이페이지 상단 탭버튼 클릭
    $('.mypage-modal .mp-head a').on('click', function () {
        if ($(this).attr('id') === undefined) {
            return false;
        } else {
            var _id = $(this).attr('id');
            var ele;
            switch (_id) {
                case 'FC':
                    ele = $('.finance-list');
                    break;
                case 'BH':
                    ele = $('.bonuses-list');
                    break;
                case 'BW':
                    ele = $('.bet-win-list');
                    break;
                case 'CP':
                    ele = $('.coupon-list');
                    break;
            }
            if (_id === 'MG' && $('.message-list table .title-td').hasClass('loading')) {
                postAjax(1, _id);
            } else {
                if (_id !== 'MG' && parseInt(ele.children().length) === 1) {
                    postAjax(1, _id);
                }
            }
        }
    });

    //쪽지클릭
    $('.after-login .al-row .letter-count').on('click', function () {
        $('.myPageModal').modal('show');
        $('.mypage-modal .mp-head a:nth-child(2)').click();
    });

    //message
    $("#writeForm").submit(function () {
        $(this).parsley().validate();
        if ($(this).parsley().isValid()) {
            $('.wrapper_loading').removeClass('hidden');
        } else {
            alertify.alert('알림', '입력항목을 확인하세요.');
        }
    });

    //포인트클릭
    $('.after_login .al_form .value').on('click', function () {
        $('.myPageModal').modal('show');
        $('.my_page_menu button:nth-child(7)').on('click');
    });

    $('.btn-deposit').on('click', function () {
        wagerCheck();
    });

    //공지사항
    $('.notice-popup').on('click', function () {
        if (parseInt($('#is_sign_in').val())) {
            var len = $('.notice_table tbody tr').length;
            if (len < 2) {
                postAjax(1, 'NT');
            }
        }
    });

    //이벤트
    $('.event-popup').on('click', function () {
        if (parseInt($('#is_sign_in').val())) {
            var len = $('.event_table tbody tr').length;
            if (len < 2) {
                postAjax(1, 'EV');
            }
        }
    });

    $('.sidenav_main .cont').on('click', function () {
        if ($(this).hasClass('m-board') && parseInt($('#is_sign_in').val())) {
            var len = $('.event_table tbody tr').length;
            if (len === 0) {
                postAjax(1, 'EV');
            }

            var len = $('.notice_table tbody tr').length;
            if (len === 0) {
                postAjax(1, 'NT');
            }
        }
    });

    $('.sidenav_main .cont .dropdowns .dd_main .dd_row').on('click', function () {
        $(".mobile_menu").on('click');
    });
});

//하단 보드 더보기 버튼
function boardPopup(target, idx, num) {
    if (parseInt($('#is_sign_in').val())) {
        var len = $('.event_table tbody tr').length;
        if (len === 0) {
            postAjax(1, 'EV');
        }

        var len = $('.notice_table tbody tr').length;
        if (len === 0) {
            postAjax(1, 'NT');
        }

        if (target === 'event') {
            viewEventDetail(idx, num);
        } else {
            viewNoticeDetail(idx, num);
        }
    }
}

function messageWrite() {
    $('.message-write').removeClass('hidden');
    $('.message-view').addClass('hidden');
}

function writeCancel() {
    $('.message-write').addClass('hidden');
    $('.message-view').removeClass('hidden');
}

//----------------------------------------------> 팝업관련
function getCookie(sName) {
    var aCookie = document.cookie.split("; ");
    for (var i = 0; i < aCookie.length; i++) {
        var aCrumb = aCookie[i].split("=");
        if (sName === aCrumb[0]) {
            return unescape(aCrumb[1]);
        }
    }
    return null;
}

function setCookie(name, value, expiredays) {
    var todayDate = new Date();
    todayDate.setDate(todayDate.getDate() + expiredays);
    document.cookie = name + "=" + escape(value) + "; path=/; expires=" + todayDate.toGMTString() + ";"
}

function addPopup(popupid, top, left, width, height) {
    var popup = document.getElementById(popupid);
    popup.style.top = top;
    popup.style.left = left;
    popup.style.width = width;
    popup.style.height = height;
    popup.style.display = 'block';

    dragElement(document.getElementById(popupid));
}

function hidePopup(name, value, expiredays) {
    setCookie(name, value, expiredays);
    var popup = document.getElementById(name);
    popup.style.display = 'none';
}

function popupClose(popupId) {
    var popup = document.getElementById(popupId);
    popup.style.display = 'none';
}

function dragElement(elmnt) {
    var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
    if (document.getElementById(elmnt.id + "header")) {
        /* if present, the header is where you move the DIV from:*/
        document.getElementById(elmnt.id + "header").onmousedown = dragMouseDown;
    } else {
        /* otherwise, move the DIV from anywhere inside the DIV:*/
        elmnt.onmousedown = dragMouseDown;
    }

    function dragMouseDown(e) {
        e = e || window.event;
        // get the mouse cursor position at startup:
        pos3 = e.clientX;
        pos4 = e.clientY;
        document.onmouseup = closeDragElement;
        // call a function whenever the cursor moves:
        document.onmousemove = elementDrag;
    }

    function elementDrag(e) {
        e = e || window.event;
        // calculate the new cursor position:
        pos1 = pos3 - e.clientX;
        pos2 = pos4 - e.clientY;
        pos3 = e.clientX;
        pos4 = e.clientY;
        // set the element's new position:
        elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
        elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
    }

    function closeDragElement() {
        /* stop moving when mouse button is released:*/
        document.onmouseup = null;
        document.onmousemove = null;
    }
}

//----------------------------------------------> 팝업관련 끝

//입출금 금액선택 버튼
function addAmount(target, amount) {
    var f = (target === 1) ? $("#depositAmount") : $("#withdrawalAmount");
    var amt = Number(f.val());
    amt = amt + (amount * 10000);
    if (isNaN(amt)) amt = 0;
    f.val(amt);
    f.focus();
}

//입출금 금액정정 버튼
function resetAmount(target) {
    var f = (target === 1) ? $("#depositAmount") : $("#withdrawalAmount");
    f.val('');
}


//90초간격 플레이어 로그인상태 업데이트
setInterval(function () {
    if (parseInt($('#is_sign_in').val())) statusUpdate('logOn');
}, 90000);

//입출금내역 업데이트
/*
setInterval(function () {
    if (parseInt($('#is_sign_in').val())) getPaymentInfo();
}, 105000);
*/

//레벨체크
/*
setTimeout(function () {
    levelUpCheck();
}, 10000);
setInterval(function () {
    levelUpCheck();
}, 66000);

function levelUpCheck() {
    if ($('#is_level').val() === 'Y') {
        $.ajax({
            url: '/player/level/level.detail',
            type: 'GET',
            data: {},
            dataType: 'json',
            headers: {},
            success: function (data) {
            },
            error: function () {
            },
            complete: function () {
            }
        });
    }
}*/

//로그인유지 타이머
function initTimer() {
    if (window.event) {
        iSecond = parseInt(iMinute) * 60;
        clearTimeout(timerChecker);
    }

    if (iSecond >= 0) {
        timerChecker = setTimeout('initTimer()', 1000);
        if (iSecond === 90) {
            $('#modal-alert-logout').modal();
            $("#alertMSG").get(0).play();
        }
        iSecond--;
    } else {
        $('.wrapper_loading').removeClass('hidden');
        clearTimeout(timerChecker);
        statusUpdate('logOff');
        setTimeout(function () {
            window.location.href = '/logout';
        }, 600);
    }
}

//상태업데이트
function statusUpdate(status) {
    if (parseInt($('#is_sign_in').val())) {
        $.ajax({
            url: '/player/status/detail.info',
            type: 'POST',
            data: {status: status},
            dataType: 'text',
            headers: {},
            success: function (data) {
            },
            error: function (xhr, status, error) {
            },
            complete: function () {
            }
        });
    }
}

//입출금내역 갱신해서 가져오기
function getPaymentInfo() {
    /*$.ajax({
        url: '/deposit/withdraw/detail.info',
        type: 'POST',
        data: {},
        dataType: 'html',
        headers: {
            
        },
        success: function (data) {
            var element = $('.bank_cont');
            element.empty();
            element.append(data);
        },
        error: function (xhr, status, error) {

        },
        complete: function () {

        }
    });*/
}

//플레이어 발란스 등 웨이저링 정보
function wagerCheck() {
    if (parseInt($('#is_sign_in').val())) {
        $(".player-balance").text("로딩중···");
        $(".current-point").text("로딩중···");
        $("#withdrawalAmount").val('');
        $.ajax({
            url: '/player/wager/detail.info',
            type: 'POST',
            data: {},
            dataType: 'json',
            headers: {},
            success: function (data) {
                //console.log(data);
                var percent = (parseInt(data.wPercent) === 100) ? 0 : parseInt(data.wPercent);
                $(".player-balance").text($.number(parseInt(data.pBalance), 0) + ' 원');
                $(".current-point").text($.number(parseInt(data.accrualPoint), 0) + ' P');
                $(".accrual-point").text($.number(Math.floor(data.accrualPoint), 0));
                $(".after-login .progressbar .percent").css('width', percent + '%');
                $("#percent").html(percent + '%');

                var mok = parseInt(data.pBalance / 10000);
                $("#withdrawalAmount").val(mok * 10000);

            },
            error: function (xhr, status, error) {
            },
            complete: function () {
                if(!$('.wrapper_loading').hasClass('hidden')) {
                    $('.wrapper_loading').addClass('hidden');
                }
            }
        });
    }
}

//입출금, 벳윈, 보너스 내역
function postAjax(page, target) {
    $('.wrapper_loading').removeClass('hidden');
    $.ajax({
        url: '/player/post.info',
        type: 'POST',
        data: {page: page, target: target},
        dataType: 'html',
        headers: {},
        success: function (data) {
            var element;
            switch (target) {
                case 'FC':
                    element = $('.finance-list');
                    break;
                case 'BH':
                    element = $('.bonuses-list');
                    break;
                case 'BW':
                    element = $('.bet-win-list');
                    break;
                case 'CP':
                    element = $('.coupon-list');
                    break;
                case 'EV':
                    element = $('.event-section');
                    break;
                case 'NT':
                    element = $('.notice-section');
                    break;
                case 'MG':
                    element = $('.message-list');
                    break;
            }
            element.empty();
            element.append(data);

            /*if (!element.has('table').length){

            }*/
        },
        error: function (xhr, status, error) {
        },
        complete: function () {
            $('.wrapper_loading').addClass('hidden');
        }
    });
}

//쿠폰사용
function useCoupon(cid, pin) {
    if (confirm('쿠폰을 사용하시겠습니까?')) {
        $('.wrapper_loading').removeClass('hidden');
        $.ajax({
            url: '/player/coupon/use.info',
            type: 'POST',
            data: {cid: cid, pin: pin},
            dataType: 'text',
            headers: {},
            success: function (data) {
                if (data === 'success') {
                    var btn = $('#btnUse_' + cid);
                    var txt = $('#use_yn_' + cid);
                    btn.attr('disabled', 'disabled');
                    btn.removeClass('btn-primary');
                    btn.addClass('btn-default');
                    txt.text('사용');
                    wagerCheck();
                } else if (data === 'error') {
                    alertify.alert('알림', '쿠폰을 사용하는데 실패했습니다. 문제가 지속되면 관리자에게 문의하세요.');
                }
            },
            error: function (xhr, status, error) {
            },
            complete: function () {
                $('.wrapper_loading').addClass('hidden');
            }
        });
    }
}


//포인트사용
function usePoint() {
    var point = Math.abs($("input[name='use-point-value']").val());
    if (point === 0 || isNaN(point)) {
        alertify.alert('알림', '사용할 포인트를 입력하세요.');
    } else {
        //var value = Math.floor(point / $('#point_restrict').val());
        if (point < parseFloat($('#point_restrict').val())) {
            alertify.alert('알림', $('#point_restrict').val() + '포인트 이상부터 사용가능합니다.');
        } else {
            if (point % 10000 > 0) {
                alertify.alert('알림', '포인트는 1만 단위로 사용해주세요.');
            } else {

                alertify.confirm('포인트사용', '포인트를 사용하시겠습니까? 사용할 포인트는 ' + point + 'P 입니다.',
                    function () {
                        $('.wrapper_loading').removeClass('hidden');
                        $.ajax({
                            url: '/player/point/use.info',
                            type: 'POST',
                            data: {point: point},
                            dataType: 'json',
                            headers: {},
                            success: function (data) {
                                console.log(data);
                                if (data.result) {
                                    alertify.alert('알림', '포인트 사용신청을 완료했습니다.(' + data.message + ')');
                                    $(".current-point").text(data.point + ' P');
                                    $(".accrual-point").text(data.point);
                                } else {
                                    alertify.alert('알림', data.message);
                                }
                            },
                            error: function (xhr, status, error) {
                            },
                            complete: function () {
                                $("input[name='use-point-value']").val('');
                                $('.wrapper_loading').addClass('hidden');
                            }
                        });
                    },
                    function () {

                    });
            }
        }
    }
    /*var point = $('.current-point').text().replace(',', '');
    var value = Math.round(point / $('#point_restrict').val());
    if (parseFloat(point) < parseFloat($('#point_restrict').val())) {
        alertify.alert('알림', '사용할 포인트가 부족합니다.');
    } else {
        if (confirm('포인트를 사용하시겠습니까?\n사용할 포인트는 ' + $.number(value * 10000) + 'P 입니다.')) {
            $('.wrapper_loading').removeClass('hidden');
            $.ajax({
                url: '/player/point/use.info',
                type: 'POST',
                data: {},
                dataType: 'json',
                headers: {},
                success: function (data) {
                    if (data.result) {
                        alertify.alert('알림','포인트 사용신청을 완료했습니다.(' + data.message + ')');
                        $('.accrual-point').html(data.point + "P")

                    } else {
                        alertify.alert('알림', data.message);
                    }
                },
                error: function (xhr, status, error) {
                },
                complete: function () {
                    $('.wrapper_loading').addClass('hidden');
                }
            });
        }
    }*/

}

//포인트 리프레시
/*function refreshUserPoint() {
    $('.wrapper_loading').removeClass('hidden');
    $.ajax({
        url: './player/point',
        type: 'POST',
        headers: {},
        success: function (data) {
            var result = JSON.parse(data);

            $(".current-point").text($.number(parseInt(result.point), 0) + ' P');
            $(".accrual-point").text($.number(Math.floor(result.point), 0));
            //var target = $('.player-point');
            //var ele = "<span class='current-point'>" + result.point + "</span> P";
            //target.empty();
            //target.append(ele);
        },
        error: function (xhr, status, error) {
            if (error === 'Unauthorized')
                alertify.alert('알림', '로그인 후 이용가능한 서비스입니다.');
        },
        complete: function () {
            $('.wrapper_loading').addClass('hidden');
            //isRolling = false;
        }
    });
}*/

//신규메시지 수신시
function messageReload() {
    if (!parseInt($('#is_sign_in').val())) return;
    $.ajax({
        url: '/player/newest/message.info',
        type: 'POST',
        data: {},
        dataType: 'html',
        headers: {},
        success: function (data) {
            var element = $(".message-list");
            element.empty();
            element.append(data);
        },
        error: function (xhr, status, error) {
        },
        complete: function () {
        }
    });
}

//쪽지 열기버튼
function readMessage(mid, rType, rStatus) {
    if (!parseInt($('#is_sign_in').val())) return;
    $.ajax({
        url: '/player/message/read.info',
        type: 'POST',
        data: {messageId: mid, receiveType: rType, readStatus: rStatus},
        dataType: 'json',
        headers: {},
        success: function (data) {
            if (parseInt(data.updateCount) > 0) {
                var ele = $('.after-login .al-row .letter-count');
                $("#" + mid + rType + rStatus).remove();
                ele.text(data.newMessages);
                if (parseInt(data.newMessages) > 0) {
                    ele.css('animation', 'letter_anim 1s linear infinite');
                } else {
                    ele.css('animation', 'letter_anim 0s linear infinite');
                }
            }
        },
        error: function (xhr, status, error) {
        },
        complete: function () {
        }
    });
}

//쪽지 삭제버튼
function deleteMessage(mid, rType) {
    if (!parseInt($('#is_sign_in').val())) return;
    $.ajax({
        url: '/player/message/delete.info',
        type: 'POST',
        data: {messageId: mid, receiveType: rType, messageStatus: 'DELETE'},
        dataType: 'json',
        headers: {},
        success: function (data) {
            if (parseInt(data.updateCount) > 0) {
                var ele = $('.after-login .al-row .letter-count');
                ele.text(data.newMessages);
                if (parseInt(data.newMessages) > 0) {
                    ele.css('animation', 'letter_anim 1s linear infinite');
                } else {
                    ele.css('animation', 'letter_anim 0s linear infinite');
                }
            }
        },
        error: function (xhr, status, error) {
        },
        complete: function () {
        }
    });
}

//인증번호요청
function requestCertifyNumber() {
    var tel = $("input:text[name='mb_tel']");
    var btn = $('.request_certify');
    if (tel.val() === "") {
        alertify.alert('알림', '전화번호를 입력하세요.');
    } else {
        btn.attr('disabled', true);
        $('.wrapper_loading').removeClass('hidden');
        $.ajax({
            url: '/request/certify/sms.info',
            type: 'GET',
            data: {phone: tel.val()},
            dataType: 'json',
            headers: {},
            success: function (data) {
                var result = data.message;
                if (result.toUpperCase() === 'SUCCESS') {
                    alertify.alert('알림', '인증번호를 입력하고 인증확인 버튼을 클릭하세요.');
                } else {
                    btn.attr('disabled', false);
                    alertify.alert('알림', '인증번호를 전송하지 못했습니다. 페이지 새로고침 후 다시 시도하세요.');
                }
            },
            error: function (xhr, status, error) {
                btn.attr('disabled', false);
                alertify.alert('알림', '인증번호를 전송하지 못했습니다. 페이지 새로고침 후 다시 시도하세요.');
            },
            complete: function () {
                $('.wrapper_loading').addClass('hidden');
            }
        });
    }
}

//인증번호확인
function requestCertifyCheck() {
    var num = $("input:text[name='certify_number']");
    var tel = $("input:text[name='mb_tel']");
    var btn = $('.confirm_certify');
    if (num.val() === "") {
        alertify.alert('알림','인증번호를 입력하세요.');
    } else {
        btn.attr('disabled', true);
        $('.wrapper_loading').removeClass('hidden');
        $.ajax({
            url: '/confirm/certify/sms.info',
            type: 'GET',
            data: {phoneNumber: tel.val(), certifyNumber: num.val()},
            dataType: 'json',
            headers: {},
            success: function (data) {
                var result = data.message;
                if (result.toUpperCase() === 'SUCCESS') {
                    $("input:hidden[name='certified_number']").val(data.result.certify_number);
                    num.focus();
                    alertify.alert('알림', '인증번호가 확인 되었습니다.');
                } else {
                    btn.attr('disabled', false);
                    alertify.alert('알림', '인증번호가 일치하지 않습니다. 페이지 새로고침 후 다시 시도하세요.');
                }
            },
            error: function (xhr, status, error) {
                btn.attr('disabled', false);
                alertify.alert('알림', '인증번호가 일치하지 않습니다. 페이지 새로고침 후 다시 시도하세요.');
            },
            complete: function () {
                $('.wrapper_loading').addClass('hidden');
            }
        });
    }
}

//이벤트 상세보기
function viewEventDetail(eIdx, num) {

    if (!parseInt($('#is_sign_in').val())) return;

    $('.wrapper_loading').removeClass('hidden');
    $.ajax({
        url: '/event/view.detail',
        type: 'POST',
        data: {e_idx: eIdx, num: num},
        dataType: 'html',
        headers: {},
        success: function (data) {
            var element = $('.event-view-section');
            element.empty();
            element.append(data);
            // $('.event-section').css('opacity', '0');
            // element.css('height', '540px');

            $('.eventSeeModal').modal('show');
            $('.eventModal').modal('hide');
        },
        error: function (xhr, status, error) {

        },
        complete: function () {
            $('.wrapper_loading').addClass('hidden');
        }
    });
}


//공지사항 상세보기
function viewNoticeDetail(nIdx, num) {

    if (!parseInt($('#is_sign_in').val())) return;

    $('.wrapper_loading').removeClass('hidden');
    $.ajax({
        url: '/notice/view.detail',
        type: 'POST',
        data: {nIdx: nIdx, num: num},
        dataType: 'html',
        headers: {},
        success: function (data) {
            var element = $('.notice-view-section');
            element.empty();
            element.append(data);
            $('.noticeSeeModal').modal('show');
            $('.noticeModal').modal('hide');
        },
        error: function (xhr, status, error) {

        },
        complete: function () {
            $('.wrapper_loading').addClass('hidden');
        }
    });
}

//라이브게임 오픈
function openGame(gamename) {

    if (!parseInt($('#is_sign_in').val())) return;

    window.open(
        "/game/" + gamename,
        gamename,
        "width=1280, height=742, left=100, top=50"
    );
}

function openGameByProvider(provider, gamecode) {
    if (!parseInt($('#is_sign_in').val())) return;
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

//슬롯게임리스트
function getSlotGames(title, category) {
    $('.wrapper_loading').removeClass('hidden');
    if (!parseInt($('#is_sign_in').val())) return;
       
    //$('.gamePageModal').modal('show');
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
            $('.game-menu.selected a:first').remove();
            var ele = "";
            if (data.games.length > 0) {
                for (var i = 0; i < data.games.length; i++) {
                    if (data.games[i].provider)
                    {
                        ele += `
                            <a href="javascript:void(0)" class="cg-btn" onclick="openGameByProvider('${data.games[i].provider}', '${data.games[i].gamecode}')">`;
                        if (data.games[i].icon)
                        {
                            ele += `<img class="main-img slot-img" src="${data.games[i].icon}">`;
                        }
                        else
                        {
                            ele += `<img class="main-img slot-img" src="/frontend/Default/ico/${data.games[i].provider}/${data.games[i].gamecode}_${data.games[i].name}.jpg">`;
                        }
                        ele += `<div class="overlay">
                                        <button type="button">PLAY</button>
                                    </div>
                                    <div class="foot">
                                        <p>${data.games[i].title}</p>
                                    </div>
                                </a>`;
                    }
                    else
                    {
                        ele += `
                            <a href="javascript:void(0)" class="cg-btn" onclick="openGame('${data.games[i].name}')">
                                <img class="main-img slot-img" src="/frontend/Default/ico/${data.games[i].name}.jpg">
                                <div class="overlay">
                                    <button type="button">PLAY</button>
                                </div>
                                <div class="foot">
                                    <p>${data.games[i].title}</p>
                                </div>
                            </a>`;
                    }
                }
            }
            $('.slot-list-view .all-g').html(ele);


            $('.game-pg-modal .cgame-cont .cg-head .link-grp a').removeClass('active');
            $('.game-pg-modal .cgame-cont .cg-head .link-grp a:first-child').addClass('active');
        },
        error: function (xhr, status, error) {
        },
        complete: function () {
            $('.wrapper_loading').addClass('hidden');
        }
    });
}

function mustSignIn(message) {
    $('#message2Player').html(message);
    $('#modal-alert-message').modal();
}

//send message
function sendMessage(mbIdx, mbId) {
    if (!parseInt($('#is_sign_in').val())) return;
    alertify.confirm('알림','운영자에게 계좌문의 메시지를 발송하시겠습니까?', function () {
        $('.wrapper_loading').removeClass('hidden');
        $("input:text[name=subject]").val('[' + mbId + '] 계좌문의');
        $("textarea[name=message]").val('입금 계좌문의 드립니다!');
        $.ajax({
            url: '/player/message/script/write.info',
            type: 'POST',
            data: {
                send_idx: mbIdx,
                send_id: mbId,
                receive_type: 'M',
                subject: '[' + mbId + '] 계좌문의',
                message: '입금 계좌문의 드립니다!'
            },
            dataType: 'text',
            headers: {},
            success: function (data) {
                if (parseInt(data) > 0 ){
                    alertify.alert('알림', '계좌문의 쪽지를 전송했습니다.');
                } else {
                    alertify.alert('알림', '쪽지 전송에 실패했습니다. 잠시 후 시도하세요.');
                }
            },
            error: function (xhr, status, error) {
            },
            complete: function () {
                $('.wrapper_loading').addClass('hidden');
            }
        });
    }, function () {

    });
}

function setOdometer() {
    var el1 = document.querySelector('#odometer1');
    var el2 = document.querySelector('#odometer2');
    var value = new Date().getTime() + "";
    value = value.substring(2, 12) / 100 + "";
    value = Number(value) + 7000000;

    var od1 = new Odometer({
        el: el1,
        value: value,
        format: '(,ddd).dd',
        theme: 'minimal'
    });

    var od2 = new Odometer({
        el: el2,
        value: value,
        format: '(,ddd).dd',
        theme: 'minimal'
    });

    setInterval(function () {
        var change = new Date().getTime() + "";
        change = change.substring(2, 12) / 100 + "";
        change = Number(change) + 7000000;
        change = $.number(change, 2);
        if (parseInt(change.substr(change.length - 1 , 1)) === 0 ){
            change = $.number(Number(change.toString().replace(/,/gi, '')) + 0.01, 2);
        }
        od1.update($.number(change, 2));
        od2.update($.number(change, 2));
    }, 2333);
}


