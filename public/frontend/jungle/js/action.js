function showLoginPopup(logo='jungle') {
    if (loginYN === 'Y') {
        alert("로그아웃후 이용가능합니다.");
        return;
    }

    var strHtml = `
        <div class="popup-overlay " style="position: fixed; inset: 0px; background: rgba(0, 0, 0, 0.5); display: flex; z-index: 999;">
            <div class="popup-content " style="position: relative; background: none; margin: auto; border: none; padding: 5px; z-index: 97;">
                <div id="fade_2" class="slideDown popup_none popup_content" data-popup-initialized="true" aria-hidden="false" role="dialog" aria-labelledby="open_63141101" style="opacity: 1; visibility: visible; display: inline-block; outline: none; transition: all 0.3s ease 0s; text-align: left; position: relative; vertical-align: middle; overflow-y: auto;">
                    <div class="login_wrap">
                        <div class="login_close_box">
                            <a class="fade_2_close" onclick="closePopup();"><img src="/frontend/jungle/images/popup_close.png"></a>
                        </div>
                        <div class="login_box_wrap">
                            <div class="login_tit">
                                <img src="/frontend/${logo}/images/logo_02.png">
                            </div>
                            <div class="login">
                                <form name="mainLogin_form" id="mainLogin_form" method="post">
                                    <table class="login_table">
                                        <tbody>
                                            <tr>
                                                <td class="login_td1">
                                                    <input name="userid" id="userid" type="text" class="input_login" placeholder="아이디">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="login_td2">
                                                    <input type="password" name="password" id="password" class="input_login" placeholder="비밀번호">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="login_td3" onclick="loginSubmit(mainLogin_form);">
                                                    <a><img src="/frontend/jungle/images/login_btn.png"></a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div></div></div></div>`;

    $("#popup").html(strHtml);
}
function goJoin( )
{
    if ($("#joindiv #username").val() == "")
    {
        alert("ID를 입력해 주십시오.");
        return false ;
    }

    if($("#joindiv #username").val().length < 3)
    {
        alert("ID는 3~10자 까지 입력 가능합니다.");
        return false ;
    }

    if($("#joindiv #username").val().length > 10)
    {
        alert("ID는 3~10자 까지 입력 가능합니다.");
        return false ;
    }
    if ($("#joindiv #password").val().value == "")
    {
        alert("Password를 입력해 주십시오.");
        return false ;
    }

    if ($("#joindiv #password").val().value == "")
    {
        alert("Password를 입력해 주십시오.");
        return false ;
    }

    if($("#joindiv #password").val() != $("#joindiv #password_confirm").val())
    {
        alert("비밀번호가 일치하지 않습니다.");
        return false ;
    }

    if($("#joindiv #password").val().length < 4)
    {
        alert("Password는 4~12까지 입력 가능합니다.");
        return false ;
    }

    if($("#joindiv #password").val().length > 12)
    {
        alert("Password는 4~12까지 입력 가능합니다.");
        return false ;
    }

    if ($("#joindiv #phonenumber").val() == "")
    {
        alert("연락처를 입력해 주십시오.");
        return false ;
    }

    if ($("#joindiv #bankname").val() == "")
    {
        alert("은행명을 입력해 주십시오.");
        return false ;
    }

    if ($("#joindiv #accountno").val() == "")
    {
        alert("계좌정보를 입력해 주십시오.");
        return false ;
    }

    if ($("#joindiv #recommender").val() == "")
    {
        alert("계좌정보를 입력해 주십시오.");
        return false ;
    }


    if ($("#joindiv #friend").val() == "")
    {
        alert("추천인코드를 입력해 주세요.");
        return;
    }

    $.ajax({
        type: 'POST',
        url: "/api/join",
        data: {	username: $("#joindiv #username").val(),
                password: $("#joindiv #password").val(),
                tel1: $("#joindiv #phonenumber").val(),
                tel2: '',
                tel3: '',
                bank_name: $("#joindiv #bankname").val(),
                recommender: $("#joindiv #recommender").val(),
                account_no: $("#joindiv #accountno").val(),
                friend: $("#joindiv #friend").val()},
        cache: false,
        async: false,
        success: function (data) {
            if (data.error) {
                alert(data.msg);
                return;
            }
            else{
                alert(data.msg);
                window.location.href = "/";
            }

        },
        error: function (err, xhr) {
            alert(err.responseText);
        }
    });
}
function showRegisterPopup() {
    if (loginYN === 'Y') {
        alert("로그아웃후 이용가능합니다.");
        return;
    }
    $("#popjoin").show();
}


function showGamesPopup(title, category) {
    if (loginYN !== 'Y') {
        showLoginAlert()
        return;
    }

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
                <div style="
                opacity: 1;
                visibility: visible;
                position: fixed;
                overflow: auto;
                z-index: 100001;
                transition: all 0.3s ease 0s;
                width: 100%;
                height: 100%;
                top: 0px;
                left: 0px;
                text-align: center;
                background-color: rgb(0, 0, 0);
                display: block;"
            >
            <div id="fade_3"
                class="expandOpen popup_none popup_content"
                data-popup-initialized="true"
                aria-hidden="false"
                role="dialog"
                aria-labelledby="open_55563334"
                style="
                opacity: 1;
                visibility: visible;
                display: inline-block;
                outline: none;
                transition: all 0.3s ease 0s;
                text-align: left;
                position: relative;
                vertical-align: middle;
                "
            >
                <div class="popup_wrap">
                <div class="close_box">
                    <a class="fade_3_close" onclick="closePopup();"><img src="/frontend/jungle/images/popup_close.png" /></a>
                </div>
                <div class="popupbox_ajax">
                    <div>
                    <div class="title1">&nbsp;&nbsp;&nbsp;${title}</div>
                    <div style="width: 100%; margin-top: -5px">
                        <table>
                        <tbody>
                            <tr>
                            <td>&nbsp;</td>
                            </tr>
                            <tr>
                            <td>
                                <table>
                                <tbody>
                                    <tr>
                                    <td>
                                        <table>
                                        <tbody>
                                            <tr>`;

            if (data.games.length > 0) {
                for (var i = 0; i < data.games.length; i++) {
                    if (i != 0 && i % 5 == 0) {
                        strHtml += `</tr><tr>`;
                    }
                    if (data.games[i].provider)
                    {
                        strHtml += `<td style="width: 250px; padding-bottom: 30px" onClick="startGameByProvider('${data.games[i].provider}', '${data.games[i].gamecode}');">
                                                <table>
                                                <tbody>
                                                    <tr>
                                                    <td>
                                                        <div>
                                                        <a style="cursor: pointer">`;
                        if (data.games[i].icon)
                        {
                            strHtml += `<img
                                    src="${data.games[i].icon}"
                                    id="xImag"
                                    width="240px"
                                    height="180px"
                                    />`;
                        }
                        else
                        {
                            strHtml += `<img
                                    src="/frontend/Default/ico/${data.games[i].provider}/${data.games[i].gamecode}_${data.games[i].name}.jpg"
                                    id="xImag"
                                    width="240px"
                                    height="180px"
                                    />`;
                        }
                        strHtml += `</a>
                                        </div>
                                    </td>
                                    </tr>
                                    <tr>
                                    <td>
                                        <div style="
                                            text-align: center;
                                            position: absolute;
                                            width: 240px;">
                                        <span class="slot_txt_style">${data.games[i].title}</span>
                                        </div>
                                    </td>
                                    </tr>
                                </tbody>
                                </table>
                            </td>`;
                    }
                    else
                    {
                        strHtml += `<td style="width: 240px; padding-bottom: 30px" onClick="startGame('${data.games[i].name}');">
                                                <table>
                                                <tbody>
                                                    <tr>
                                                    <td>
                                                        <div>
                                                        <a style="cursor: pointer"
                                                            ><img
                                                            src="/frontend/Default/ico/${data.games[i].name}.jpg"
                                                            id="xImag"
                                                            width="240px"
                                                            height="180px"
                                                        /></a>
                                                        </div>
                                                    </td>
                                                    </tr>
                                                    <tr>
                                                    <td>
                                                        <div style="
                                                            text-align: center;
                                                            position: absolute;
                                                            width: 240px;">
                                                        <span class="slot_txt_style">${data.games[i].title}</span>
                                                        </div>
                                                    </td>
                                                    </tr>
                                                </tbody>
                                                </table>
                                            </td>`;
                    }
                    
                }
            }

            strHtml += `</tr>
                                        </tbody>
                                        </table>
                                    </td>
                                    </tr>
                                </tbody>
                                </table>
                            </td>
                            </tr>
                        </tbody>
                        </table>
                        <div class="con_box20">
                        <div class="btn_wrap_center">
                            <ul>
                            <li>
                                <a onclick="closePopup();"><span class="btn3_1">목록으로</span></a>
                            </li>
                            </ul>
                        </div>
                        </div>
                        <div class="con_box20"><div class="btn_wrap_center"></div></div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            </div>`;

            // if (data.games.length > 0) {
            //     for (var i = 0; i < data.games.length; i++) {
            //         strHtml += `
            //             <div class="gamelist" onClick="startGame('${data.games[i].name}');">
            //                 <img class="main-img" src="/frontend/Default/ico/${data.games[i].name}.jpg" alt="thumbnail">
            //                 <div class="foot">
            //                     <p style="word-break: break-all;font-size:18px;">${data.games[i].title}</p>
            //                 </div>
            //             </div>`;
            //     }
            // } else {
            //     strHtml += '<div style="text-align: center;">';
            //     strHtml +=
            //         '<img src="/frontend/Major/major/images/coming_soon.png" style="margin-top: 150px;">';
            //     strHtml += "</div>";
            // }
            // strHtml += "</div></div></div>";

            $("#popup").html(strHtml);
        },
        error: function (err, xhr) {
            alert(err.responseText);
        },
    });

    // } else {
    //     showLoginAlert();
    // }
}

function showProfilePopup() {
    if (loginYN !== 'Y') {
        showLoginAlert()
        return;
    }

    var strHtml = `
        <div class="popup-overlay" style="
        position: fixed;
        inset: 0px;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        z-index: 999;
    ">
    <div class="popup-content" style="
        position: relative;
        background: rgb(0, 0, 0);
        margin: auto;
        border: none;
        padding: 5px;
        z-index: 99;
        ">
        <div id="fade_2" class="slideDown popup_none popup_content" data-popup-initialized="true" aria-hidden="false"
        role="dialog" style="
            opacity: 1;
            visibility: visible;
            display: inline-block;
            outline: none;
            transition: all 0.3s ease 0s;
            text-align: left;
            position: relative;
            vertical-align: middle;
        ">
        <div class="popup_wrap">
            <div class="close_box">
            <a href="#" class="fade_1_close" onclick="closePopup();"><img src="/frontend/jungle/images/popup_close.png" /></a>
            </div>
            <div class="popupbox">
            
            <div id="popuptab_cont4" class="popuptab_cont">
                <div class="title1">마이페이지</div>
                <div class="contents_in">
                <table class="write_title_top" style="width: 100%">
                    <tbody>
                    <tr>
                        <td class="write_title">회원아이디</td>
                        <td class="write_basic">
                        <input class="input1" readonly="" value="${userName}" style="width: 200px" />
                        </td>
                    </tr>
                    <tr>
                        <td class="write_title">보유게임 총머니</td>
                        <td class="write_basic">
                        <input class="input1" readonly="" value="${currentBalance}" style="width: 200px" />
                        </td>
                    </tr>
                    </tbody>
                </table>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
    </div>`;

    $("#popup").html(strHtml);
}
function convertDeal() {

    var money = $("#bonusdeal #money").val();
    if (money < 1000) {
        alert("환전 최소금액은 1,000원 입니다.");
        return;
    }
    if (money % 1000 > 0) {
        alert("1,000원 단위로만 환전 가능합니다.");
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
function showDealOut()
{
    if (loginYN !== 'Y') {
        showLoginAlert()
        return;
    }
    $.ajax({
        type: "POST",
        url: "/api/balance",
        data: null,
        processData: false,
        contentType: false,
        cache: false,
        async: false,
        success: function (data) {
            if (data.error) {
                alert(data.msg);
                return;
            }
            var strHtml = `
                <div class="popup-overlay "
                style="position: fixed; inset: 0px; background: rgba(0, 0, 0, 0.5); display: flex; z-index: 999;">
                <div class="popup-content "
                    style="position: relative; background: rgb(0, 0, 0); margin: auto; border: none; padding: 5px; z-index: 99;">
                    <div id="fade_2" class="slideDown popup_none popup_content" data-popup-initialized="true" aria-hidden="false"
                        role="dialog"
                        style="opacity: 1; visibility: visible; display: inline-block; outline: none; transition: all 0.3s ease 0s; text-align: left; position: relative; vertical-align: middle; overflow-y: auto; height: 600px;">
                        <div class="popup_wrap">
                            <div class="close_box"><a href="#" class="fade_1_close" onclick="closePopup();"><img src="/frontend/jungle/images/popup_close.png"></a>
                            </div>
                            <div class="popupbox">
                                <div id="bonusdeal" class="popuptab_cont">
                                    <div class="title1">보너스 전환</div>
                                    <div class="contents_in">
                                        <div class="con_box10">
                                            <div class="info_wrap">
                                                <div class="info2" style="text-align: center;"><span class="ww_font">보너스금액 <img
                                                            src="/frontend/jungle/images/ww_icon.png" height="30"><input
                                                            class="input1 walletBalance" id="balance_offer" readonly="" value="${data['deal']}">
                                                        원</span></div>
                                            </div>
                                        </div>
                                        <table class="write_title_top" style="width: 100%;">
                                            <tbody>
                                                <tr>
                                                    <td class="write_title">전환금액</td>
                                                    <td class="write_td"></td>
                                                    <td class="write_basic">
                                                        <input id="money" type="hidden" name="money" value="0">
                                                        <input class="input1" id="money1" name="money1" placeholder="0" value="0" onchange="comma()">
                                                        <a href="javascript:money_count('10000');" style="padding-left: 5px;"><span class="btn1_2">1만원</span></a>
                                                        <a href="javascript:money_count('50000');" style="padding-left: 5px;"><span class="btn1_2">5만원</span></a>
                                                        <a href="javascript:money_count('100000');" style="padding-left: 5px;"><span class="btn1_2">10만원</span></a>
                                                        <a href="javascript:money_count('500000');" style="padding-left: 5px;"><span class="btn1_2">50만원</span></a>
                                                        <a href="javascript:money_count('1000000');" style="padding-left: 5px;"><span class="btn1_2">100만원</span></a>
                                                        <a href="javascript:money_count('5000000');" style="padding-left: 5px;"><span class="btn1_2">500만원</span></a>
                                                        <a href="javascript:money_count_hand();" style="padding-left: 5px;"><span class="btn1_1">정정</span></a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="con_box20">
                                            <div class="btn_wrap_center">
                                                <ul>
                                                    <li><a onclick="convertDeal();"><span class="btn3_1">전환하기</span></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;

            $("#popup").html(strHtml);
            $("#myWallet").html(data['balance'] + ' 원');
            $("#myBonus").html(data['deal'] + ' 원');
        }
    });
    
}
function showProfileEditorPopup() {
    if (loginYN !== 'Y') {
        showLoginAlert()
        return;
    }

    var strHtml = `
        <div class="popup-overlay "
        style="position: fixed; inset: 0px; background: rgba(0, 0, 0, 0.5); display: flex; z-index: 999;">
        <div class="popup-content "
            style="position: relative; background: rgb(0, 0, 0); margin: auto; border: none; padding: 5px; z-index: 99;">
            <div id="fade_2" class="slideDown popup_none popup_content" data-popup-initialized="true" aria-hidden="false"
                role="dialog"
                style="opacity: 1; visibility: visible; display: inline-block; outline: none; transition: all 0.3s ease 0s; text-align: left; position: relative; vertical-align: middle; overflow-y: auto; height: 600px;">
                <div class="popup_wrap">
                    <div class="close_box"><a href="#" class="fade_1_close" onclick="closePopup();"><img src="/frontend/jungle/images/popup_close.png"></a>
                    </div>
                    <div class="popupbox">
                        <div id="popuptab_cont4" class="popuptab_cont">
                            <div class="title1">마이페이지</div>
                            <div class="contents_in">
                                <table class="write_title_top" style="width: 100%;">
                                    <tbody>
                                        <tr>
                                            <td class="write_title">회원아이디 </td>
                                            <td class="write_basic"><input class="input1" readonly="" value="${userName}"
                                                    style="width: 200px;"></td>
                                        </tr>
                                        <tr>
                                            <td class="write_title">비밀번호</td>
                                            <td class="write_basic">* 비밀번호 변경시 고객센터에 문의 바랍니다.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>`;

    $("#popup").html(strHtml);
}

function showDepositPopup() {
    if (loginYN !== 'Y') {
        showLoginAlert()
        return;
    }

    $("#popdeposit").show();
}

function showWithdrawPopup() {
    if (loginYN !== 'Y') {
        showLoginAlert()
        return;
    }

    var strHtml = `
        <div class="popup-overlay "
        style="position: fixed; inset: 0px; background: rgba(0, 0, 0, 0.5); display: flex; z-index: 999;">
        <div class="popup-content "
            style="position: relative; background: rgb(0, 0, 0); margin: auto; border: none; padding: 5px; z-index: 99;">
            <div id="fade_2" class="slideDown popup_none popup_content" data-popup-initialized="true" aria-hidden="false"
                role="dialog"
                style="opacity: 1; visibility: visible; display: inline-block; outline: none; transition: all 0.3s ease 0s; text-align: left; position: relative; vertical-align: middle; overflow-y: auto; height: 900px;">
                <div class="popup_wrap">
                    <div class="close_box" onclick="closePopup();"><a href="#" class="fade_1_close"><img src="/frontend/jungle/images/popup_close.png"></a>
                    </div>
                    <div class="popupbox">
                        
                        <div id="popuptab_cont2" class="popuptab_cont">
                            <div class="title1">출금신청</div>
                            <div class="contents_in">
                                <div class="con_box00">
                                    <div class="info_wrap">
                                        <div class="info2">주의사항</div>
                                        <div class="info3">
                                            - 출금 최소 3만원부터 만원단위로 출금 가능하십니다.
                                            <br>
                                            - 출금은 가입하신 동일 예금주로만 가능합니다.
                                        </div>
                                    </div>
                                </div>
                                <div class="con_box10">
                                    <div class="info_wrap">
                                        <div class="info2" style="text-align: center;"><span class="ww_font">내 지갑 <img
                                                    src="/frontend/jungle/images/ww_icon.png" height="30"><input
                                                    class="input1 walletBalance" id="balance_offer" readonly="" value="${currentBalance}">
                                                원</span></div>
                                    </div>
                                </div>
                                <div class="con_box10" id="withdraw">
                                <form method="post" id="fundFrm" name="fundFrm">
                                    <table class="write_title_top">
                                        <tbody>
                                            <tr>
                                                <td class="write_title">ID</td>
                                                <td class="write_td"></td>
                                                <td class="write_basic"><input class="input1 userID" readonly=""
                                                        value="${userName}"></td>
                                            </tr>
                                            <tr>
                                                <td class="write_title">은행이름</td>
                                                <td class="write_td"></td>
                                                <td class="write_basic"><input class="input1" id="bankname" value="${bankname}"></td>
                                            </tr>
                                            <tr>
                                                <td class="write_title">계좌번호</td>
                                                <td class="write_td"></td>
                                                <td class="write_basic"><input class="input1" id="accountno" value="${accountno}"></td>
                                            </tr>
                                            <tr>
                                                <td class="write_title">입금자명</td>
                                                <td class="write_td"></td>
                                                <td class="write_basic"><input class="input1 userName" id="name" value="${recommender}"></td>
                                            </tr>
                                            <tr>
                                                <td class="write_title">출금금액</td>
                                                <td class="write_td"></td>
                                                <td class="write_basic">
                                                    <input id="money" type="hidden" name="money" value="0">
                                                    <input class="input1" id="money1" name="money1" placeholder="0" value="0" onchange="comma()">
                                                    <a href="javascript:money_count('10000');" style="padding-left: 5px;"><span class="btn1_2">1만원</span></a>
                                                    <a href="javascript:money_count('50000');" style="padding-left: 5px;"><span class="btn1_2">5만원</span></a>
                                                    <a href="javascript:money_count('100000');" style="padding-left: 5px;"><span class="btn1_2">10만원</span></a>
                                                    <a href="javascript:money_count('500000');" style="padding-left: 5px;"><span class="btn1_2">50만원</span></a>
                                                    <a href="javascript:money_count('1000000');" style="padding-left: 5px;"><span class="btn1_2">100만원</span></a>
                                                    <a href="javascript:money_count('5000000');" style="padding-left: 5px;"><span class="btn1_2">500만원</span></a>
                                                    <a href="javascript:money_count_hand();" style="padding-left: 5px;"><span class="btn1_1">정정</span></a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    </form>
                                </div>
                                <div class="con_box20">
                                    <div class="btn_wrap_center">
                                        <ul>
                                            <li><a onclick="withdraw();"><span class="btn3_1">출금신청하기</span></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>`;

    $("#popup").html(strHtml);
}

function showNotificationPopup() {
    if (loginYN !== 'Y') {
        showLoginAlert()
        return;
    }

    $("#popnoticelist").show();

}
function showWriteContactPopup() {
    if (loginYN !== 'Y') {
        showLoginAlert()
        return;
    }
    $("#popwritecont").show();
}

function showContactPopup() {
    if (loginYN !== 'Y') {
        showLoginAlert()
        return;
    }
    $.post('/api/messages', null, function(data){
        if(data.error == false){
			var html = `<table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
						<tr>
						<td width="15%" class="list_title1">번호</td>
						<td class="list_title1">제목</td>
						<td class="list_title1">작성시간</td>
						<td class="list_title1">읽은시간</td>
						<td width="12%" class="list_title1">타입</td>
					</tr>`;
			if (data.data.length > 0) {
				for (var i = 0; i < data.data.length; i++) {
					var date = new Date(data.data[i].created_at);
                    var read = '';
					if (data.data[i].read_at == null)
					{
						read = '읽지 않음';
					}
					else
					{
						date1 = new Date(data.data[i].read_at);
						read = date1.toLocaleString();
					}
					var type = (data.user_id!=data.data[i].writer_id)?'수신':'발신';
					html += `<tr onclick="openMessage('${data.data[i].id}')" class="cp">
						<td class="list_notice1">${i+1}</td>
						<td class="list_notice2"> ${data.data[i].title}</td>
						<td class="list_notice2">${date.toLocaleString()}</td>
						<td class="list_notice2">${read}</td>
						<td class="list_notice2">${type}</td>
						</tr>
						<tr id="msg_${data.data[i].id}" style="display:none;">
						<td class="list_notice1"></td>
						<td colspan="4" class="list_notice2">${data.data[i].content}</td>
						</tr>`;
				}
				
			}
			html += `</table>`;
            $('#contactlist').html(html);
			$("#popcontact").show();
            
			
        } else {
            alert(data.msg);
        }
        
    });

    
}

function getBalance() {
    $.ajax({
        type: "POST",
        url: "/api/balance",
        data: null,
        processData: false,
        contentType: false,
        cache: false,
        async: false,
        success: function (data) {
            if (data.error) {
                alert(data.msg);
                return;
            }
            $("#myWallet").html(data['balance'] + ' 원');
            $("#myBonus").html(data['deal'] + ' 원');
        }
    });
}

function startGame(gamename) {
    if (loginYN !== 'Y') {
        showLoginAlert()
        return;
    }
    
    startGameByProvider(null, gamename);
}
function startGameByProvider(provider, gamecode) {
    var formData = new FormData();
    formData.append("provider", provider);
    formData.append("gamecode", gamecode);
    formData.append("_token", $("#_token").val());
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

function loginSubmit(frm) {
    event.preventDefault();

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

    // frm.action = "/login/login.asp";
    // frm.submit();
    var username = frm.userid.value;
    var password = frm.password.value;

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
                alert(data.msg);
            }

            location.reload();
        },
        error: function (err, xhr) {
        }
    });
}

function requestAccount(){
    $.ajax({
        type: "POST",
        url: "/api/depositAccount",
        data: null,
        cache: false,
        async: false,
        success: function (data) {
            $('#depositAccount').text(data.msg);
        },
        error: function (err, xhr) {
            alert(err.responseText);
        },
    });

}


function deposit() {
    var refundname = $("#deposit #name").val();
    var money = $("#deposit #money").val();
    var bank = $("#deposit #bankname").val();
    var accno = $("#deposit #accountno").val();

    if (refundname == "") {
        alert("입금자명을 입력해주세요.");
        $("#deposit #name").focus();
        return;
    }

    if (money < 30000) {
        alert("충전 최소금액은 30,000원 입니다.");
        return;
    }
    if (money % 10000 > 0) {
        alert("10,000원 단위로만 충전 가능합니다.");
        return;
    }

    $.ajax({
        type: "POST",
        url: "/api/addbalance",
        data: { accountName: refundname, bank:bank, no:accno, money: money },
        cache: false,
        async: false,
        success: function (data) {
            if (data.error) {
                alert(data.msg);
                if (data.code == "001") {
                    location.reload();
                } else if (data.code == "002") {
                    $("#deposit #name").focus();
                }
                return;
            }

            alert("충전 신청이 완료되었습니다.");
            // $("#deposit .btn3_2").click();
        },
        error: function (err, xhr) {
            alert(err.responseText);
        },
    });
}

function withdraw() {
    // var refundpassword = $("#withdraw #password").val();
    // if (refundpassword == "") {
    //     alert("출금비밀번호를 입력해주세요.");
    //     $("#withdraw #password").focus();
    //     return;
    // }

    var money = $("#withdraw #money").val();
    var refundname = $("#withdraw #name").val();
    var bank = $("#withdraw #bankname").val();
    var accno = $("#withdraw #accountno").val();
    if (money < 30000) {
        alert("환전 최소금액은 30,000원 입니다.");
        return;
    }
    if (money % 10000 > 0) {
        alert("10,000원 단위로만 환전 가능합니다.");
        return;
    }

    $.ajax({
        type: "POST",
        url: "/api/outbalance",
        data: { accountName: refundname, bank:bank, no:accno, money: money  },
        cache: false,
        async: false,
        success: function (data) {
            if (data.error) {
                alert(data.msg);
                if (data.code == "001") {
                    location.reload();
                } else if (data.code == "002") {
                    $("#withdraw #money1").focus();
                } else if (data.code == "003") {
                    $("#withdraw #money1").val("0");
                } else if (data.code == "004") {
                    $("#withdraw #password").focus();
                } else if (data.code == "005") {
                    $("#withdraw #password").val("");
                }
                return;
            }

            alert("환전 신청이 완료되었습니다.");
            // location.reload();
        },
        error: function (err, xhr) {
            alert(err.responseText);
        },
    });
}

function goLogout() {
    location.href = "/logout";
}

function closePopup() {
    $("#popup").html("");
}
function closePopupDiv(popid) {
    $("#" + popid).hide();
}

function showLoginAlert() {
    alert("로그인 후 이용가능합니다.");
}

function openNotice(idx){
    if($('#ntc_'+idx).hasClass("open")){
        $('#ntc_'+idx).css('display','none').removeClass('open');;
    } else {
        $('#ntc_'+idx).css('display','table-row').addClass('open');
    }
}

function openMessage(idx){
    if($('#msg_'+idx).hasClass("open")){
        $('#msg_'+idx).css('display','none').removeClass('open');;
    } else {
        $('#msg_'+idx).css('display','table-row').addClass('open');
    }
    $.post('/api/readMsg',{id : idx},function(data){
    }); 
}

function writeMessage(){
    var subject = $('#subject').val();
    var writeArea = $('#writeArea').val();
    if(!subject){
        alert('제목을 입력해주세요');
        return;
    }
    if(!writeArea){
        alert('내용을 입력해주세요');
        return;
    }
    $.post('/api/writeMsg', {
        title : subject, 
        content : writeArea, 
    },function(data){
        if(data.error == false){
            alert('저장 되었습니다');
            window.location.href = "/";
        } else {
            alert(data.msg);
        }
    });
}