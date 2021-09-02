var $Banner;
var	$Wrap;
var $RightBoard;
var $RightBoard2;

$(document).ready(function (e) {
    $Wrap = $("#wrap");
    $Banner = $(".top_banner_wrap");
    $RightBoard = $(".right_board_wrap");
    $RightBoard2 = $(".right_board_wrap2");
    $(".banner_up").bind("click", onMoving2);
    $(".banner_down").bind("click", onMoving1);
    $(".right_open").bind("click", onMoving3);
    $(".right_close").bind("click", onMoving4);
    $(".right_open2").bind("click", onMoving5);
    $(".right_close2").bind("click", onMoving6);

    $("#username").keypress(function (e) {
        if (e.keyCode == 13) {
            loginProc();
        }
    });
    $("#password").keypress(function (e) {
        if (e.keyCode == 13) {
            loginProc();
        }
    });
});

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

function loginProc() {
    var username = $("#username").val();
    var password = $("#password").val();

    if (username.length < 1) {
        Swal.fire({
            icon: 'warning',
            title: '아이디를 입력 해 주세요.',
            confirmButtonText: '확인'
        }).then((result) => {
            if (result.value) {
                $("#login_id").focus();
            }
        });
        return false;
    } else if (password.length < 1) {
        Swal.fire({
            icon: 'warning',
            title: '비밀번호를 입력 해 주세요.',
            confirmButtonText: '확인'
        }).then((result) => {
            if (result.value) {
                $("#login_pwd").focus();
            }
        });
        return false;
    }

    var formData = new FormData();
    formData.append("_token", $("#_token").val());
    formData.append("username", username);
    formData.append("password", password);

    $.ajax({
        type: "POST",
        url: "/api/login",
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        async: false,
        success: function (data, status) {
            if (data.error) {
                Swal.fire({
                    icon: 'error',
                    title: data.msg,
                    confirmButtonText: '확인'
                });
                return;
            }

            location.reload(true);
        },
        error: function (err, xhr) {
        }
    });
}

function logoutProc() {
    location.href = "/logout";
}
function getBalance() {
    $.ajax({
        type: "POST",
        url: "/api/balance",
        cache: false,
        async: false,
        success: function (data) {
            if (data.error) {
                alert(data.msg);
                if (data.code == "001") {
                    location.reload(true);
                }
                return;
            }
            $("#cur_money").text(data.balance + ' 원');
            $("#cur_deal").text(data.deal + ' 원');
        },
        error: function (err, xhr) {
            //alert(err.responseText);
        }
    });
}
function openMenu(obj) {
    var tab = $("#menu_pop .popup_tab li." + obj);
    tab.siblings().removeClass("sk_tab_active_01");
    tab.addClass("sk_tab_active_01");

    $("#menu_pop .content").siblings().addClass("popup_none");
    $("#" + obj).removeClass("popup_none");

    if (obj == "myinfo") {
        
    }
    else if (obj == "moneyhistory") {
    }
    else if (obj == "bonusdeal") {
        $.ajax({
            type: "POST",
            url: "/api/balance",
            cache: false,
            async: false,
            success: function (data) {
                if (data.error) {
                    alert(data.msg);
                    if (data.code == "001") {
                        location.reload(true);
                    }
                    return;
                }
                $("#cur_money").text(data.balance + ' 원');
                $("#cur_deal").text(data.deal + ' 원');
                $('#bonusdeal.cur_deal').text(data.deal + ' 원');
            },
            error: function (err, xhr) {
                //alert(err.responseText);
            }
        });
    }
}
function openDemoApp(url) {
    window.open(url, "game", "width=1280, height=742, left=100, top=50");
  }

function openGroup(obj, category) {
    var tab = $("#slot_pop .popup_tab li." + obj);
    tab.siblings().removeClass("sk_tab_active_01");
    tab.addClass("sk_tab_active_01");

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
                    location.reload(true);
                }
                return;
            }

            var strHtml = '';
            if (data.games.length > 0) {
                for (var i = 0; i < data.games.length; i++) {
                    strHtml += '<div style="width: 18%; height: 220px; margin: 10px; float:left; text-align:center;">';

                    if (data.games[i].provider)
                    {
                        strHtml += '<a href="javascript:;" onclick="startGameByProvider(\'' + data.games[i].provider + '\',\'' + data.games[i].gamecode + '\');">';
                        if(data.games[i].name != '')
                        {
                            if (data.games[i].icon)
                            {
                                strHtml += '<img src="' + data.games[i].icon + '" style="width: 100%; height: 60%;" alt="' + data.games[i].title + '"></img>';
                            }
                            else {
                                strHtml += '<img src="/frontend/Default/ico/' + data.games[i].provider + "/" + data.games[i].gamecode + "_" + data.games[i].name + '.jpg" style="width: 100%; height: 60%;" alt="' + data.games[i].title + '"></img>';
                            }
                        }
                        else{
                            strHtml += '<img src="" style="width: 100%;" alt=""></img>';
                        }
                        strHtml += '</a>';
                    }
                    else
                    {
                        strHtml += '<a href="javascript:;" onclick="startGame(\'' + data.games[i].name + '\');">';
                        //</a>strHtml += '<a href="javascript:;" onclick="comingSoon();">';
                        if(data.games[i].name != '')
                        {
                            strHtml += '<img src="/frontend/Default/ico/' + data.games[i].name + '.jpg" style="width: 100%; height: 70%;" alt="' + data.games[i].title + '"></img>';
                        }
                        else{
                            strHtml += '<img src="" style="width: 100%;" alt=""></img>';
                        }
                        strHtml += '</a>';
                    }
                    strHtml += '<span style="margin-top:5px; display:inline-block; color: #fff; font-size:16px;">' + data.games[i].title + '</span>';
                    if (data.games[i].demo)
                    {
                        strHtml += '<p><a href="javascript:;" onclick="openDemoApp(\'' + data.games[i].demo +'\');">';
                        strHtml += '<span class="demo_btn">무료게임</span>';
                        //strHtml += '<span style="margin-top:5px; display:inline-block; color: #fff; font-size:18px;">DemoPlay</span>';
                        strHtml += '</a>';
                    }
                    strHtml += '</div>';
                }
            } else {
                strHtml += '<div style="text-align: center;">';
                strHtml += '<img src="/frontend/Major/major/images/coming_soon.png" style="margin-top: 150px;">';
                strHtml += '</div>';
            }
            if (data.others.length > 0) {
                for (var i = 0; i < data.others.length; i++) {
                    strHtml += '<div style="width: 18%; height: 200px; margin: 10px; float:left; text-align:center;">';
                    strHtml += '<a href="javascript:;" onclick="startGame(\'' + data.others[i].name + '\');">';
                    //</a>strHtml += '<a href="javascript:;" onclick="comingSoon();">';
                    if(data.others[i].name != '')
                    {
                        strHtml += '<img src="/frontend/Default/ico/' + data.others[i].name + '.jpg" style="width: 100%; height: 70%;" alt="' + data.others[i].title + '"></img>';
                    }
                    else{
                        strHtml += '<img src="" style="width: 100%;" alt=""></img>';
                    }
                    strHtml += '</a>';
                    strHtml += '<span style="margin-top:5px; display:inline-block; color: #fff; font-size:18px;">' + data.others[i].title + '</span>';
                    strHtml += '</div>';
                }
            } else {
            }
            $("#slot_pop .popup_content_wrap").html(strHtml);
        },
        error: function (err, xhr) {
            alert(err.responseText);
        }
    });
}

function comingSoon() {
    alert('준비중입니다...');
}

function updateMyInfo() {
    var cur_pwd = $("#cur_pwd").val();
    var new_pwd = $("#new_pwd").val();
    var new_pwd_confirm = $("#new_pwd_confirm").val();

    if (cur_pwd == '') {
        alert('비밀번호를 입력해주세요.');
        $("#cur_pwd").focus();
        return;
    }
    if (new_pwd == '') {
        alert('새 비밀번호가 입력되지 않았습니다.');
        $("#new_pwd").focus();
        return;
    }
    if (new_pwd_confirm == '') {
        alert('새 비밀번호 재입력을 해주세요.');
        $("#new_pwd_confirm").focus();
        return;
    }
    if (new_pwd != new_pwd_confirm) {
        alert('새비밀번호와 확인비밀번호가 일치하지 않습니다.');
        return;
    }

    $.ajax({
        type: "POST",
        url: "/api/updateinfo",
        data: { cur_pwd: cur_pwd, new_pwd: new_pwd },
        cache: false,
        async: false,
        success: function (data) {
            if (data.error) {
                alert(data.msg);
                if (data.code == '001') {
                    location.reload(true);
                }
                else if (data.code == '002') {
                    $("#cur_pwd").focus();
                }
                else if (data.code == '003') {
                    $("#new_pwd").focus();
                }
                return;
            }

            alert('비밀번호가 수정되었습니다.');
            logoutProc();
        },
        error: function (err, xhr) {
            alert(err.responseText);
        }
    });


}

function replaceComma(str) {
    if (str == null || str.length == 0) return "";
    while (str.indexOf(",") > -1) {
        str = str.replace(",", "");
    }
    return str;
}

function insertComma(str) {
    var val = str.split('.');
    var str2 = 0;
    if (val.length == 2) {
        str = val[0];
        str2 = val[1];
    }

    var rightchar = replaceComma(str);
    var moneychar = "";
    for (index = rightchar.length - 1; index >= 0; index--) {
        splitchar = rightchar.charAt(index);
        if (isNaN(splitchar)) {
            alert("'" + splitchar + "' 숫자가 아닙니다.\n다시 입력해주십시오.");
            return "";
        }
        moneychar = splitchar + moneychar;
        if (index % 3 == rightchar.length % 3 && index != 0) {
            moneychar = ',' + moneychar;
        }
    }
    str = moneychar;

    if (val.length == 2) {
        str = str + "." + str2;
    }

    return str;
}

function numChk(num) {
    var rightchar = replaceComma(num.value);
    var tmp = parseFloat(rightchar);
    rightchar = "" + tmp;
    if (isNaN(rightchar)) {
    } else {
        var moneychar = "";
        for (index = rightchar.length - 1; index >= 0; index--) {
            splitchar = rightchar.charAt(index);
            if (isNaN(splitchar)) {
                alert("'" + splitchar + "' 숫자가 아닙니다.\n다시 입력해주십시오.");
                num.value = "";
                num.focus();
                return false;
            }
            moneychar = splitchar + moneychar;
            if (index % 3 == rightchar.length % 3 && index != 0) {
                moneychar = ',' + moneychar;
            }
        }
        num.value = moneychar;
    }
    return true;
}

function changeMoney(obj) {
    var money = replaceComma($('#' + obj + ' .money').val());
    if (money.length > 0) {
        $('#' + obj + ' .tmp_money').val(parseInt(money));
    }
    else {
        $('#' + obj + ' .tmp_money').val(0);
    }
    $('#' + obj + ' .money').val(insertComma($('#' + obj + ' .tmp_money').val()));
}

function addMoney(obj, c_money) {
    var money = parseInt($('#' + obj + ' .tmp_money').val());
    money += parseInt(c_money);
    $('#' + obj + ' .tmp_money').val(money);
    $('#' + obj + ' .money').val(insertComma('' + money));
}

function resetMoney(obj) {
    $('#' + obj + ' .tmp_money').val(0);
    $('#' + obj + ' .money').val(0);
}

function deposit() {
    var refundname = $('#deposit .refundname').val();
    var money = $('#deposit .tmp_money').val();

    if (refundname == '') {
        alert('입금자명을 입력해주세요.');
        $('#deposit .refundname').focus();
        return;
    } 

    if (money < 30000) {
        alert('충전 최소금액은 30,000원 입니다.');
        return;
    }
    if (money % 10000 > 0) {
        alert("10,000원 단위로만 충전 가능합니다.");
        return;
    }

    $.ajax({
        type: 'POST',
        url: '/api/deposit',
        data: { refundname: refundname, money: money },
        cache: false,
        async: false,
        success: function (data) {
            if (data.error) {
                alert(data.msg);
                if (data.code == '001') {
                    location.reload(true);
                }
                else if (data.code == '002') {
                    $('#deposit .refundname').focus();
                }
                return;
            }

            alert('충전 신청이 완료되었습니다.');
            $('#deposit .btn3_2').click();
        },
        error: function (err, xhr) {
            alert(err.responseText);
        }
    });
}

function convertDeal() {

    var money = $('#bonusdeal .tmp_money').val();
    if (money < 10000) {
        alert('환전 최소금액은 10,000원 입니다.');
        return;
    }
    if (money % 10000 > 0) {
        alert("10,000원 단위로만 환전 가능합니다.");
        return;
    }


    $.ajax({
        type: 'POST',
        url: '/api/convert_deal_balance',
        data: { summ: money },
        cache: false,
        async: false,
        success: function (data) {
            if (data.error) {
                alert(data.msg);
                return;
            }
            alert('수익금이 보유금으로 전환되었습니다.');
            location.reload(true);
        },
        error: function (err, xhr) {
            alert(err.responseText);
        }
    });

}

function withdraw() {
    var refundpassword = $('#withdraw .refundpassword').val();
    if (refundpassword == '') {
        alert('출금비밀번호를 입력해주세요.');
        $('#withdraw .refundpassword').focus();
        return;
    }

    var money = $('#withdraw .tmp_money').val();
    if (money < 30000) {
        alert('환전 최소금액은 30,000원 입니다.');
        return;
    }
    if (money % 10000 > 0) {
        alert("10,000원 단위로만 환전 가능합니다.");
        return;
    }

    $.ajax({
        type: 'POST',
        url: '/api/withdraw',
        data: { money: money, refundpassword: refundpassword },
        cache: false,
        async: false,
        success: function (data) {
            if (data.error) {
                alert(data.msg);
                if (data.code == '001') {
                    location.reload(true);
                }
                else if (data.code == '002') {
                    $('#withdraw .money').focus();
                }
                else if (data.code == '003') {
                    $('#withdraw .money').val('0');
                }
                else if (data.code == '004') {
                    $('#withdraw .refundpassword').focus();
                }
                else if (data.code == '005') {
                    $('#withdraw .refundpassword').val('');
                }
                return;
            }

            alert('환전 신청이 완료되었습니다.');
            location.reload(true);
        },
        error: function (err, xhr) {
            alert(err.responseText);
        }
    });
}

function delMoneyHistory() {
    if (confirm('정말 삭제하시겟습니까?')) {
        $.ajax({
            type: 'POST',
            url: '/api/delmoneyhistory',
            cache: false,
            async: false,
            success: function (data) {
                if (data.error) {
                    alert(data.msg);
                    if (data.code == '001') {
                        location.reload(true);
                    }
                    return;
                }

                $("#moneyhistory .list").html('<tr><td colspan="5" class="td_basic">입출금내역이 없습니다.</td></tr>');
            },
            error: function (err, xhr) {
                alert(err.responseText);
            }
        });
    }
}
