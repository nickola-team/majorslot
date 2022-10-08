var nCheckLetter = 0;

var nLetterCnt = 0;
var nMainLetterCnt = 0;

var wndGame;
var bChkSession = true;

var alarm = new Audio('/frontend/daenamu/snd/msg_recv_alarm.mp3');
alarm.addEventListener('ended', function() {
    this.currentTime = 0;
    this.play();
}, false);

function onGoUrl(strUrl) {
    if(nCheckLetter == 1) {
        alert('먼저 중요 쪽지를 확인해주세요.');
        return;
    }
    
    document.location.href = strUrl;
}

function onTransAll() {
    if(nCheckLetter == 1) {
        alert('먼저 중요 쪽지를 확인해주세요.');
        return;
    }

    if(confirm('게임머니를 전부 보유머니로 회수하시겠습니까?')) {
        $.ajax({
        type: "GET",
        url: "/onTrasferAllToUser",
        cache: false,
        async : false,
        success: function(data){
            // alert(data.message);
        }
    });
    }
}

function onPopup(url, width, height, wid, gameid) {
    if(nCheckLetter == 1) {
        alert('먼저 중요 쪽지를 확인해주세요.');
        return;
    }
    
    if(!confirm(gameid + ' 게임을 실행하시겠습니까?')) return;
    
    var leftPosition, topPosition;
    leftPosition = (window.screen.width / 2) - ((width / 2) + 10);
    topPosition = (window.screen.height / 2) - ((height / 2) + 50);
    wndGame = window.open(url, wid,
    "status=no,height=" + height + ",width=" + width + ",resizable=yes,left="
    + leftPosition + ",top=" + topPosition + ",screenX=" + leftPosition + ",screenY="
    + topPosition + ",toolbar=no,menubar=no,scrollbars=no,location=no,directories=no");
}

function addDeposit(money){
    if(nCheckLetter == 1) {
        alert('먼저 중요 쪽지를 확인해주세요.');
        return;
    }
    
    var amount = $("#deposit_amount").val();
    if(amount == '') amount = 0;

    if(money == 0) $("#deposit_amount").val('');
    else $("#deposit_amount").val(comma(parseInt(uncomma(amount)) + parseInt(money)));
}

function onAskAccount(){
    if(nCheckLetter == 1) {
        alert('먼저 중요 쪽지를 확인해주세요.');
        return;
    }

    var amount = uncomma($("#deposit_amount").val());
    if (amount == '' || amount == 0) { 
        alert("입금하실 금액을 입력하여 주세요");
        $("#deposit_amount").focus();  
        return; 
    }
    var accountName = $(".userName").val();
    
    $.ajax({
        type: "POST",
        url: "/api/depositAccount",
        data: { money: amount, account:accountName },
        cache: false,
        async: true,
        beforeSend: function(){ },
        success: function(data, status){
            if (data.error) {
                alert(data.msg);
                return;
            }
            $("#depositAcc").html(data.msg);
            if (data.url != null)
            {
                var leftPosition, topPosition;
                width = 600;
                height = 1000;
                leftPosition = (window.screen.width / 2) - ((width / 2) + 10);
                topPosition = (window.screen.height / 2) - ((height / 2) + 50);
                wndGame = window.open(data.url, "Deposit",
                "status=no,height=" + height + ",width=" + width + ",resizable=yes,left="
                + leftPosition + ",top=" + topPosition + ",screenX=" + leftPosition + ",screenY="
                + topPosition + ",toolbar=no,menubar=no,scrollbars=no,location=no,directories=no");
            }
        },
        error: function(err, xhr){ }
    });
}

function convertDeal(money) {

    if (money < 30000) {
        alert("전환 최소금액은 30,000원 입니다.");
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
            alert('보너스금이 보유금으로 전환되었습니다.');
            location.reload(true);
        },
        error: function (err, xhr) {
            alert(err.responseText);
        }
    });

}
function DepositProc(){
    if(nCheckLetter == 1) {
        alert('먼저 중요 쪽지를 확인해주세요.');
        return;
    }

    var amount = uncomma($("#deposit_amount").val());

    if (amount == '' || amount == 0) { 
        alert("입금하실 금액을 입력하여 주세요");
        $("#deposit_amount").focus();  
        return; 
    }
    if (amount < 30000) { 
        alert("입금은 3만원 이상부터 가능합니다"); $("#deposit_amount").focus();  return false; 
    }


    if(!confirm("입금신청 하시겠습니까?")) return;

    var data = { accountName: $(".userName").val(), bank:$(".userBankName").val(), no:$(".accountNo").val(), money: amount };
    $.ajax({
        type: "POST",
        url: "/api/addbalance",
        data: data,
        cache: false,
        async: true,
        beforeSend: function(){ },
        success: function(response, status){
            if (response.error) {
                alert(response.msg);
                if (response.code == "001") {
                    location.reload();
                } else if (response.code == "002") {
                }
                return;
            }

            alert("충전 신청이 완료되었습니다.");
            location.reload();
        },
        error: function(err, xhr){ }
    });
}

function onAsk(){
    if(nCheckLetter == 1) {
        alert('먼저 중요 쪽지를 확인해주세요.');
        return;
    }

    var data = { nType: $('#nType').val(), strQueContent: $('#strQueContent').val() }
    $.ajax({
        type: "GET",
        url: "/onChatCreate",
        data: data,
        cache: false,
        async: true,
        beforeSend: function(){ },
        success: function(response, status){
            if(response.success == -1){
                if(wndGame != null) wndGame.close();
                document.location.href = '/login';
            } else {
                alert(response.message);
            }
        },
        error: function(err, xhr){ }
    });
}

function addWithdraw(money) {
    if(nCheckLetter == 1) {
        alert('먼저 중요 쪽지를 확인해주세요.');
        return;
    }

    var amount = $("#withdraw_amount").val();
    if(amount == '') amount = 0;
    if(money == 0) $("#withdraw_amount").val('');
    else $("#withdraw_amount").val(comma(parseInt(uncomma(amount)) + parseInt(money)));
}

function WithdrawProc() {
    if(nCheckLetter == 1) {
        alert('먼저 중요 쪽지를 확인해주세요.');
        return;
    }

    var amount = uncomma($("#withdraw_amount").val());
    if (amount == '' || amount == 0) { 
        alert("출금하실 금액을 입력하여 주세요"); $("#withdraw_amount").focus();  return false; 
    }
    if (amount < 30000) { 
        alert("출금은 3만원 이상부터 가능합니다"); $("#withdraw_amount").focus();  return false; 
    }
    if (confirm("출금신청 하시겠습니까?")){
        var data = { accountName: $(".userName").val(), bank:$(".userBankName").val(), no:$(".accountNo").val(), money: amount };
        $.ajax({
            type: "POST",
            url: "/api/outbalance",
            data: data,
            cache: false,
            async: true,
            beforeSend: function(){ },
            success: function(response, status){
                if (response.error) {
                    alert(response.msg);
                    if (response.code == "001") {
                        location.reload();
                    } else if (response.code == "002") {
                    }
                    return;
                }
    
                alert("환전 신청이 완료되었습니다.");
                location.reload();
            },
            error: function(err, xhr){ }
        });
    }
}



function tabActionSlot(plat, pid, platcode, platidx) {
    if(nCheckLetter == 1) {
        alert('먼저 중요 쪽지를 확인해주세요.');
        return;
    }
    
    $.ajax({
        type: "GET",
        url: "/slotList",
        cache: false,
        async : false,
        data: "nGameTypeCode=" + platcode,
        success: function(data){
            if(data == "") {
                swal('점검중입니다.');
            } else {
                $("#popupbox_ajax").html(data);
            }
        },
        error: function(err, xhr){
            alert(err.responseText);
        }
    });
}
function startGame(gamename, winparam="default") {   
    startGameByProvider(null, gamename, winparam);
}

function startGameByProvider(provider, gamecode, winparam="default") {
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
            alert(data.msg + data.data);
            return;
        }
        
        if (winparam=='default')
        {
            winparam = 'width=1280, height=720, left=100, top=50';
        }
        else if (winparam=='max')
        {
            winparam = "width="+screen.availWidth+",height="+screen.availHeight;
        }
        window.open(data.data.url, "game", winparam);
    }
    });
    
}
function getSlotGames(title, category) {
    
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
                strHtml = `<div class="con_box10">
                                <div class="title1"> `+ title +` <span style="float:right; padding:0 10px 0 10px;margin-top:-5px;"></span>
                                </div>
                            </div>
                            <div style="width:100%;margin-top:-5px">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tbody>`;
                for (var i = 0; i < data.games.length; i++) {

                    if( i % 6  == 0 )
                    {
                        strHtml += `<tr><td height="20">&nbsp;</td></tr>
                                    <tr>
                                    <td  width="100%">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tbody>
                                                <tr>`;
                    }
                    strHtml += `<td >
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td>
                                                <div align="center">`;
                    if (data.games[i].provider)
                    {
                        strHtml +='<a style="cursor:pointer" onclick="startGameByProvider(\'' + data.games[i].provider + '\',\'' + data.games[i].gamecode + '\');">';
                        if (data.games[i].icon)
                            {
                                strHtml += `<img src="${data.games[i].icon}" id="xImag" />
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>`;
                            }
                            else {
                                strHtml += `<img src="/frontend/Default/ico/${data.games[i].provider}/${data.games[i].gamecode}_${data.games[i].name}.jpg" id="xImag" />
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>`;
                            }
                    }
                    else
                    {
                        strHtml +=`<a style="cursor:pointer" onclick="startGame('${data.games[i].name}');">
                                                        <img src="/frontend/Default/ico/${data.games[i].name}.jpg" id="xImag" />
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>`;
                    }
                    strHtml += `<tr>
                                <td height="30px">
                                    <div style="text-align:center;position:absolute;width:203px;margin: auto; ">
                                        <span class="slot_txt_style">${data.games[i].title}</span>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>`;

                    if( i % 6  == 5 )
                    {
                        strHtml += `</tr> </tbody></table></td> </tr>`;
                    }
                }

                for (var i = 0; i < 6- data.games.length % 6; i++) {
                    strHtml += `<td style="width: 203px;"></td>`;
                }
                strHtml +=`</tbody></table><div class="con_box20">
                                <div class="btn_wrap_center">
                                <ul>
                                    <li>
                                    <a href="#" onclick="tabActClose();">
                                        <span class="btn3_1">닫기</span>
                                    </a>
                                    </li>
                                </ul>
                                </div>
                            </div>
                            <div class="con_box20">
                                <div class="btn_wrap_center"></div>
                            </div>
                        </div>`;
                $("#popupbox_ajax").html(strHtml);
                $('#casino_1').popup('show');
            }

        },
        complete: function() {
            $(".wrapper_loading").addClass("hidden");
        }
    });

}


function tabActClose() {
    $('#casino_1').popup('hide');
}

function digit_check(evt){
    var code = evt.which?evt.which:event.keyCode;
    if(code < 48 || code> 57) {
        return false;
    }
}
function CheckHangul(name) {
    strarr = new Array(name.value.length);
    schar = new Array('/','.','>','<',',','?','}','{',' ','\\','|','(',')','+','=','!','@','#','$','%','^','&','*','~','[',']','-','_');
    for (i=0; i <name.value.length; i++) {
        for (j=0; j <schar.length; j++) {
            if (schar[j]==name.value.charAt(i)){
                alert("이름은 한글입력만 가능합니다.");
                document.frm_join.membername.value="" ;
                document.frm_join.membername.focus();
                return false;
            }
            else
                continue;
        }
        strarr[i]=name.value.charAt(i)
        if ((strarr[i]>=0) && (strarr[i] <=9)){
            alert("이름에 숫자가 있습니다. 이름은 한글입력만 가능합니다.");
            document.frm_join.membername.value="" ;
            document.frm_join.membername.focus();
            return false;}
        else if ((strarr[i]>='a') && (strarr[i] <='z')){
            alert("이름에 알파벳이 있습니다. 이름은 한글입력만 가능합니다.");
            document.frm_join.membername.value="" ;
            document.frm_join.membername.focus();
            return false;
        }
        else if ((strarr[i]>='A') && (strarr[i] <='Z')) {
            alert("이름에 알파벳이 있습니다. 이름은 한글입력만 가능합니다.");
            document.frm_join.membername.value="" ;
            document.frm_join.membername.focus();
            return false;
            }
        else if ((escape(strarr[i])> '%60') && (escape(strarr[i]) <'%80') ){
            alert("이름에 특수문자가 있습니다. 이름은 한글입력만 가능합니다.");
            document.frm_join.membername.value="" ;
            document.frm_join.membername.focus();
            return false;
        }
        else{
            continue;
        }
    }
    return true;
}
//아이디 숫자 영문 체크
function CheckId(strValue) {
    var f=document.frm_join;
    strValue=f.memberid.value;
    if (strValue.match(/[^a-zA-Z0-9]/) !=null){
        alert('영문 + 숫자 조합해서 3자리이상된 아이디로 가입해주세요.');
        f.memberid.value="" ;
        f.memberid.focus();
        return;
    }
}

function onDel(nCode){
    if(nCheckLetter == 1) {
        alert('먼저 중요 쪽지를 확인해주세요.');
        return;
    }
    
    if(!confirm('삭제하시겠습니까?')) return;

    $.ajax({
        type: "GET",
        url: "/onChatDel?nCode=" + nCode,
        cache: false,
        async: true,
        dataType: "json",
        success: function(response, status){
            alert(response.message);
            tabActionProc('tab5','chatList');
        },
        error: function(err, xhr){
            
        }
    });
}

function onRegister() {
    var strMark=document.getElementById("strMark").value;
    var strID=document.getElementById("strID").value;
    var strPW=document.getElementById("strPW").value; 
    var strPhone=document.getElementById("strPhone").value; 
    var strBankUser=document.getElementById("strBankUser").value; 
    var strBankNum=document.getElementById("strBankNum").value; 
    var strBankName=document.getElementById("strBankName").value; 
    if(strMark=="" ) {
        alert("추천인 코드 입력하세요.");
        return; 
    } 
    if(strBankName=="직접입력" ) { 
        strBankName=document.getElementById("strDirBankName").value; 
    } 
    if(strID=="" ) { 
        alert("아이디 입력하세요."); 
        return;
    } 
    if(strID.length < 3 || strID.length> 10) { 
        alert("아이디는 3 ~ 10글자 이여야 합니다."); 
        return; 
    } 
    if(strPhone=="" ){ 
        alert("전화번호 입력하세요."); 
        return; 
    } 
    if(strPW=="" ){ 
        alert("비밀번호 입력하세요."); 
        return; 
    } 
    if(strBankUser=="" ){
        alert("예금주 입력하세요."); 
        return; 
    } 
    if(strBankName=="" ){ 
        alert("은행명 입력하세요."); return; } 
    if(strBankNum=="" ){ alert("계좌번호 입력하세요."); return; } 
    var data = {	username: strID,
        password: strPW,
        tel1: strPhone,
        tel2: '',
        tel3: '',
        bank_name: strBankName,
        recommender: strBankUser,
        account_no: strBankNum,
        friend: strMark};
    $.ajax({ 	
        type: "POST" , 
        url: "/api/join" , 
        data: data, 
        cache: false, 
        async: true, 
        beforeSend: function(){ },
        success: function(response, status){ 
            if (response.error) {
                alert(response.msg);
                return;
            }
            else{
                alert(response.msg);
                location.reload(true);
            }
        }, 
        error: function(err, xhr){ } 
    });
}

function directBankName(val){
    var f=document.frm_join;
    strValue=f.banknm.value;
    
    if(val.value=="직접입력" ) {
        f.banknm.value="" ;
        document.getElementById('banknmId').style.display="" ;
    } else {
        f.banknm.value="" ;
        document.getElementById('banknmId').style.display="none" ;
    }
}

//-- 쪽지 --//
function readMessage(idx) {
    $.ajax({
        url: "/api/readMsg",
        type: 'POST',
        data: { id: idx },
        headers: {},
        success: function(data) {},
        error: function(xhr, status, error) {},
        complete: function() {}
    });
}
