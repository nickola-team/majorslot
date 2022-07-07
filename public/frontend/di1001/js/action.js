var nCheckLetter = 0;

var nLetterCnt = 0;
var nMainLetterCnt = 0;

var wndGame;
var bChkSession = true;

var alarm = new Audio('/frontend/di1001/snd/msg_recv_alarm.mp3');
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

    var data = { nType: 0, strQueContent: "입금계좌번호 문의합니다." }
    
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
    if (amount == '' || amount == 0) { alert("입금하실 금액을 입력하여 주세요"); $("#withdraw_amount").focus();  return false; }
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
            },
            error: function(err, xhr){ }
        });
    }
}

function tabActionPopView(obj, pid, idx) {
    if(nCheckLetter == 1&& !pid.includes('letter')) {
        alert('먼저 중요 쪽지를 확인해주세요.');
        return;
    }
    
    if(obj) {
        var tab = $(".popup_tab1 li." + obj).closest(".popup_tab1 > li");
        tab.siblings().removeClass("sk_tab_active_01");
        tab.addClass("sk_tab_active_01");

        var target = $(tab.attr("data-target")+".sk_tab_con_01");
        $(".sk_tab_con_01").addClass("sk_tab_hidden_01");
        target.removeClass("sk_tab_hidden_01");
        target.html("");
    } else {
        $(".sk_tab_con_01").html("");
    }
    if( pid == "letterView" ){
              
    }
    else if( pid == "chatView" ){
            data = `<div class="title1">
                        문의
                    </div>
                        <div class="contents_in">
                                <div class="con_box10">             
                                        <table width="98.5%" border="0" cellspacing="0" cellpadding="0" style="margin-left:10px;">
                                            <tr>
                                                <td height="30" align="right"><span class="view_box">글쓴이</span> 운영자      <span class="view_box">상태</span> 답변완료</td>
                                            </tr>
                                            <tr>
                                                <td class="view1 write_title_top" style="color: greenyellow">문의</td>
                                            </tr>
                                            <tr>
                                                <td class="view1 write_title_top" style="padding-left: 100px;">문의제목 : 계좌문의</td>
                                            </tr>
                                            <tr>
                                                <td class="view1 write_title_top" style="padding-left: 100px;">문의내용 : 
                                                    입금계좌번호 문의합니다.
                                                </td>
                                            </tr> 
                                            <tr>
                                                <td class="view1 write_title_top" style="color: greenyellow">응답</td>
                                            </tr>
                                            <tr>
                                                <td class="view1 write_title_top" style="padding-left: 100px;">응답제목 : 입금 계좌문의</td>
                                            </tr>
                                            <tr>
                                                <td class="view1 write_title_top" style="padding-left: 100px;">응답내용 : 
                                                    <p>안녕하세요 계좌안내 입니다.</p><p><br></p><p>【은행명 : 전북은행】 - 【예금주 : 정호영】 - 【계좌번호 : 1021-01-6610476】</p><p><br></p><p>계좌번호는 수시로 변경될 수 있습니다.</p><p>입금전 반드시 계좌문의를 통하여 계좌번호를 확인후.</p><p>입금해주시기 바랍니다 감사합니다.</p>
                                                </td>
                                            </tr>
                                    </table> 
                            </div>
                            <div class="con_box20">
                                <div class="btn_wrap_center">
                                    <ul>
                                        <li><a href="#" onclick="tabActionProc('tab5','chatList');"><span class="btn2_1">목록</span></a></li>                                                                                                                                                                       
                                    </ul>
                                </div>
                            </div>
                    </div>`;
    }
    else if(pid == "noticeView"){
            data= `<div class="title1">
                                    공지사항
                            </div>
                            <div class="contents_in">
                                    <div class="con_box10">             
                                            <table width="98.5%" border="0" cellspacing="0" cellpadding="0" style="margin-left:10px;">
                                                    <tr>
                                                            <td height="30" align="right"><span class="view_box">글쓴이</span> 운영자      <span class="view_box">작성일</span> 2021-11-09 17:10:38      </td>
                                                    </tr>
                                                    <tr>
                                                            <td class="view1 write_title_top">★공지사항★</td>
                                                    </tr>
                                                    <tr>
                                                            <td class="view2">
                                                                    <?xml encoding="utf-8" ?><?xml encoding="utf-8" ?><!--?xml encoding="utf-8" ?--><!--?xml encoding="utf-8" ?--><!--?xml encoding="utf-8" ?--><!--?xml encoding="utf-8" ?--><!--?xml encoding="utf-8" ?--><!--?xml encoding="utf-8" ?--><!--?xml encoding="utf-8" ?--><!--?xml encoding="utf-8" ?--><!--?xml encoding="utf-8" ?--><!--?xml encoding="utf-8" ?--><!--?xml encoding="utf-8" ?--><!--?xml encoding="utf-8" ?--><!--?xml encoding="utf-8" ?--><!--?xml encoding="utf-8" ?--><!--?xml encoding="utf-8" ?--><!--?xml encoding="utf-8" ?--><!--?xml encoding="utf-8" ?--><h2><span style='font-family: "Arial Black";'><b>입금계좌는 수시로 변경됩니다.</b></span></h2><h2><span style='font-family: "Arial Black";'><b><br></b></span><b style="color: inherit; font-family: inherit;">반드시 입금전 계좌문의후 입금해주시기 바랍니다.</b></h2><h2><b style="color: inherit; font-family: inherit;"><br></b><b style="color: inherit; font-family: inherit;">전계좌로 입금시 확인이 불가능합니다.</b></h2>
                            
                                                            </td>
                                                    </tr>
                                            </table>
                                    </div>
                                    <div class="con_box20">
                                            <div class="btn_wrap_center">
                                                    <ul>
                                                            <li><a href="#" onclick="tabActionProc('tab6','noticeList');"><span class="btn2_1">목록</span></a></li>                                                                                                                                                                       
                                                    </ul>
                                            </div>
                                    </div>                                                         
                            </div>
                            <script>
                                    getUserMoney();
                            </script>`;
    }

    $(".sk_tab_con_01").html(data);
    
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
            alert(data.msg + data.data);
            return;
        }
        window.open(data.data.url, "game", "width=1280, height=720, left=100, top=50");
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
                        strHtml +=`<a style="cursor:pointer" onclick="startGame('${data.games[i].gamecode}');">
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
