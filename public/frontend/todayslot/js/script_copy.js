function MM_swapImgRestore() { //v3.0
    var i, x, a = document.MM_sr;
    for (i = 0; a && i < a.length && (x = a[i]) && x.oSrc; i++) x.src = x.oSrc;
}

function MM_preloadImages() { //v3.0
    var d = document;
    if (d.images) {
        if (!d.MM_p) d.MM_p = new Array();
        var i, j = d.MM_p.length,
            a = MM_preloadImages.arguments;
        for (i = 0; i < a.length; i++)
            if (a[i].indexOf("#") != 0) {
                d.MM_p[j] = new Image;
                d.MM_p[j++].src = a[i];
            }
    }
}

function MM_findObj(n, d) { //v4.01
    var p, i, x;
    if (!d) d = document;
    if ((p = n.indexOf("?")) > 0 && parent.frames.length) {
        d = parent.frames[n.substring(p + 1)].document;
        n = n.substring(0, p);
    }
    if (!(x = d[n]) && d.all) x = d.all[n];
    for (i = 0; !x && i < d.forms.length; i++) x = d.forms[i][n];
    for (i = 0; !x && d.layers && i < d.layers.length; i++) x = MM_findObj(n, d.layers[i].document);
    if (!x && d.getElementById) x = d.getElementById(n);
    return x;
}

function MM_swapImage() { //v3.0
    var i, j = 0,
        x, a = MM_swapImage.arguments;
    document.MM_sr = new Array;
    for (i = 0; i < (a.length - 2); i += 3)
        if ((x = MM_findObj(a[i])) != null) {
            document.MM_sr[j++] = x;
            if (!x.oSrc) x.oSrc = x.src;
            x.src = a[i + 2];
        }
}

//콤마찍기
function comma(str) {
    str = String(str);
    return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
}

//콤마풀기
function uncomma(str) {
    str = String(str);
    return str.replace(/[^\d]+/g, '');
}

//값 입력시 콤마찍기
function inputNumberFormat(obj) {
    obj.value = comma(uncomma(obj.value));
}

//숫자만 입력
function numCheck(evt) {
    var code = evt.which ? evt.which : event.keyCode;
    if (code < 48 || code > 57) {
        return false;
    }
}


function gotopage() {
    document.location.href = '?pid=' + getUrlParameter('pid') + '&pageNum=' + arguments[0];
}

function gotopagecp() {
    document.location.href = '?pid=' + getUrlParameter('pid') + '&pageNum2=' + arguments[0];
}

function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
};


var ajaxID;

function ajaxBalance() {
    ajaxID = "balance";
    document.getElementById(ajaxID).innerHTML = "<img src=\"/images/loading_login.gif\" width=\"75\" border=\"0\" align=\"absmiddle\">"
    //document.getElementById("cash_refresh").style.display="none";
    sendRequest(ajaxRequestCommon, '&1=1&CmdMode=', 'GET', '../game/member.balance.total.html', true);

}

function ajaxBalance2(val) {
    ajaxID = "balance_" + val;
    document.getElementById(ajaxID).innerHTML = "<img src=\"/images/loading_login.gif\" width=\"75\" border=\"0\" align=\"absmiddle\" style='margin-top: -4px;'>"
    //document.getElementById("cash_refresh").style.display="none";
    sendRequest(ajaxRequestCommon, '&1=1&CmdMode=&ServiceCode=' + val, 'GET', '../game/member.balance.html', true);
}


function ajaxBalSec(sec) {
    ajaxID = "balance_" + sec;
    document.getElementById(ajaxID).innerHTML = "<img src=\"/images/loading_login.gif\" width=\"75\" border=\"0\" align=\"absmiddle\">"
    //document.getElementById("cash_refresh").style.display="none";
    sendRequest(ajaxRequestCommon, '&1=1&CmdMode=', 'GET', '../game/member.balance.total.html', true);

}


function ajaxRequestBalance(oj) {
    var result = (oj.responseText);
    try {
        if (result.length > 0) {
            document.getElementById(ajaxID).innerHTML = result;
            document.getElementById("cash_refresh").style.display = "";

        } else {
            document.getElementById(ajaxID).innerHTML = "SYSTEM.ERROR!";
        }
    } catch (e) {}
}

function ajaxRequestCommon(oj) {
    var result = (oj.responseText);
    var result2 = result.replace('원', '');
    try {
        if (result.length > 0) {
            document.getElementById(ajaxID).innerHTML = result + '원';
            document.getElementById("cash_refresh").style.display = "";

        } else {
            document.getElementById(ajaxID).innerHTML = "SYSTEM.ERROR!";
        }
    } catch (e) {}
}



function InfoUser() {

    $.ajax({
        type: "GET",
        url: "../_proc/_info.asp?" + Math.random(),
        cache: false,
        async: false,

        beforeSend: function() {

        },
        success: function(response, status) {

            if (response.result == 'success') {
                $('.userID').val(response.data.player_id).html(response.data.player_id);
                $(".userAccountNumber").val(response.data.account_number).html(response.data.account_number);
                $(".userAccountHolder").val(response.data.account_holder).html(response.data.account_holder);
                $(".userBankName").val(response.data.bank_name).html(response.data.bank_name);
                $(".userName").val(response.data.player_name).html(response.data.player_name);
                $("#UserInfo").data('accountholder', response.data.account_holder).data('checkedupdate', response.data.checked_update).data('moneymoveused', response.data.moneymove_used);

                $("#UserInfo").data('bankinfo', response.data.account_holder + '^' + response.data.bank_name + '^' + response.data.account_number);

                $("#UserCompPer").html(response.data.comp_set);
            }

        },
        error: function(err, xhr) {

        }
    });
}

function GetUserBalanceAll(e, target) {

    $.ajax({
        type: "GET",
        url: "../_proc/_balance.asp?" + Math.random(),
        cache: false,
        async: false,

        beforeSend: function() {

        },
        success: function(data, status) {

            var balanceData = '<option value="" selected>게임선택</option>';
            var TotalBalanceData = '';
            var PrintBalance = '';
            var noSelect = '';

            jQuery.each(data, function(i, v) {

                var sel = '';

                if (i == 'wallet_balance') {

                    $('.walletBalance').val(v);
                    $('.walletYn').val("Y");
                    $(".offerGameCode").val("999");
                    noSelect = 'Y';
                } else if (i == 'total_balance') {

                    if (e == 'all') {
                        sel = 'selected';
                        PrintBalance = v;
                    }

                    TotalBalanceData += '<option value="total" ' + sel + '>TOTAL : ' + v + ' 원</option>';

                } else {

                    var btl = i.split('_');

                    if (e != 'all') {
                        if (e == btl[0]) {
                            sel = 'selected';
                            PrintBalance = comma(v);
                        }
                    }

                    //balanceData += '<option value="' + btl[0] + '" ' + sel + '>' + btl[1] + ' : ' + comma(v) + '원</option>';
                    balanceData += '<option value="' + btl[0] + '" ' + sel + '>' + btl[1] + '</option>';

                }
            });
            if (noSelect == "Y") {
                $(".game_code_total").html(TotalBalanceData + balanceData);
                $(".game_code_select").html(balanceData);
                $("#" + target).html(PrintBalance + '원').data('balance', uncomma(PrintBalance));
            }


        },
        error: function(err, xhr) {

        }
    });
}

function GetUserBalanceDetail(e, target) {

    var url = '_balance_detail';

    if (e == 'refresh') {
        e = $("#game_code_total").val();
        if (e == 'total') {
            url = '_balance_total';
        }
    }

    $.ajax({
        type: "GET",
        url: "../_proc/" + url + ".asp?code=" + e,
        cache: false,
        async: false,

        beforeSend: function() {

            GetUserBalanceAll(e, target);
            $("#" + target).html('로딩중..').data('balance', 'loading');
            $('.balanceSelect').data('balance', 'loading');
        },
        success: function(data, status) {
            if (data.result == 'success') {
                $("#" + target).html(comma(data.balance) + '원').data('balance', data.balance);
                $('.balanceSelect').data('balance', data.balance);
            }
        },
        error: function(err, xhr) {

        }
    });
}

function GetMoneyHistory() {

    $.ajax({
        type: "GET",
        url: "../_proc/_history.asp",
        cache: false,
        async: false,

        beforeSend: function() {

        },
        success: function(response, status) {

            if (response.result == 'success') {
                var html_history = '';
                var history = response.list;
                $.each(history, function(k, v) {
                    var splitData = v.split('^');
                    if (splitData[5] != 'xxx') {
                        splitData[4] = splitData[4] + ' ' + splitData[5];
                    }
                    html_history += '<tr>';
                    html_history += '<td>' + splitData[2] + '</td>';
                    html_history += '<td>' + splitData[0] + '</td>';
                    html_history += '<td>' + splitData[1] + '</td>';
                    html_history += '<td>' + splitData[3] + '</td>';
                    html_history += '<td>' + splitData[4] + '</td>';
                    html_history += '</tr>';
                });

                if (history.length == undefined) {
                    html_history += '<tr>';
                    html_history += '<td colspan="5">입출금 내역이 존재하지 않습니다.</td>';
                    html_history += '</tr>';
                }

                $("#moneyHistory").html(html_history);
            }

        },
        error: function(err, xhr) {

        }
    });
}